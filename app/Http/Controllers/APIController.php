<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserEducation;
use App\Models\UserExperience;
use Illuminate\Support\Facades\Validator;

class APIController extends Controller
{
    public function createOrUpdateUser(Request $request)
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

    public function deleteUser($userID)
    {
        $data = User::findOrFail($userID)->delete();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function userData($id, Request $request)
    {
        $searchTerm = $request->input('search'); // Get the search term from the request

        $data = User::with('education', 'experience')
            ->where("users.userID", $id)->get();

        return response()->json([
            'data' => $data,
        ]);
    }
    public function getUsers()
    {
        $data = User::all();

        return response()->json([
            'data' => $data,
        ]);
    }
}
