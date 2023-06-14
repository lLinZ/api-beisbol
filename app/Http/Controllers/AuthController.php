<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
    public function register(Request $request)
    {

        if ($request->has('image')) {
            $image = $request->image;
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('users');
            $image->move($path, $image_name);
        } else {
            return response()->json(['message' => 'La imagen es obligatoria'], 400);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:255|unique:users',
            'document' => 'required|string|max:255|unique:users',
            'short_address' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'photo' => "users/$image_name",
            'document' => $request->document,
            'short_address' => $request->short_address,
            'role_id' => 3,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken("auth_token")->plainTextToken;
        return response()->json(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer',]);
    }
    public function register_player(Request $request)
    {
        if ($request->has('image')) {
            $image = $request->image;
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('players');
            $image->move($path, $image_name);
        } else {
            return response()->json(['message' => 'La imagen es obligatoria'], 400);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:255|unique:users',
            'document' => 'required|string|max:255|unique:users',
            'short_address' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 400);
        }
        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'photo' => $image_name,
            'document' => $request->document,
            'short_address' => $request->short_address,
            'role_id' => 2,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken("auth_token")->plainTextToken;
        return response()->json(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer',]);
    }
    public function register_admin(Request $request)
    {
        if ($request->has('image')) {
            $image = $request->image;
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('users');
            $image->move($path, $image_name);
        } else {
            return response()->json(['message' => 'La imagen es obligatoria'], 400);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:255|unique:users',
            'document' => 'required|string|max:255|unique:users',
            'short_address' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 400);
        }
        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'photo' => $image_name,
            'document' => $request->document,
            'short_address' => $request->short_address,
            'role_id' => 1,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken("auth_token")->plainTextToken;
        return response()->json(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer',]);
    }
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Bienvenido ' . $user->name,
            'accessToken' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }
    public function getRole(User $user){
        return response()->json(["users"=>$user->roleById()], 200);
    }
    public function logout(User $user)
    {
        $user->tokens()->delete();
        return [
            'message' => 'Has cerrado sesion exitosamente'
        ];
    }
}
