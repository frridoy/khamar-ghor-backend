<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function getUserRoles()
    {
        $roles = config('user_roles.roles');
        return response()->json([
            'status' => 'success',
            'data' => $roles
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => [
                'required',
                'string',
                'unique:users',
                'regex:/^(?:\+8801|8801|01)[3-9]\d{8}$/'
            ],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
            'user_role' => [
                'required',
                'integer',
                Rule::in([1, 2])
            ],
        ], [
            'phone_number.regex' => 'The phone number must be a valid Bangladeshi number.',
            'user_role.in' => 'Registration is only allowed for Sellers and Buyers.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $rolePrefix = match ((int)$request->user_role) {
            1 => 'S',
            2 => 'B',
            3 => 'V',
            default => 'U'
        };

        $monthYear = date('mY');
        $prefix = $rolePrefix . $monthYear . '-';

        $lastUser = User::where('code', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastUser) {
            $lastSequence = (int)substr($lastUser->code, -4);
            $newSequence = str_pad($lastSequence + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newSequence = '0001';
        }

        $code = $prefix . $newSequence;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'user_role' => $request->user_role,
            'password' => Hash::make($request->password),
            'code' => $code,
            'is_active' => true,
            'is_profile_completed' => false,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $login = $request->login;

        $user = User::where(function ($query) use ($login) {
            $query->where('email', $login)
                ->orWhere('phone_number', $login);
        })->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Logged in successfully',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ]);
    }
}
