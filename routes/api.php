<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/health-check',function(){
    return response()->json([
        'status' => true,
        'message' => 'Working properly'
    ]);
});

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->prefix('task')->group(function(){
    Route::post('get-list', [TaskController::class, 'getList']);
    Route::post('create',[TaskController::class, 'create']);
    Route::patch('{task}',[TaskController::class, 'update']);
    Route::delete('{task}',[TaskController::class, 'delete']);
    Route::post('assign-user',[TaskController::class, 'assignTaskToUser']);
    Route::get('get-assinged',[TaskController::class, 'getAssignedTask']);
    Route::post('remove-user',[TaskController::class, 'unassignUserFromTask']);
});

