<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getUserDataToEdit($id)
    {
        $data = User::with('education', 'experience')->where("users.userID", $id)->get();



        return response()->json([
            'data' => $data,
        ]);
    }
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->back();
    }
    public function getUserData(Request $request, $page)
    {
        $perPage = 3; // Number of records per page

        $searchTerm = $request->input('search'); // Get the search term from the request

        $query = User::with('education', 'experience');

        // Check if a search term is provided
        if (!empty($searchTerm)) {
            // Add search conditions to the query
            $query->where(function ($innerQuery) use ($searchTerm) {
                $innerQuery->where('userName', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('userHobby', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('userEmail', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Calculate the offset based on the requested page
        $offset = ($page - 1) * $perPage;

        // Get records based on offset and limit
        $data = $query->skip($offset)->take($perPage)->get();

        return response()->json([
            'data' => $data,
        ]);
    }


    public function createUpdateUser(Request $request)
    {
        if (!is_null($request['userID'])) {
            $validator = Validator::make($request->all(), [
                'userName' => 'required|regex:/^[a-zA-Z\s]+$/',
                'userEmail' => 'email|required',
                'userPhoneNo' => 'required|max:10|min:10',
                'userGender' => 'required',
                'userExperience.*' => 'required',
                'userHobby' => 'required',
                'userMessage' => 'required',
            ]);

            UserExperience::where('relatedUserID', $request["userID"])->delete();
            UserEducation::where('relatedUserID', $request["userID"])->delete();
        } else {
            $validator = Validator::make($request->all(), [
                'userName' => 'required|regex:/^[a-zA-Z\s]+$/',
                'userEmail' => 'email|required',
                'userPhoneNo' => 'required|max:10|min:10',
                'userGender' => 'required',
                'userExperience.*' => 'required',
                'userHobby' => 'required',
                'userPicture' => 'required|file|mimes:jpg,jpeg,png',
                'userMessage' => 'required',
            ]);
        }

        if (!$validator->passes()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        if ($file = $request->file('userPicture')) {
            $name = Time();
            $delimeter = ".";
            $extension = $file->getClientOriginalExtension();

            $pictureName = $name . $delimeter . $extension;

            $file->move(public_path('user_images/'), $pictureName);
        } else {
            $pictureName = User::where('userID', $request["userID"])->value('userPicture');
        }

        $addUser = User::updateOrCreate(['userID' => $request["userID"] ?? 0], [
            "userName" => $request["userName"],
            "userEmail" => $request["userEmail"],
            "userPhoneNo" => $request["userPhoneNo"],
            "userGender" => $request["userGender"],
            "userHobby" => implode(",", $request["userHobby"]),
            "userPicture" => $pictureName,
            "userMessage" => $request["userMessage"],
        ]);

        $latestUserID = User::latest()->take(1)->value('userID');

        foreach ($request["userExperience"] as $key => $value) {
            UserExperience::create([
                'relatedUserID' => $latestUserID,
                'userExperience' => $value,
            ]);
        }

        foreach ($request["userEducation"] as $key => $value) {
            UserEducation::create([
                'relatedUserID' => $latestUserID,
                'userEducation' => $value,
            ]);
        }
    }
}
