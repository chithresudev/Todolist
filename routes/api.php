<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('task')->group(function() {
  Route::get('', [TaskController::class, 'index']);
  Route::post('/create', [TaskController::class, 'createTask']);
  Route::post('/{task?}/create-sub-task', [TaskController::class, 'createSubTask']);
  Route::post('/{task?}/states-update', [TaskController::class, 'statesUpdate']);
  Route::post('/{task?}/remove', [TaskController::class, 'taskRemove']);
});
