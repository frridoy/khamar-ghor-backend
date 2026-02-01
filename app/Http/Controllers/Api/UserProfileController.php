<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserProfile;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\UserProfileLog;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function getProfile(Request $request, $user_id)
    {
        if (!$this->isAuthorized($request->user(), $user_id)) {
            return response()->json(['status' => false, 'message' => 'Unauthorized access'], 403);
        }

        $user = User::find($user_id);
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found'], 404);
        }

        $profile = UserProfile::with([
            'country:id,name_en,name_bn,dial_code',
            'division:id,name_en,name_bn',
            'district:id,name_en,name_bn',
            'thana:id,name_en,name_bn'
        ])->where('user_id', $user_id)->first();

        $data = $profile ? $profile->makeHidden(['created_at', 'updated_at'])->toArray() : $this->getEmptyProfileData($user_id);

        $data['user'] = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'is_active' => $user->is_active,
            'is_profile_completed' => $user->is_profile_completed,
        ];

        return response()->json(['status' => true, 'data' => $data]);
    }

    private function isAuthorized($authUser, $targetUserId)
    {
        return $authUser->id == $targetUserId || $authUser->user_role === 0;
    }

    private function getEmptyProfileData($user_id)
    {
        return [
            'id' => null,
            'user_id' => $user_id,
            'profile_image' => null,
            'nid_front_image' => null,
            'nid_back_image' => null,
            'father_name' => null,
            'mother_name' => null,
            'spouse_name' => null,
            'nid_number' => null,
            'secondary_phone' => null,
            'dob' => null,
            'sex' => null,
            'education_qualification' => null,
            'country_id' => null,
            'division_id' => null,
            'district_id' => null,
            'thana_id' => null,
            'area_name' => null,
            'postal_code' => null,
            'country' => null,
            'division' => null,
            'district' => null,
            'thana' => null,
        ];
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nid_front_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nid_back_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'secondary_phone' => 'nullable|string',
            'father_name' => 'nullable|string',
            'mother_name' => 'nullable|string',
            'spouse_name' => 'nullable|string',
            'country_id' => 'nullable|exists:countries,id',
            'division_id' => 'nullable|exists:divisions,id',
            'district_id' => 'nullable|exists:districts,id',
            'thana_id' => 'nullable|exists:thanas,id',
            'area_name' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'nid_number' => 'nullable|string|unique:user_profiles,nid_number,' . $request->user()->id . ',user_id',
            'dob' => 'nullable|date',
            'sex' => 'nullable|string',
            'education_qualification' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $input = $request->except(['profile_image', 'nid_front_image', 'nid_back_image']);
        $input['user_id'] = $user->id;

        if ($request->hasFile('profile_image')) {
            $input['profile_image'] = $this->uploadFile($request->file('profile_image'), 'profile_images', $user->id, 'profile_image');
        }
        
        if ($request->hasFile('nid_front_image')) {
            $input['nid_front_image'] = $this->uploadFile($request->file('nid_front_image'), 'nid_images', $user->id, 'nid_front_image');
        }

        if ($request->hasFile('nid_back_image')) {
            $input['nid_back_image'] = $this->uploadFile($request->file('nid_back_image'), 'nid_images', $user->id, 'nid_back_image');
        }

        $userProfile = UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            $input
        );

        $user->update(['is_profile_completed' => true]);

        return response()->json([
            'status' => true,
            'message' => 'User profile saved successfully',
            'data' => $userProfile
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nid_front_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nid_back_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'secondary_phone' => 'nullable|string',
            'father_name' => 'nullable|string',
            'mother_name' => 'nullable|string',
            'spouse_name' => 'nullable|string',
            'country_id' => 'nullable|exists:countries,id',
            'division_id' => 'nullable|exists:divisions,id',
            'district_id' => 'nullable|exists:districts,id',
            'thana_id' => 'nullable|exists:thanas,id',
            'area_name' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'nid_number' => 'nullable|string',
            'dob' => 'nullable|date',
            'sex' => 'nullable|string',
            'education_qualification' => 'nullable|string',
            'password' => 'nullable|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $currentProfile = UserProfile::firstOrNew(['user_id' => $user->id]);
        $input = $request->except(['profile_image', 'nid_front_image', 'nid_back_image', 'password', 'password_confirmation']);
        $input['user_id'] = $user->id;

        if ($request->filled('password')) {
            $hashedPassword = Hash::make($request->password);
            
            if (!Hash::check($request->password, $user->password)) {
                $user->password = $hashedPassword;
                $user->save();
            }
        }

        $fileFields = ['profile_image', 'nid_front_image', 'nid_back_image'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $oldPath = $currentProfile->$field;
                
                $newPath = $this->uploadFile($request->file($field), str_replace('_', 's', $field .'s'), $user->id, $field);
                $input[$field] = $newPath;

                if ($oldPath && !empty($oldPath) && Storage::exists('public/' . $oldPath)) {
                   Storage::delete('public/' . $oldPath);
                }

                UserProfileLog::create([
                    'user_id' => $user->id,
                    'column_name' => $field,
                    'old_value' => $oldPath,
                    'new_value' => $newPath,
                ]);
            }
        }

        foreach ($input as $key => $value) {
            if ($key === 'user_id') continue;
            
            $oldValue = $currentProfile->$key;
            if ((string)$oldValue !== (string)$value) {
                 UserProfileLog::create([
                    'user_id' => $user->id,
                    'column_name' => $key,
                    'old_value' => $oldValue,
                    'new_value' => $value,
                ]);
            }
        }

        $currentProfile->fill($input);
        $currentProfile->save();

        $user->update(['is_profile_completed' => true]);

        return response()->json([
            'status' => true,
            'message' => 'User profile updated successfully',
            'data' => $currentProfile
        ]);
    }

    private function uploadFile($file, $folder, $userId, $fileType)
    {
        $filename = $userId . '_' . $fileType . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/' . $folder, $filename);
        return $folder . '/' . $filename; 
    }
}
