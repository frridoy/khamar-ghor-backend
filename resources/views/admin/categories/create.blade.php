@extends('admin.layouts.app')

@section('title', 'Add Category')
@section('page-title', 'Create Category')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    <div class="card">
        <div class="card-header">
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">Category Information</h2>
                <p style="color: var(--text-secondary); font-size: 0.875rem;">Define a new product category and upload a representative image.</p>
            </div>
            <i class="fas fa-folder-plus" style="font-size: 1.5rem; color: var(--primary-color); opacity: 0.5;"></i>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Left Column -->
                <div>
                    <div class="form-group">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Cow, Bike, Laptop">
                    </div>

                    <div class="form-group">
                        <label for="is_active" class="form-label">Category Status</label>
                        <select name="is_active" id="is_active" class="form-control">
                            <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.5rem;">Inactive categories will be hidden from public view.</p>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <div class="form-group">
                        <label for="image" class="form-label">Category Image</label>
                        <input type="file" name="image" id="image" class="form-control">
                        <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.5rem;">Recommended size: 512x512px. Formats: JPG, PNG, GIF.</p>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
                <a href="{{ route('admin.categories.index') }}" class="btn" style="background: #f1f5f9; color: var(--text-secondary);">
                    Cancel & Return
                </a>
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.4);">
                    <i class="fas fa-check" style="margin-right: 0.5rem;"></i> Create Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
