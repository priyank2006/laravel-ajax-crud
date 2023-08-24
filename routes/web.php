    <?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Default Page Route
Route::get('/', function () {
    return view('index');
});

// Getting Paginated Users Data
Route::get('getUserData/{page}', [UserController::class, 'getUserData']);

// Getting Data of User For Editing
Route::get('getUserDataToEdit/{id}', [UserController::class, 'getUserDataToEdit']);
