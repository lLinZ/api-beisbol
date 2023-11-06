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


// ANY -> Ruta de logeo de usuarios
Route::post('login', [AuthController::class, 'login']);

// Ruta de registro de administrador
Route::post('master/create/24548539', [AuthController::class, 'register_master_no_auth_required']);

// Rutas protegidas
Route::middleware(['auth:sanctum'])->group(function () {
    // USER/MASTER -> Cerrar sesion
    Route::get('logout', [AuthController::class, 'logout']);

    // MASTER -> Ruta de registro de administrador
    Route::post('master/create', [AuthController::class, 'register_master']);

    // USER -> Ruta de registro de usuarios
    Route::post('user/create', [AuthController::class, 'register']);

    // USER -> Ruta para validar token
    Route::get('user/data', [AuthController::class, 'get_logged_user_data']);

    // USER -> Editar usuario
    Route::put('user/edit/{user}', [AuthController::class, 'edit_user_data']);

    // USER -> Editar color de tema
    Route::put('user/edit/{user}/color', [AuthController::class, 'change_theme_color']);

    // PLAYER -> Obtener jugador por su ID
    Route::get('player/{player}', [PlayerController::class, 'get_player_by_id']);

    // PLAYER -> Obtener todos los jugadores
    Route::get('player/all', [PlayerController::class, 'get_all_players']);

    // PLAYER -> Editar un jugador
    Route::put('player/edit/{player}', [PlayerController::class, 'edit_player']);

    // PLAYER -> Crear un jugador
    Route::post('player/create', [PlayerController::class, 'create']);

    // PLAYER -> Eliminar un jugador
    Route::delete('player/delete/{player}', [PlayerController::class, 'delete_player']);

    // ROLE -> Obtener todos los roles
    Route::get('roles', [RoleController::class, 'index']);

    // ROLE -> Crear un rol
    Route::post('roles', [RoleController::class, 'create']);

    // ROLE -> Editar un rol
    Route::put('roles/{role}', [RoleController::class, 'update']);

    // ROLE -> Obtener los roles por usuario
    // Route::get('users/roles', [AuthController::class, 'get_role']);
});
