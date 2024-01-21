<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\MessageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::get('login/{provider}', [LoginController::class, 'redirectToProvider']);
Route::get('login/{provider}/callback', [LoginController::class, 'handleProviderCallback']);

Route::get('/user', [UserController::class, "getUser"]);
Route::post('/register', [UserController::class, 'store']);
Route::post('/authenticate', [UserController::class, 'login']);
Route::post('/authenticate', [UserController::class, 'authenticate']);
Route::post('/forgot-password',  [UserController::class, 'sendResetLinkEmail']);
Route::post('/reset-password',  [UserController::class, 'reset'])->name('password.reset');
Route::get('/auth/google', [UserController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback',[UserController::class, 'handleGoogleCallback']);

Route::middleware('auth:sanctum')->get('/logout', [UserController::class, "logout"]);
Route::controller(RoleController::class)->group(
    function(){
        Route::prefix('role')->group(
            function(){
                Route::post('/addRoleUser','setRole')->name('role.addRoleUser');
                Route::post('/store','store')->name('role.store');
                Route::put('/updateRole','updateRole')->name('role.updateRole');
                Route::post('/RemoveRole','RemoveRole')->name('role.RemoveRole');
                Route::post('/updateRoleUser','updateRoleUser')->name('role.updateRoleUser');
                Route::post('/RemoveAllRoles','RemoveAllRoles')->name('role.RemoveAllRoles');
                Route::delete('/destroy','destroy')->name('role.destroy');
            }
        );
    }
);

Route::apiResource('/datasets', DatasetController::class);
Route::apiResource('/messages', MessageController::class);

