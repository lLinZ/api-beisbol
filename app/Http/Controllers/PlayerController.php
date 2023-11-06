<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Role;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller
{
    //
    public function get_all_players()
    {
        $players = Player::all();
        return response()->json(['status' => true, 'data' => $players]);
    }
    public function get_player_by_id(Request $request, Player $player)
    {
        try {
            $player_data = Player::where(['id' => $player->id]);
            return response()->json(['status' => true, 'data' => $player_data]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false], 204);
        }
    }
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'document' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'phone' => $request->phone,
            'email' => $request->email,
            'document' => $request->document,
            'password' => Hash::make($request->password),
            'color' => '#4caf50',
        ]);
        // Obtener status activo o crear status si no existe
        $status = Status::firstOrNew(['description' => 'Activo']);
        $status->save();
        // Se asocia el status al usuario
        $user->status()->associate($status);

        // Obtener rol cliente o crear rol si no existe
        $role = Role::firstOrNew(['description' => 'Master']);
        $role->save();
        // Se asocia el rol al usuario
        $user->role()->associate($role);

        // Se guarda el usuario
        $user->save();

        // Token de auth
        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json(['data' => $user, 'token' => $token, 'token_type' => 'Bearer', 'status' => true]);
    }
    public function edit_player(Request $request, Player $player)
    {
        try {
            $player_data = Player::where(['id' => $player->id]);
            return response()->json(['status' => true, 'data' => $player_data]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false], 204);
        }
    }
    public function delete_player(Request $request, Player $player)
    {
        try {
            $player_data = Player::where(['id' => $player->id]);
            return response()->json(['status' => true, 'data' => $player_data]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false], 204);
        }
    }
}
