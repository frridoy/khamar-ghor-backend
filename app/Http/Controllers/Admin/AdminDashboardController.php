<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $recentUsers = User::orderBy('id', 'desc')->take(5)->get();
        
        return view('admin.dashboard', compact('totalUsers', 'recentUsers'));
    }
}
