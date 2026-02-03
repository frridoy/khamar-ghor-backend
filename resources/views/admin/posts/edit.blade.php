@extends('admin.layouts.app')

@section('title', 'Edit Product Post')
@section('page-title', 'Edit Product')

@section('content')
<div style="max-width: 1100px; margin: 0 auto;">
    <div class="card">
        <div class="card-header">
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 700;">Edit Product Details</h2>
                <p style="color: var(--text-secondary); font-size: 0.875rem;">
                    Update seller, store, category and product details.
                </p>
            </div>
        </div>

        <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data"
            id="post-edit-form">
            @csrf
            @method('PUT')

            <div style="display:grid;grid-template-columns:1.5fr 1fr;gap:2rem;">

                <!-- LEFT COLUMN -->
                <div>

                    <!-- Context -->
                    <div
                        style="background:#f8fafc;padding:1.5rem;border-radius:1rem;border:1px solid var(--border-color);margin-bottom:2rem;display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
                        <div class="form-group">
                            <label class="form-label">Seller</label>
                            <select name="user_id" id="user-select" class="form-control" required>
                                <option value="">Choose Seller...</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $post->user_id) == $user->id ?
                                    'selected' : '' }}>
                                    {{ $user->name }} (ID: {{ $user->id }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Store</label>
                            <select name="store_id" id="store-select" class="form-control" required>
                                @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ old('store_id', $post->store_id) == $store->id ?
                                    'selected' : '' }}>
                                    {{ $store->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" style="grid-column: span 2;">
                            <label class="form-label">Category</label>
                            <select name="category_id" id="category-select" class="form-control" required>
                                <option value="">Choose Category...</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) ==
                                    $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="form-group">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}"
                            required>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="5"
                            required>{{ old('description', $post->description) }}</textarea>
                    </div>

                    <!-- Dynamic Attributes -->
                    <div id="dynamic-attributes-container"
                        style="margin-top:2.5rem;padding-top:2rem;border-top:2px dashed var(--border-color);">
                        <h3 style="font-size:1rem;font-weight:700;margin-bottom:1.5rem;">
                            Category Specific Fields
                        </h3>
                        <div id="attributes-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
                        </div>
                    </div>

                </div>

                <!-- RIGHT COLUMN -->
                <div>

                    <!-- Pricing -->
                    <div
                        style="background:#fff;border:1px solid var(--border-color);border-radius:1rem;padding:1.5rem;margin-bottom:2rem;">
                        <div class="form-group">
                            <label class="form-label">Original Price</label>
                            <input type="number" step="0.01" name="original_price" class="form-control"
                                value="{{ old('original_price', $post->original_price) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Discount Price</label>
                            <input type="number" step="0.01" name="discount_price" class="form-control"
                                value="{{ old('discount_price', $post->discount_price) }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Credit Cost</label>
                            <input type="number" step="0.01" name="credit_cost" class="form-control"
                                value="{{ old('credit_cost', $post->credit_cost) }}">
                        </div>
                    </div>

                    <!-- Existing Images -->
                    <div class="form-group">
                        <label class="form-label">Existing Images</label>
                        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                            @foreach($post->media->where('type','image') as $media)
                            <img src="{{ asset('storage/'.$media->file_path) }}"
                                style="width:90px;height:90px;object-fit:cover;border-radius:0.5rem;">
                            @endforeach
                        </div>
                    </div>

                    <!-- Upload New Media -->
                    <div class="form-group">
                        <label class="form-label">Upload New Images</label>
                        <input type="file" name="images[]" class="form-control" multiple>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Replace Video</label>
                        <input type="file" name="video" class="form-control">
                    </div>

                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:1rem;margin-top:3rem;">
                <a href="{{ route('admin.posts.index') }}" class="btn">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

    const categorySelect = document.getElementById('category-select');
    const attributesGrid = document.getElementById('attributes-grid');
    const existingValues = @json($attributeValues);

    function loadAttributes(categoryId) {
        if (!categoryId) return;

        fetch(`/admin/posts/get-attributes/${categoryId}`)
            .then(res => res.json())
            .then(data => {
                attributesGrid.innerHTML = '';

                data.forEach(attr => {
                    const value = existingValues[attr.id] ?? '';
                    let input = `<input type="text" name="attributes[${attr.id}]" value="${value}" class="form-control">`;

                    if (attr.data_type === 'boolean') {
                        input = `
                        <select name="attributes[${attr.id}]" class="form-control">
                            <option value="1" ${value == 1 ? 'selected' : ''}>Yes</option>
                            <option value="0" ${value == 0 ? 'selected' : ''}>No</option>
                        </select>`;
                    }

                    attributesGrid.innerHTML += `
                        <div class="form-group">
                            <label class="form-label">${attr.name}</label>
                            ${input}
                        </div>
                    `;
                });
            });
    }

    loadAttributes(categorySelect.value);
    categorySelect.addEventListener('change', e => loadAttributes(e.target.value));
});
</script>
@endsection
