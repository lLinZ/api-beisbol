<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;

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

// Ruta de registro de usuarios
Route::post('register', [AuthController::class, 'register']);

// Ruta de logeo de usuarios
Route::post('login', [AuthController::class, 'login']);

Route::get('roles', [RoleController::class, 'index']);
Route::post('roles', [RoleController::class, 'create']);
Route::put('roles/{role}', [RoleController::class, 'update']);
// Rutas protegidas
Route::middleware(['auth:sanctum'])->group(function () {

    // Cerrar sesion
    Route::get('logout', [AuthController::class, 'logout']);

    // Traer productos
    Route::get('products', [ProductController::class, 'index']);

    // Roles
});
