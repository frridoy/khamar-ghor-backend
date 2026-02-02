<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AdminCategoryAttributeController extends Controller
{
    public function globalIndex(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $categoryId = $request->input('category_id');

        $query = CategoryAttribute::with('category')->orderBy('id', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $attributes = $query->paginate($perPage)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.categories.attributes.index', compact('attributes', 'perPage', 'search', 'categoryId', 'categories'));
    }

    public function index(Request $request, Category $category)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $query = $category->attributes()->orderBy('id', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $attributes = $query->paginate($perPage)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $categoryId = $category->id;

        return view('admin.categories.attributes.index', compact('category', 'attributes', 'perPage', 'search', 'categoryId', 'categories'));
    }

    public function manage(Category $category)
    {
        $category->load('attributes');
        return view('admin.categories.attributes.manage', compact('category'));
    }

    public function updateBulk(Request $request, $categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $attributesData = $request->input('attributes', []);

        $request->validate([
            'attributes' => 'required|array',
            'attributes.*.name' => 'required|string|max:255',
            'attributes.*.data_type' => 'required|in:string,integer,decimal,boolean,date',
        ]);

        DB::beginTransaction();
        try {
            $submittedIds = [];
            
            foreach ($attributesData as $data) {
                $isRequired = isset($data['is_required']) && ($data['is_required'] === 'on' || $data['is_required'] == 1);
                
                if (isset($data['id']) && !empty($data['id'])) {
                    // Update existing
                    $attribute = $category->attributes()->findOrFail($data['id']);
                    $attribute->update([
                        'name' => $data['name'],
                        'slug' => Str::slug($data['name']),
                        'data_type' => $data['data_type'],
                        'is_required' => $isRequired,
                        'is_nullable' => !$isRequired,
                        'unit' => $data['unit'] ?? null,
                        'display_order' => $data['display_order'] ?? 0,
                    ]);
                    $submittedIds[] = $attribute->id;
                } else {
                    // Create new
                    $newAttr = $category->attributes()->create([
                        'name' => $data['name'],
                        'slug' => Str::slug($data['name']),
                        'data_type' => $data['data_type'],
                        'is_required' => $isRequired,
                        'is_nullable' => !$isRequired,
                        'unit' => $data['unit'] ?? null,
                        'display_order' => $data['display_order'] ?? 0,
                        'is_active' => true,
                    ]);
                    $submittedIds[] = $newAttr->id;
                }
            }

            // Sync: Delete attributes not in submitted list
            $category->attributes()->whereNotIn('id', $submittedIds)->delete();

            DB::commit();
            return redirect()->route('admin.categories.attributes.index', $category->id)
                ->with('success', 'Attributes synchronized successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Critical Error: ' . $e->getMessage()])->withInput();
        }
    }

    public function create(Category $category)
    {
        return view('admin.categories.attributes.create', compact('category'));
    }

    public function store(Request $request, $categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $attributes = $request->input('attributes', []);

        if (empty($attributes)) {
            return back()->withErrors(['error' => 'The attributes array is missing or empty.'])->withInput();
        }

        $request->validate([
            'attributes' => 'required|array|min:1',
            'attributes.*.name' => 'required|string|max:255',
            'attributes.*.data_type' => 'required|in:string,integer,decimal,boolean,date',
        ]);

        DB::beginTransaction();
        try {
            $savedCount = 0;
            foreach ($attributes as $attr) {
                if (empty($attr['name'])) continue;

                $isRequired = isset($attr['is_required']) && ($attr['is_required'] === 'on' || $attr['is_required'] == 1);
                
                $category->attributes()->create([
                    'name' => $attr['name'],
                    'slug' => Str::slug($attr['name']),
                    'data_type' => $attr['data_type'],
                    'is_required' => $isRequired,
                    'is_nullable' => !$isRequired,
                    'unit' => $attr['unit'] ?? null,
                    'display_order' => $attr['display_order'] ?? 0,
                    'is_active' => true,
                ]);
                $savedCount++;
            }

            if ($savedCount === 0) {
                DB::rollBack();
                return back()->withErrors(['error' => 'No valid attributes were found in your submission.'])->withInput();
            }

            DB::commit();
            return redirect()->route('admin.categories.attributes.index', $category->id)
                ->with('success', $savedCount . ' attributes added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Submission Error: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit(Category $category, CategoryAttribute $attribute)
    {
        return view('admin.categories.attributes.edit', compact('category', 'attribute'));
    }

    public function update(Request $request, Category $category, CategoryAttribute $attribute)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'data_type' => 'required|in:string,integer,decimal,boolean,date',
        ]);

        $isRequired = $request->has('is_required');
        $attribute->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'data_type' => $request->data_type,
            'is_required' => $isRequired,
            'is_nullable' => !$isRequired,
            'unit' => $request->unit,
            'display_order' => $request->display_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.categories.attributes.index', $category->id)
            ->with('success', 'Attribute updated successfully.');
    }

    public function destroy(Category $category, CategoryAttribute $attribute)
    {
        $attribute->delete();
        return redirect()->route('admin.categories.attributes.index', $category->id)
            ->with('success', 'Attribute deleted successfully.');
    }
}
