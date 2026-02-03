<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SystemSetting;
use App\Models\UserCredit;
use App\Models\CreditTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $role = $request->input('role');

        $query = User::with('store:id,name,user_id')->orderBy('id', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($role !== null && $role !== '') {
            $query->where('user_role', $role);
        }

        $users = $query->paginate($perPage)->withQueryString();

        return view('admin.customers.index', compact('users', 'perPage', 'search', 'role'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|unique:users',
            'password' => 'required|string|min:4',
            'user_role' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                'user_role' => $request->user_role,
                'is_active' => 1,
            ]);

            $systemSetting = SystemSetting::latest()->first();
            $freeCredits = ($systemSetting && $systemSetting->free_credits_on_signup)
                ? $systemSetting->free_credits_on_signup
                : 0;

            UserCredit::create([
                'user_id' => $user->id,
                'total_earned' => $freeCredits,
                'total_spent' => 0,
                'balance_credit' => $freeCredits,
            ]);

            CreditTransaction::create([
                'user_id' => $user->id,
                'type' => 'earn',
                'source' => 'signup',
                'reference_id' => null,
                'credits' => $freeCredits,
                'balance_before' => 0,
                'balance_after' => $freeCredits,
            ]);

            DB::commit();
            return redirect()->route('admin.customers.index')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create user. Please try again.');
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.customers.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone_number' => 'required|string|unique:users,phone_number,' . $id,
            'user_role' => 'required|integer',
            'is_active' => 'required|integer|in:1,2,3',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'user_role' => $request->user_role,
            'is_active' => $request->is_active,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.customers.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.customers.index')->with('success', 'User deleted successfully.');
    }

    public function show($id)
    {
        $user = User::with(['userProfile', 'store'])->findOrFail($id);
        return view('admin.customers.view-modal', compact('user'));
    }
}
