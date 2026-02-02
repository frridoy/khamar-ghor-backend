@extends('admin.layouts.app')

@section('title', 'Category Management')
@section('page-title', 'Product Categories')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="font-size: 1.25rem; font-weight: 700;">Categories Database</h2>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Category
        </a>
    </div>

    <!-- Filters Section -->
    <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.75rem; margin-bottom: 2rem; border: 1px solid var(--border-color);">
        <form action="{{ route('admin.categories.index') }}" method="GET" id="filter-form" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.75rem;">Search Category</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Search by name or slug..." style="padding: 0.5rem 0.75rem;">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.75rem;">Show Rows</label>
                <select name="per_page" class="form-control" style="padding: 0.5rem 0.75rem;" onchange="this.form.submit()">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 Rows</option>
                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20 Rows</option>
                    <option value="30" {{ $perPage == 30 ? 'selected' : '' }}>30 Rows</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 Rows</option>
                </select>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem;">
                    <i class="fas fa-filter"></i> Search
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn" style="background: white; border: 1px solid #e2e8f0; color: var(--text-secondary); padding: 0.5rem 1rem;">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Category Name</th>
                    <th>Attributes</th>
                    <th>Status</th>
                    <th>Last Update</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>
                        @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" style="width: 45px; height: 45px; border-radius: 0.75rem; object-fit: cover; border: 1px solid var(--border-color);">
                        @else
                        <div style="width: 45px; height: 45px; background: #f1f5f9; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; color: #94a3b8; border: 1px dashed #cbd5e1;">
                            <i class="fas fa-image"></i>
                        </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight: 700; color: var(--text-primary); font-size: 1rem;">{{ $category->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">SLUG: /{{ $category->slug }}</div>
                    </td>
                    <td>
                        <a href="{{ route('admin.categories.attributes.index', $category->id) }}" class="badge" style="background: #e0f2fe; color: #0369a1; text-decoration: none; font-weight: 700;">
                            <i class="fas fa-list-ul" style="margin-right: 0.3rem; opacity: 0.7;"></i>
                            {{ $category->attributes_count }} Attributes
                        </a>
                    </td>
                    <td>
                        @if($category->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                        
                        @if($category->is_updated)
                            <span class="badge" style="background: #fef3c7; color: #92400e; font-size: 0.7rem;">Changed</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-size: 0.875rem; color: var(--text-primary);">{{ $category->updated_at->format('d M Y') }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $category->updated_at->diffForHumans() }}</div>
                    </td>
                    <td style="text-align: right;">
                        <button onclick="openModal('{{ route('admin.categories.show', $category->id) }}')" class="btn btn-sm" style="background: #f8fafc; color: var(--text-primary); border: 1px solid #e2e8f0;" title="Quick View">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="{{ route('admin.categories.attributes.index', $category->id) }}" class="btn btn-sm" style="background: #f8fafc; color: var(--text-primary); border: 1px solid #e2e8f0;" title="Data Schema">
                            <i class="fas fa-cog"></i>
                        </a>
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm" style="background: #f8fafc; color: var(--primary-color); border: 1px solid #e2e8f0;" title="Edit Module">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Deleting category will permanently remove all attributes. Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="background: #fff1f2; color: var(--danger-color); border: 1px solid #fecaca;" title="Trash">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 4rem; color: var(--text-secondary);">
                        <i class="fas fa-tags" style="font-size: 2.5rem; display: block; margin-bottom: 1rem; opacity: 0.2;"></i>
                        No categories found. Click "New Category" to get started.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 0.875rem; color: var(--text-secondary);">
            Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }} results
        </div>
        <div>
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .pagination { display: flex; list-style: none; gap: 0.25rem; }
    .page-item {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 32px; height: 32px; padding: 0 0.5rem; border: 1px solid #e2e8f0;
        border-radius: 0.375rem; text-decoration: none; color: var(--text-secondary);
        font-size: 0.875rem; transition: all 0.2s;
    }
    .page-item:hover:not(.disabled) { background-color: #f1f5f9; border-color: #cbd5e1; }
    .page-item.active { background-color: var(--primary-color); color: white; border-color: var(--primary-color); }
    .page-item.disabled { opacity: 0.5; cursor: not-allowed; }
</style>
@endsection
