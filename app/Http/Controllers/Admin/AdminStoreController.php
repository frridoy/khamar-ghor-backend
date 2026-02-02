<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;

class AdminStoreController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $userId = $request->input('user_id');
        $isVerified = $request->input('is_verified');

        $query = Store::with('user:id,name,code,email,phone_number')->orderBy('id', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('store_type', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($isVerified !== null && $isVerified !== '') {
            $query->where('is_verified', $isVerified);
        }

        $stores = $query->paginate($perPage)->withQueryString();
        $users = User::where('user_role', 1)->orderBy('name')->get(['id', 'name', 'code']);

        return view('admin.stores.index', compact('stores', 'perPage', 'search', 'userId', 'isVerified', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'country_id' => 'nullable|exists:countries,id',
            'division_id' => 'nullable|exists:divisions,id',
            'district_id' => 'nullable|exists:districts,id',
            'thana_id' => 'nullable|exists:thanas,id',
            'area_name' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'trade_license_number' => 'nullable|string|max:255',
            'is_active' => 'nullable|in:1,2,3',
            'established_date' => 'nullable|date',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $slug = \Illuminate\Support\Str::slug($request->name);
        $userId = $request->user_id;

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $randomSixDigits = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $extension = $logo->getClientOriginalExtension();
            $fileName = 'store_logo_' . $userId . '_' . $randomSixDigits . '.' . $extension;
            $logoPath = $logo->storeAs('stores/logos', $fileName, 'public');
        }

        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image');
            $randomSixDigits = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $extension = $coverImage->getClientOriginalExtension();
            $fileName = 'store_cover_image_' . $userId . '_' . $randomSixDigits . '.' . $extension;
            $coverImagePath = $coverImage->storeAs('stores/cover_images', $fileName, 'public');
        }

        Store::create([
            'user_id' => $userId,
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'phone' => $request->phone,
            'email' => $request->email,
            'website' => $request->website,
            'logo' => $logoPath,
            'cover_image' => $coverImagePath,
            'country_id' => $request->country_id,
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'thana_id' => $request->thana_id,
            'area_name' => $request->area_name,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'trade_license_number' => $request->trade_license_number,
            'is_verified' => 0,
            'is_active' => $request->is_active ?? 1,
            'established_date' => $request->established_date,
        ]);

        return redirect()->route('admin.stores.index')->with('success', 'Store created successfully.');
    }


    public function show($id)
    {
        $store = Store::with(['user', 'country', 'division', 'district', 'thana'])->findOrFail($id);
        return view('admin.stores.view-modal', compact('store'));
    }

    public function verify(Request $request, $id)
    {
        $store = Store::findOrFail($id);

        $request->validate([
            'is_verified' => 'required|in:0,1',
        ]);

        $store->update([
            'is_verified' => (int) $request->is_verified,
        ]);

        return redirect()->route('admin.stores.index')->with('success', 'Store verification status updated successfully.');
    }

    public function create()
    {
        $countries = Country::orderBy('name_en')->get();
        return view('admin.stores.create', compact('countries'));
    }

    public function edit($id)
    {
        $store = Store::findOrFail($id);
        $countries = Country::orderBy('name_en')->get();
        return view('admin.stores.edit', compact('store', 'countries'));
    }

    public function update(Request $request, $id)
    {
        $store = Store::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'country_id' => 'nullable|exists:countries,id',
            'division_id' => 'nullable|exists:divisions,id',
            'district_id' => 'nullable|exists:districts,id',
            'thana_id' => 'nullable|exists:thanas,id',
            'area_name' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'trade_license_number' => 'nullable|string|max:255',
            'is_active' => 'nullable|in:1,2,3',
            'established_date' => 'nullable|date',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $updateData = [
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'description' => $request->description,
            'phone' => $request->phone,
            'email' => $request->email,
            'website' => $request->website,
            'country_id' => $request->country_id,
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'thana_id' => $request->thana_id,
            'area_name' => $request->area_name,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'trade_license_number' => $request->trade_license_number,
            'is_active' => $request->is_active ?? 1,
            'established_date' => $request->established_date,
        ];

        if ($request->hasFile('logo')) {
            if ($store->logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($store->logo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($store->logo);
            }

            $logo = $request->file('logo');
            $randomSixDigits = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $extension = $logo->getClientOriginalExtension();
            $fileName = 'store_logo_' . $store->user_id . '_' . $randomSixDigits . '.' . $extension;
            $updateData['logo'] = $logo->storeAs('stores/logos', $fileName, 'public');
        }

        if ($request->hasFile('cover_image')) {
            if ($store->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($store->cover_image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($store->cover_image);
            }

            $coverImage = $request->file('cover_image');
            $randomSixDigits = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $extension = $coverImage->getClientOriginalExtension();
            $fileName = 'store_cover_image_' . $store->user_id . '_' . $randomSixDigits . '.' . $extension;
            $updateData['cover_image'] = $coverImage->storeAs('stores/cover_images', $fileName, 'public');
        }

        $store->update($updateData);

        return redirect()->route('admin.stores.index')->with('success', 'Store updated successfully.');
    }
}
