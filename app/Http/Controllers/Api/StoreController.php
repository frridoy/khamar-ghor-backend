<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'country_id' => 'nullable|exists:countries,id',
            'division_id' => 'nullable|exists:divisions,id',
            'district_id' => 'nullable|exists:districts,id',
            'thana_id' => 'nullable|exists:thanas,id',
            'area_name' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'address' => 'nullable|string',
            'trade_license_number' => 'nullable|string',
            'established_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        
        $slug = Str::slug($request->name);
        $count = Store::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $input = $request->except(['logo', 'cover_image']);
        $input['user_id'] = $user->id;
        $input['slug'] = $slug;

        $currentStore = Store::where('user_id', $user->id)->first();

        if ($request->hasFile('logo')) {
            if ($currentStore && $currentStore->logo) {
                Storage::delete('public/' . $currentStore->logo);
            }
            $input['logo'] = $this->uploadFile($request->file('logo'), 'store/logos', $user->id, 'logo');
        }

        if ($request->hasFile('cover_image')) {
            if ($currentStore && $currentStore->cover_image) {
                Storage::delete('public/' . $currentStore->cover_image);
            }
            $input['cover_image'] = $this->uploadFile($request->file('cover_image'), 'store/covers', $user->id, 'cover');
        }

        $store = Store::updateOrCreate(
            ['user_id' => $user->id],
            $input
        );

        return response()->json([
            'status' => true,
            'message' => 'Store information saved successfully',
            'data' => $store
        ]);
    }

    public function show(Request $request, $user_id)
    {
        $authUser = $request->user();

        if ($authUser->id != $user_id && $authUser->user_role !== 0) {
            return response()->json(['status' => false, 'message' => 'Unauthorized access'], 403);
        }

        $store = Store::with([
            'country:id,name_en,name_bn',
            'division:id,name_en,name_bn',
            'district:id,name_en,name_bn',
            'thana:id,name_en,name_bn',
            'user:id,name,email,phone_number'
        ])->where('user_id', $user_id)->first();

        if (!$store) {
            return response()->json(['status' => false, 'message' => 'Store not found'], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $store
        ]);
    }

    private function uploadFile($file, $folder, $userId, $type)
    {
        $filename = $userId . '_' . $type . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/' . $folder, $filename);
        return $folder . '/' . $filename;
    }
}
