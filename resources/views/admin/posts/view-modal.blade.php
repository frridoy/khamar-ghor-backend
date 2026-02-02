<div style="max-width: 1000px; margin: 0 auto;">
    <div class="card">
        <div class="card-header">
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">Post Details</h2>
                <p style="color: var(--text-secondary); font-size: 0.875rem;">Complete information about this product
                    listing</p>
            </div>
        </div>

        <!-- Product Information -->
        <div class="info-section">
            <h3 class="section-title">
                <i class="fas fa-box"></i> Product Information
            </h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Title</span>
                    <span class="info-value">{{ $post->title }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Slug</span>
                    <span class="info-value">/{{ $post->slug }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Category</span>
                    <span class="info-value">
                        <span class="badge" style="background: #eef2ff; color: var(--primary-color); font-weight: 600;">
                            {{ $post->category->name }}
                        </span>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        @if($post->status == 'active')
                        <span class="badge badge-success">Active</span>
                        @elseif($post->status == 'pending')
                        <span class="badge badge-warning">Pending</span>
                        @elseif($post->status == 'sold')
                        <span class="badge" style="background: #e0f2fe; color: #0369a1;">Sold</span>
                        @else
                        <span class="badge badge-danger">{{ ucfirst($post->status) }}</span>
                        @endif
                        @if($post->is_featured)
                        <span class="badge" style="background: #fbbf24; color: #78350f; margin-left: 0.5rem;">
                            <i class="fas fa-star"></i> Featured
                        </span>
                        @endif
                    </span>
                </div>
                <div class="info-item" style="grid-column: span 2;">
                    <span class="info-label">Description</span>
                    <span class="info-value">{{ $post->description }}</span>
                </div>
            </div>
        </div>

        <!-- Seller Information -->
        <div class="info-section">
            <h3 class="section-title">
                <i class="fas fa-user"></i> Seller Information
            </h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Seller Name</span>
                    <span class="info-value">{{ $post->user->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Seller ID</span>
                    <span class="info-value">{{ $post->user->code ?? $post->user->id }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $post->user->email }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Phone</span>
                    <span class="info-value">{{ $post->user->phone_number ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Store Information -->
        <div class="info-section">
            <h3 class="section-title">
                <i class="fas fa-store"></i> Store Information
            </h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Store Name</span>
                    <span class="info-value">{{ $post->store->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Store Type</span>
                    <span class="info-value">{{ $post->store->store_type ?? 'General' }}</span>
                </div>
                @if($post->store->phone_number)
                <div class="info-item">
                    <span class="info-label">Store Phone</span>
                    <span class="info-value">{{ $post->store->phone_number }}</span>
                </div>
                @endif
                @if($post->store->address)
                <div class="info-item" style="grid-column: span 2;">
                    <span class="info-label">Address</span>
                    <span class="info-value">{{ $post->store->address }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Pricing Information -->
        <div class="info-section">
            <h3 class="section-title">
                <i class="fas fa-tag"></i> Pricing Information
            </h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Original Price</span>
                    <span class="info-value" style="font-weight: 700; color: var(--text-primary);">৳{{
                        number_format($post->original_price, 2) }}</span>
                </div>
                @if($post->discount_price)
                <div class="info-item">
                    <span class="info-label">Discount Price</span>
                    <span class="info-value" style="font-weight: 700; color: #10b981;">৳{{
                        number_format($post->discount_price, 2) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Savings</span>
                    <span class="info-value" style="font-weight: 700; color: #10b981;">৳{{
                        number_format($post->original_price - $post->discount_price, 2) }}</span>
                </div>
                @endif
                @if($post->credit_cost)
                <div class="info-item">
                    <span class="info-label">Credit Cost</span>
                    <span class="info-value" style="font-weight: 700; color: #f59e0b;">{{
                        number_format($post->credit_cost, 0) }} Credits</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Product Specifications (Attributes) -->
        @if($post->attributeValues->count() > 0)
        <div class="info-section">
            <h3 class="section-title">
                <i class="fas fa-list-check"></i> Product Specifications
            </h3>
            <div class="info-grid">
                @foreach($post->attributeValues as $attrValue)
                <div class="info-item">
                    <span class="info-label">
                        {{ $attrValue->attribute->name }}
                        @if($attrValue->attribute->unit)
                        <span style="font-weight: 400; opacity: 0.7;">({{ $attrValue->attribute->unit }})</span>
                        @endif
                    </span>
                    <span class="info-value">
                        @if($attrValue->attribute->data_type === 'boolean')
                        @if($attrValue->value == 1)
                        <span style="color: #10b981;"><i class="fas fa-check-circle"></i> Yes</span>
                        @else
                        <span style="color: #ef4444;"><i class="fas fa-times-circle"></i> No</span>
                        @endif
                        @elseif($attrValue->attribute->data_type === 'date')
                        {{ \Carbon\Carbon::parse($attrValue->value)->format('d M Y') }}
                        @else
                        {{ $attrValue->value }}
                        @endif
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Timestamps -->
        <div class="info-section" style="border-bottom: none;">
            <h3 class="section-title">
                <i class="fas fa-clock"></i> Timeline
            </h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Created At</span>
                    <span class="info-value">{{ $post->created_at->format('d M Y, h:i A') }} <span
                            style="color: var(--text-secondary);">({{ $post->created_at->diffForHumans()
                            }})</span></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Last Updated</span>
                    <span class="info-value">{{ $post->updated_at->format('d M Y, h:i A') }} <span
                            style="color: var(--text-secondary);">({{ $post->updated_at->diffForHumans()
                            }})</span></span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .info-section {
        padding: 2rem;
        border-bottom: 1px solid var(--border-color);
    }

    .section-title {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--text-secondary);
        text-transform: uppercase;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .info-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        font-weight: 700;
    }

    .info-value {
        font-size: 0.9375rem;
        color: var(--text-primary);
        font-weight: 500;
    }
</style>
