<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Rules\Password;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|max:255|email|unique:users',
                'phone' => 'nullable|string|max:255',
                'password' => ['required','string',new Password],
            ]);
    
            User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);
            
            $user = User::where('email', $request->email)->first();
    
            $tokenResult = $user->createToken('authToken')->plainTextToken;
    
            return ResponseFormatter::success(
                [
                    'access_token' => $tokenResult,
                    'token_type' => 'Bearer',
                    'user' => $user, 
                ], 'User Registered'
            );
        } catch (Exception $error) {
            return ResponseFormatter::error([
                    'message' => 'Something went wrong',
                    'errors' => $error->getMessage(), 
                ], 'Authentication Failed', 404
            );
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized',
                ], 'Authentication Error', 404);
            }

            $user = User::where('email', $request->email)->first();

            if (!Hash::check($request->password, $user->password, [])) {
                throw new Exception("Invalid Credentialas", 1);
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success(
                [
                    'access_token' => $tokenResult,
                    'token_type' => 'Bearer',
                    'user' => $user, 
                ], 'Authenticated'
            );
        } catch (Exception $error) {
            return ResponseFormatter::error([
                    'message' => 'Something went wrong',
                    'error' => $error,
                ], 'Authentication Failed', 500
            );
        }
    }

    public function fetch()
    {
        return ResponseFormatter::success([
            'user' => Auth::user(),
            'Data user berhasil diambil'
        ]);
    }

    public function updateProfile(Request $request)
    {
        // $request->validate([
        //     'name' => 'string|max:255',
        //     'username' => 'string|max:255|unique:users',
        //     'email' => 'string|max:255|email|unique:users',
        //     'phone' => 'nullable|string|max:255',
        // ]);

        $user = Auth::user();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->phone = $request->phone;
        $user->save();

        return ResponseFormatter::success(
            $user,
            'Profile Updated'
        );
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();

        return ResponseFormatter::success($token, 'Revoked');
    }

    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => ['required','string'],
                'password' => ['required','string'],
                'confirm_password' => ['required','string'],
                'id' => 'required',
            ]);
            
            $user = User::find($request->id);
            $userPassword = $user->password;

            if (!Hash::check($request->current_password, $userPassword)) {
                return ResponseFormatter::error(null, 'Password Tidak Cocok', 404);
            }

            if ($request->password != $request->confirm_password) {
                return ResponseFormatter::error(null, 'Password Tidak Cocok', 404);
            }

            $password =  Hash::make($request->password);

            User::where('id', $user->id)->update(['password' => $password]);

            return ResponseFormatter::success($user, 'Berhasil ganti password');

        } catch (Exception $th) {
            // print($th);
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $th,
            ], 'Authentication Failed', 500
        );
        }


    }
}
