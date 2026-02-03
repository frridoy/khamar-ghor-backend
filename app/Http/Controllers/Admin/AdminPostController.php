<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryAttribute;
use App\Models\Post;
use App\Models\PostAttributeValue;
use App\Models\PostMedia;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminPostController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        $query = Post::with(['user', 'store', 'category'])->orderBy('id', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($minPrice) {
            $query->where(function ($q) use ($minPrice) {
                $q->where('discount_price', '>=', $minPrice)
                    ->orWhere(function ($q2) use ($minPrice) {
                        $q2->whereNull('discount_price')
                            ->where('original_price', '>=', $minPrice);
                    });
            });
        }

        if ($maxPrice) {
            $query->where(function ($q) use ($maxPrice) {
                $q->where('discount_price', '<=', $maxPrice)
                    ->orWhere(function ($q2) use ($maxPrice) {
                        $q2->whereNull('discount_price')
                            ->where('original_price', '<=', $maxPrice);
                    });
            });
        }

        $posts = $query->paginate($perPage)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.posts.index', compact('posts', 'perPage', 'search', 'categoryId', 'minPrice', 'maxPrice', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $users = User::where('user_role', 1)->get();
        return view('admin.posts.create', compact('categories', 'users'));
    }

    public function getStores($userId)
    {
        $stores = Store::where('user_id', $userId)->get(['id', 'name']);
        return response()->json($stores);
    }

    public function getAttributes($categoryId)
    {
        $attributes = CategoryAttribute::where('category_id', $categoryId)
            ->where('is_active', true)
            ->orderBy('display_order')
            ->get();
        return response()->json($attributes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'store_id' => 'required|exists:stores,id',
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'original_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'credit_cost' => 'nullable|numeric|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:20480',
        ]);

        DB::beginTransaction();
        try {
            $post = Post::create([
                'user_id' => $request->user_id,
                'store_id' => $request->store_id,
                'category_id' => $request->category_id,
                'title' => $request->title,
                'description' => $request->description,
                'original_price' => $request->original_price,
                'discount_price' => $request->discount_price,
                'credit_cost' => $request->credit_cost,
                'status' => 'active',
            ]);

            $attributesData = $request->input('attributes', []);

            if (!empty($attributesData) && is_array($attributesData)) {
                foreach ($attributesData as $attributeId => $value) {
                    if ($value !== null && $value !== '') {
                        PostAttributeValue::create([
                            'post_id' => $post->id,
                            'attribute_id' => $attributeId,
                            'value' => is_array($value) ? json_encode($value) : $value,
                        ]);
                    }
                }
            }

            if ($request->hasFile('images')) {
                $images = $request->file('images');
                $imageCount = 1;
                foreach (array_slice($images, 0, 3) as $index => $image) {
                    if ($image) {
                        $extension = $image->getClientOriginalExtension();
                        $fileName = 'post_image' . $imageCount . '_' . rand(100000, 999999) . '.' . $extension;
                        $path = $image->storeAs('posts/images', $fileName, 'public');

                        PostMedia::create([
                            'post_id' => $post->id,
                            'file_path' => $path,
                            'type' => 'image',
                            'position' => $index,
                            'is_primary' => $index === 0,
                        ]);
                        $imageCount++;
                    }
                }
            }

            if ($request->hasFile('video')) {
                $video = $request->file('video');
                $extension = $video->getClientOriginalExtension();
                $fileName = 'post_video_' . rand(100000, 999999) . '.' . $extension;
                $path = $video->storeAs('posts/videos', $fileName, 'public');

                PostMedia::create([
                    'post_id' => $post->id,
                    'file_path' => $path,
                    'type' => 'video',
                    'position' => 0,
                    'is_primary' => false,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.posts.index')->with('success', 'Post created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Critical Error: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $post = Post::with([
            'attributeValues',
            'media'
        ])->findOrFail($id);

        $users = User::all();
        $stores = Store::where('user_id', $post->user_id)->get();
        $categories = Category::all();

        $attributeValues = $post->attributeValues
            ->pluck('value', 'attribute_id')
            ->toArray();

        return view('admin.posts.edit', compact(
            'post',
            'users',
            'stores',
            'categories',
            'attributeValues'
        ));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'store_id' => 'required|exists:stores,id',
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'original_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'credit_cost' => 'nullable|numeric|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:20480',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Update core post
            $post->update($request->only([
                'user_id',
                'store_id',
                'category_id',
                'title',
                'description',
                'original_price',
                'discount_price',
                'credit_cost',
            ]));

            /** 🔹 Attribute Sync */
            $attributes = $request->input('attributes', []);

            // Delete old values first (safe + clean)
            PostAttributeValue::where('post_id', $post->id)->delete();

            foreach ($attributes as $attributeId => $value) {
                if ($value !== null && $value !== '') {
                    PostAttributeValue::create([
                        'post_id' => $post->id,
                        'attribute_id' => $attributeId,
                        'value' => is_array($value) ? json_encode($value) : $value,
                    ]);
                }
            }

            /** 🔹 Images (append new, don’t delete old automatically) */
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    if ($image) {
                        $path = $image->store('posts/images', 'public');

                        PostMedia::create([
                            'post_id' => $post->id,
                            'file_path' => $path,
                            'type' => 'image',
                            'position' => $index,
                            'is_primary' => false,
                        ]);
                    }
                }
            }

            /** 🔹 Video replace (optional) */
            if ($request->hasFile('video')) {
                PostMedia::where('post_id', $post->id)
                    ->where('type', 'video')
                    ->delete();

                $path = $request->file('video')->store('posts/videos', 'public');

                PostMedia::create([
                    'post_id' => $post->id,
                    'file_path' => $path,
                    'type' => 'video',
                    'position' => 0,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.posts.index')
                ->with('success', 'Post updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Post $post)
    {
        foreach ($post->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully.');
    }

    public function show(Post $post)
    {
        $post->load(['user', 'store', 'category', 'attributeValues.attribute', 'media']);
        return view('admin.posts.view-modal', compact('post'));
    }

    public function media(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $query = Post::with(['user', 'store', 'media', 'category'])->orderBy('id', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($qu) use ($search) {
                        $qu->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('store', function ($qs) use ($search) {
                        $qs->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $posts = $query->paginate($perPage)->withQueryString();

        return view('admin.posts.media', compact('posts', 'perPage', 'search'));
    }
}
