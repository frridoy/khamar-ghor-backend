@extends('admin.layouts.app')

@section('title', 'Create Product Post')
@section('page-title', 'Launch New Product')

@section('content')
<div style="max-width: 1100px; margin: 0 auto;">
    <div class="card">
        <div class="card-header">
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">Product Details</h2>
                <p style="color: var(--text-secondary); font-size: 0.875rem;">Create a new post by choosing a seller, store and category.</p>
            </div>
            <i class="fas fa-paper-plane" style="font-size: 1.5rem; color: var(--primary-color); opacity: 0.5;"></i>
        </div>

        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" id="post-create-form">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 2rem;">
                <!-- Left Column: Core Info & Dynamic Attributes -->
                <div>
                    <!-- Context Selection -->
                    <div style="background: #f8fafc; padding: 1.5rem; border-radius: 1rem; border: 1px solid var(--border-color); margin-bottom: 2rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Select Seller</label>
                            <select name="user_id" id="user-select" class="form-control" required>
                                <option value="">Choose Seller...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} (ID: {{ $user->code ?? $user->id }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Select Store</label>
                            <select name="store_id" id="store-select" class="form-control" required>
                                <option value="">Select seller first...</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 0; grid-column: span 2;">
                            <label class="form-label">Product Category</label>
                            <select name="category_id" id="category-select" class="form-control" required>
                                <option value="">Choose Category...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- General Info -->
                    <div class="form-group">
                        <label class="form-label">Listing Title</label>
                        <input type="text" name="title" class="form-control" required placeholder="e.g. Pure Breed Holstein Cow for Sale">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="5" required placeholder="Detailed information about the product..."></textarea>
                    </div>

                    <!-- Dynamic Attributes Section -->
                    <div id="dynamic-attributes-container" style="display: none; margin-top: 2.5rem; padding-top: 2rem; border-top: 2px dashed var(--border-color);">
                        <h3 style="font-size: 1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1.5rem;">
                            <i class="fas fa-list-check" style="margin-right: 0.5rem; color: var(--primary-color);"></i>
                            Category Specific Fields
                        </h3>
                        <div id="attributes-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                            <!-- AJAX content here -->
                        </div>
                    </div>
                </div>

                <!-- Right Column: Pricing & Media -->
                <div>
                    <!-- Pricing Card -->
                    <div style="background: white; border: 1px solid var(--border-color); border-radius: 1rem; padding: 1.5rem; box-shadow: var(--shadow-sm); margin-bottom: 2rem;">
                        <h3 style="font-size: 0.875rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 1.25rem;">Pricing Info</h3>
                        <div class="form-group">
                            <label class="form-label">Original Price</label>
                            <input type="number" step="0.01" name="original_price" class="form-control" required placeholder="0.00">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Discount Price (Optional)</label>
                            <input type="number" step="0.01" name="discount_price" class="form-control" placeholder="0.00">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Credit Cost (Optional)</label>
                            <input type="number" step="0.01" name="credit_cost" class="form-control" placeholder="Promotional credits">
                        </div>
                    </div>

                    <!-- Media Upload Card -->
                    <div style="background: white; border: 1px solid var(--border-color); border-radius: 1rem; padding: 1.5rem; box-shadow: var(--shadow-sm);">
                        <h3 style="font-size: 0.875rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 1.25rem;">Product Media</h3>
                        
                        <div class="form-group">
                            <label class="form-label">Upload Images (Max 3)</label>
                            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                <input type="file" name="images[]" class="form-control" accept="image/*">
                                <input type="file" name="images[]" class="form-control" accept="image/*">
                                <input type="file" name="images[]" class="form-control" accept="image/*">
                            </div>
                            <p style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.5rem;">PNG, JPG format. Max 2MB each.</p>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Product Video (Optional)</label>
                            <input type="file" name="video" class="form-control" accept="video/*">
                            <p style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.5rem;">Max duration: 30s. Max size: 20MB.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
                <a href="{{ route('admin.posts.index') }}" class="btn" style="background: #f1f5f9; color: var(--text-secondary);">
                    Discard Draft
                </a>
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2.5rem; box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.4);">
                    <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i> Publish Product Post
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userSelect = document.getElementById('user-select');
    const storeSelect = document.getElementById('store-select');
    const categorySelect = document.getElementById('category-select');
    const attributesContainer = document.getElementById('dynamic-attributes-container');
    const attributesGrid = document.getElementById('attributes-grid');

    // Handle User Selection -> Fetch Stores
    userSelect.addEventListener('change', function() {
        const userId = this.value;
        storeSelect.innerHTML = '<option value="">Loading stores...</option>';
        
        if (!userId) {
            storeSelect.innerHTML = '<option value="">Select seller first...</option>';
            return;
        }

        fetch(`/admin/posts/get-stores/${userId}`)
            .then(response => response.json())
            .then(data => {
                storeSelect.innerHTML = data.length ? '' : '<option value="">No stores found</option>';
                data.forEach(store => {
                    storeSelect.innerHTML += `<option value="${store.id}" selected>${store.name}</option>`;
                });
            });
    });

    // Handle Category Selection -> Fetch Attributes
    categorySelect.addEventListener('change', function() {
        const categoryId = this.value;
        
        if (!categoryId) {
            attributesContainer.style.display = 'none';
            attributesGrid.innerHTML = '';
            return;
        }

        attributesGrid.innerHTML = '<div style="grid-column: span 2; padding: 2rem; text-align: center; color: var(--text-secondary);"><i class="fas fa-spinner fa-spin"></i> Loading specialized fields...</div>';
        attributesContainer.style.display = 'block';

        fetch(`/admin/posts/get-attributes/${categoryId}`)
            .then(response => response.json())
            .then(data => {
                attributesGrid.innerHTML = '';
                if (data.length === 0) {
                    attributesGrid.innerHTML = '<div style="grid-column: span 2; padding: 2rem; border-radius: 1rem; background: #f8fafc; text-align: center; color: var(--text-secondary);">This category uses basic information only.</div>';
                    return;
                }

                data.forEach(attr => {
                    let inputHtml = '';
                    const isReq = attr.is_required ? 'required' : '';
                    
                    if (attr.data_type === 'boolean') {
                        inputHtml = `
                            <select name="attributes[${attr.id}]" class="form-control" ${isReq}>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>`;
                    } else if (attr.data_type === 'date') {
                        inputHtml = `<input type="date" name="attributes[${attr.id}]" class="form-control" ${isReq}>`;
                    } else if (attr.data_type === 'integer' || attr.data_type === 'decimal') {
                        inputHtml = `<input type="number" step="${attr.data_type === 'decimal' ? '0.01' : '1'}" name="attributes[${attr.id}]" class="form-control" ${isReq} placeholder="${attr.unit ? 'Unit: ' + attr.unit : ''}">`;
                    } else {
                        inputHtml = `<input type="text" name="attributes[${attr.id}]" class="form-control" ${isReq} placeholder="${attr.unit ? 'Unit: ' + attr.unit : ''}">`;
                    }

                    attributesGrid.innerHTML += `
                        <div class="form-group">
                            <label class="form-label">${attr.name}${attr.is_required ? ' <span style="color: var(--danger-color);">*</span>' : ''}</label>
                            ${inputHtml}
                        </div>
                    `;
                });
            });
    });
});
</script>
@endsection
