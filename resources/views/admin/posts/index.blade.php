@extends('admin.layouts.app')

@section('title', 'Post Management')
@section('page-title', 'Product Posts')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="font-size: 1.25rem; font-weight: 700;">Product Listings Database</h2>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Post
        </a>
    </div>

    <!-- Filters Section -->
    <div
        style="background: #f8fafc; padding: 1.5rem; border-radius: 0.75rem; margin-bottom: 2rem; border: 1px solid var(--border-color);">
        <form action="{{ route('admin.posts.index') }}" method="GET" id="filter-form"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.75rem;">Search</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control"
                    placeholder="Title or slug..." style="padding: 0.5rem 0.75rem;">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.75rem;">Category</label>
                <select name="category_id" class="form-control" style="padding: 0.5rem 0.75rem;">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $categoryId==$category->id ? 'selected' : '' }}>{{
                        $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.75rem;">Min Price (৳)</label>
                <input type="number" step="0.01" name="min_price" value="{{ $minPrice }}" class="form-control"
                    placeholder="0.00" style="padding: 0.5rem 0.75rem;">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.75rem;">Max Price (৳)</label>
                <input type="number" step="0.01" name="max_price" value="{{ $maxPrice }}" class="form-control"
                    placeholder="999999.99" style="padding: 0.5rem 0.75rem;">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.75rem;">Show Rows</label>
                <select name="per_page" class="form-control" style="padding: 0.5rem 0.75rem;">
                    <option value="10" {{ $perPage==10 ? 'selected' : '' }}>10 Rows</option>
                    <option value="20" {{ $perPage==20 ? 'selected' : '' }}>20 Rows</option>
                    <option value="30" {{ $perPage==30 ? 'selected' : '' }}>30 Rows</option>
                    <option value="50" {{ $perPage==50 ? 'selected' : '' }}>50 Rows</option>
                </select>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem;">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('admin.posts.index') }}" class="btn"
                    style="background: white; border: 1px solid #e2e8f0; color: var(--text-secondary); padding: 0.5rem 1rem;">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Table View -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Product Title</th>
                    <th>Seller & Store</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr>
                    <td>
                        <div style="font-weight: 700; color: var(--text-primary); font-size: 0.9375rem;">{{ $post->title
                            }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">SLUG: /{{ $post->slug }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: var(--text-primary);">{{ $post->user->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--primary-color);">
                            <i class="fas fa-store" style="opacity: 0.7;"></i> {{ $post->store->name }}
                        </div>
                    </td>
                    <td>
                        <span class="badge" style="background: #eef2ff; color: var(--primary-color); font-weight: 600;">
                            {{ $post->category->name }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: var(--text-primary);">৳{{
                            number_format($post->discount_price ?? $post->original_price, 2) }}</div>
                        @if($post->discount_price)
                        <div style="font-size: 0.75rem; color: var(--text-secondary); text-decoration: line-through;">
                            ৳{{ number_format($post->original_price, 2) }}</div>
                        @endif
                    </td>
                    <td>
                        @if($post->status == 'active')
                        <span class="badge badge-success">Active</span>
                        @elseif($post->status == 'pending')
                        <span class="badge badge-warning">Pending</span>
                        @elseif($post->status == 'sold')
                        <span class="badge" style="background: #e0f2fe; color: #0369a1;">Sold</span>
                        @else
                        <span class="badge badge-danger">{{ ucfirst($post->status) }}</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <button onclick="openModal('{{ route('admin.posts.show', $post->id) }}')" class="btn btn-sm"
                            style="background: #f8fafc; color: var(--text-primary); border: 1px solid #e2e8f0;"
                            title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-sm"
                            style="background:#f8fafc;color:var(--primary-color);border:1px solid #e2e8f0;"
                            title="Edit Post">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.posts.delete', $post->id) }}" method="POST"
                            style="display:inline;"
                            onsubmit="return confirm('Are you sure you want to delete this post and all its media?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm"
                                style="background: #fff1f2; color: var(--danger-color); border: 1px solid #fecaca;"
                                title="Delete Post">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 4rem; color: var(--text-secondary);">
                        <i class="fas fa-store-slash"
                            style="font-size: 2.5rem; display: block; margin-bottom: 1rem; opacity: 0.2;"></i>
                        No product posts found. Click "Create New Post" to get started.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 0.875rem; color: var(--text-secondary);">
            Showing {{ $posts->firstItem() ?? 0 }} to {{ $posts->lastItem() ?? 0 }} of {{ $posts->total() }} results
        </div>
        <div>
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .pagination {
        display: flex;
        list-style: none;
        gap: 0.25rem;
    }

    .page-item {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 0.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        text-decoration: none;
        color: var(--text-secondary);
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .page-item:hover:not(.disabled) {
        background-color: #f1f5f9;
        border-color: #cbd5e1;
    }

    .page-item.active {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .page-item.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
@endsection
