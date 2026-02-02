<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class AdminSystemSettingController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::all();
        return view('admin.settings.index', compact('settings'));
    }

    public function show($id)
    {
        $setting = SystemSetting::findOrFail($id);
        return view('admin.settings.show', compact('setting'));
    }

    public function create()
    {
        $setting = new SystemSetting();
        return view('admin.settings.show', compact('setting'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'free_credits_on_signup' => 'required|integer|min:0',
            'free_post_views' => 'required|integer|min:0',
            'default_post_credit_cost' => 'required|numeric|min:0',
            'credit_price' => 'required|numeric|min:0',
        ]);

        SystemSetting::create([
            'free_credits_on_signup' => $request->free_credits_on_signup,
            'free_post_views' => $request->free_post_views,
            'default_post_credit_cost' => $request->default_post_credit_cost,
            'credit_price' => $request->credit_price,
        ]);

        return redirect()->route('admin.settings.index')->with('success', 'System settings created successfully.');
    }

    public function update(Request $request, $id)
    {
        $setting = SystemSetting::findOrFail($id);

        $request->validate([
            'free_credits_on_signup' => 'required|integer|min:0',
            'free_post_views' => 'required|integer|min:0',
            'default_post_credit_cost' => 'required|numeric|min:0',
            'credit_price' => 'required|numeric|min:0',
        ]);

        $setting->update([
            'free_credits_on_signup' => $request->free_credits_on_signup,
            'free_post_views' => $request->free_post_views,
            'default_post_credit_cost' => $request->default_post_credit_cost,
            'credit_price' => $request->credit_price,
        ]);

        return redirect()->route('admin.settings.index')->with('success', 'System settings updated successfully.');
    }
}
