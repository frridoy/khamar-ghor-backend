@extends('admin.layouts.app')

@section('title', 'Attribute Management')
@section('page-title', isset($category) ? 'Attributes: ' . $category->name : 'Global Attributes')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="font-size: 1.25rem; font-weight: 700;">
            {{ isset($category) ? 'Attributes for ' . $category->name : 'Attributes Database' }}
        </h2>
        <div style="display: flex; gap: 1rem;">
            @if(isset($category))
            <a href="{{ route('admin.categories.attributes.manage', $category->id) }}" class="btn btn-primary">
                <i class="fas fa-sync"></i> Bulk Manage Schema
            </a>
            @endif
        </div>
    </div>

    <!-- Filters Section (Consistent with Customer Design) -->
    <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.75rem; margin-bottom: 2rem; border: 1px solid var(--border-color);">
        <form action="{{ isset($category) ? route('admin.categories.attributes.index', $category->id) : route('admin.categories.attributes.global') }}" method="GET" id="filter-form" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.75rem;">Search Attribute</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Search by name or slug..." style="padding: 0.5rem 0.75rem;">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.75rem;">Category</label>
                <select name="category_id" class="form-control" style="padding: 0.5rem 0.75rem;" onchange="this.value ? (window.location.href = '/admin/categories/' + this.value + '/attributes') : (window.location.href = '{{ route('admin.categories.attributes.global') }}')">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
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
                <a href="{{ isset($category) ? route('admin.categories.attributes.index', $category->id) : route('admin.categories.attributes.global') }}" class="btn" style="background: white; border: 1px solid #e2e8f0; color: var(--text-secondary); padding: 0.5rem 1rem;">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Attribute Name</th>
                    <th>Category</th>
                    <th>Data Type</th>
                    <th>Unit</th>
                    <th>Required</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attributes as $attr)
                <tr>
                    <td><code>#{{ $attr->display_order }}</code></td>
                    <td>
                        <div style="font-weight: 700; color: var(--text-primary); font-size: 0.9375rem;">{{ $attr->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">Slug: {{ $attr->slug }}</div>
                    </td>
                    <td>
                        @if($attr->category)
                            <span class="badge" style="background: #f1f5f9; color: var(--text-primary); border: 1px solid var(--border-color);">
                                <i class="fas fa-folder-open" style="font-size: 0.75rem; margin-right: 0.3rem; opacity: 0.5;"></i>
                                {{ $attr->category->name }}
                            </span>
                        @else
                            <span style="color: var(--text-secondary); font-style: italic;">No Category</span>
                        @endif
                    </td>
                    <td><span class="badge" style="background: #eef2ff; color: var(--primary-color); font-weight: 700;">{{ strtoupper($attr->data_type) }}</span></td>
                    <td>{{ $attr->unit ?? '-' }}</td>
                    <td>
                        @if($attr->is_required)
                            <span style="color: var(--danger-color); font-weight: 800; font-size: 0.75rem;">YES</span>
                        @else
                            <span style="color: var(--text-secondary); font-size: 0.75rem;">NO</span>
                        @endif
                    </td>
                    <td>
                        @if($attr->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.categories.attributes.edit', [$attr->category_id, $attr->id]) }}" class="btn btn-sm" style="background: #f8fafc; color: var(--primary-color); border: 1px solid #e2e8f0;">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.categories.attributes.delete', [$attr->category_id, $attr->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this attribute?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="background: #fff1f2; color: var(--danger-color); border: 1px solid #fecaca;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 4rem; color: var(--text-secondary);">
                        <i class="fas fa-tags" style="font-size: 2.5rem; display: block; margin-bottom: 1rem; opacity: 0.2;"></i>
                        No attributes found matching your criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 0.875rem; color: var(--text-secondary);">
            Showing {{ $attributes->firstItem() ?? 0 }} to {{ $attributes->lastItem() ?? 0 }} of {{ $attributes->total() }} results
        </div>
        <div>
            {{ $attributes->links() }}
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
