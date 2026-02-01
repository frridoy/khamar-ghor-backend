<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function getAllUsers()
    {
        $users = User::where('user_role', '!=', 0)
            ->select('id', 'code', 'name', 'email', 'phone_number', 'user_role', 'created_at', 'is_active', 'is_profile_completed', 'closing_date')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'code' => $user->code,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number,
                    'is_active' => $user->is_active,
                    'is_profile_completed' => $user->is_profile_completed,
                    'user_role' => $user->user_role,
                    'role_name' => $user->role_name,
                    'joining_date' => $user->created_at->format('Y-m-d'),
                    'closing_date' => $user->closing_date,
                ];
            });

        return response()->json([
            'status' => 'success',
            'message' => 'Users retrieved successfully',
            'data' => $users
        ]);
    }
}
