@extends('admin.layouts.app')

@section('title', 'Edit Category')
@section('page-title', 'Edit Category: ' . $category->name)

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    <div class="card">
        <div class="card-header">
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">Update Information</h2>
                <p style="color: var(--text-secondary); font-size: 0.875rem;">Modify details and status for category <strong>{{ $category->name }}</strong>.</p>
            </div>
            <i class="fas fa-edit" style="font-size: 1.5rem; color: var(--primary-color); opacity: 0.5;"></i>
        </div>

        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Left Column -->
                <div>
                    <div class="form-group">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name) }}" required placeholder="e.g. Cow, Bike, Laptop">
                    </div>

                    <div class="form-group">
                        <label for="is_active" class="form-label">Category Status</label>
                        <select name="is_active" id="is_active" class="form-control">
                            <option value="1" {{ old('is_active', $category->is_active) == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $category->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.5rem;">Update the visibility of this category.</p>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <div class="form-group">
                        <label for="image" class="form-label">Category Image</label>
                        @if($category->image)
                        <div style="margin-bottom: 1.25rem; position: relative; width: 120px; height: 120px;">
                            <img src="{{ asset('storage/' . $category->image) }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 0.75rem; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
                            <div style="position: absolute; top: -8px; right: -8px; background: var(--primary-color); color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem;">
                                <i class="fas fa-image"></i>
                            </div>
                        </div>
                        @endif
                        <input type="file" name="image" id="image" class="form-control">
                        <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.5rem;">Upload a new image to replace the current one.</p>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
                <a href="{{ route('admin.categories.index') }}" class="btn" style="background: #f1f5f9; color: var(--text-secondary);">
                    Cancel & Return
                </a>
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.4);">
                    <i class="fas fa-save" style="margin-right: 0.5rem;"></i> Update Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
