<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function get_all_users(Request $request)
    {

        $users = User::with('role')->whereHas('status', function ($query) {
            $query->where('description', 'Activo');
        })->whereHas('role', function ($query) {
            $query->where('description', 'Cliente');
        })->get();

        return response()->json(['status' => true, 'data' => $users]);
    }
    public function get_logged_user_data(Request $request)
    {

        $user_data = [];
        $data = $request->user();
        $user_data = [
            'name' => $data->name,
            'lastname' => $data->lastname,
            'phone' => $data->phone,
            'short_address' => $data->short_address,
            'role_id' => $data->status_id,
            'status_id' => $data->status_id,
            'color' => $data->color,
            'email' => $data->email,
        ];
        $user = new User($user_data);
        $user->id = $data->id;
        $user->role_id = $data->role_id;
        $user->status_id = $data->status_id;
        $user->role = Role::find(['id' => $user->role_id])[0];
        $user->status = Status::find(['id' => $user->status_id])[0];
        return response()->json(['user' => $user]);
    }
    /**
     * Registrar user
     */
    public function register_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'short_address' => 'required|string|max:255',
            'document' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 400);
        }
        try {
            //code...
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
            $role = Role::firstOrNew(['description' => 'Usuario']);
            $role->save();
            // Se asocia el rol al usuario
            $user->role()->associate($role);

            // Se guarda el usuario
            $user->save();

            // Token de auth
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['data' => $user, 'token' => $token, 'token_type' => 'Bearer', 'status' => true], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => false, 'errors' => ['No se logro crear el usuario']], 400);
        }
    }
    public function register_client(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'short_address' => 'required|string|max:255',
            'document' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 400);
        }
        try {
            //code...
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
            $role = Role::firstOrNew(['description' => 'Cliente']);
            $role->save();
            // Se asocia el rol al usuario
            $user->role()->associate($role);

            // Se guarda el usuario
            $user->save();

            // Token de auth
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['data' => $user, 'token' => $token, 'token_type' => 'Bearer', 'status' => true], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => false, 'errors' => ['No se logro crear el usuario']], 400);
        }
    }

    /**
     * Registrar maestro
     */
    public function register_master_no_auth_required(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'short_address' => 'required|string|max:255',
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
                'short_address' => $request->short_address,
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
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => false, 'errors' => ['No se logro crear el master', $th->getMessage()]], 400);
        }
    }
    public function register_master(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'short_address' => 'required|string|max:255',
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
                'short_address' => $request->short_address,
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
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => false, 'errors' => ['No se logro crear el master']], 400);
        }
    }

    /**
     * Login de usuario
     */
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }
        $user = User::with('status', 'role')->where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        $user->token = $token;
        return response()->json([
            'status' => true,
            'message' => 'Bienvenido ' . $user->name,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    /**
     * Cerrar sesion
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return [
            'status' => true,
            'message' => 'Has cerrado sesion exitosamente'
        ];
    }

    public function edit_user_data(Request $request, User $user)
    {

        if ($request->password != $request->confirmarPassword) {
            return response()->json(['status' => false, 'errors' => 'Las contraseñas no coinciden'], 400);
        }

        $validator = Validator::make($request->all(), [
            'phone' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'string|min:8',
        ]);

        if (!$validator->fails()) {
            return response()->json(['status' => false, 'errors' => [$validator->errors(), 'dsadsadsa00']], 400);
        }

        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['status' => true, 'message' => 'Se ha editado el usuario', 'user' => $user], 200);
    }

    public function change_theme_color(Request $request, User $user)
    {
        if (!$request->color) {
            return response()->json(['status' => false, 'message' => 'El color es obligatorio'], 400);
        }
        $user->color = $request->color;
        $user->save();

        return response()->json(['status' => true, 'message' => 'Se ha cambiado el color'], 200);
    }
    public function get_role(Request $request)
    {
        $user_data = [];
        $data = $request->user();
        $user = User::with('status', 'role')->where(['id' => $data->id]);
        return response()->json(['user' => $user]);
    }
}
