<div style="padding: 0.5rem;">
    <!-- Modal Header -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border-color);">
        <div style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 64px; height: 64px; border-radius: 1rem; overflow: hidden; box-shadow: var(--shadow-sm); background: #f1f5f9; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-color);">
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <i class="fas fa-tags fa-xl" style="color: var(--primary-color);"></i>
                @endif
            </div>
            <div>
                <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin: 0;">{{ $category->name }}</h2>
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-top: 0.25rem;">
                    <span style="color: var(--text-secondary); font-size: 0.8125rem;"><i class="fas fa-link" style="font-size: 0.75rem;"></i> /{{ $category->slug }}</span>
                </div>
            </div>
        </div>
        <div style="text-align: right;">
            @if($category->is_active)
                <span class="badge badge-success" style="padding: 0.4rem 0.8rem; font-size: 0.75rem; border-radius: 2rem;">● Active</span>
            @else
                <span class="badge badge-danger" style="padding: 0.4rem 0.8rem; font-size: 0.75rem; border-radius: 2rem;">● Inactive</span>
            @endif
        </div>
    </div>

    <!-- Attributes Header -->
    <div style="margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
        <h3 style="font-size: 0.875rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-list-check" style="color: var(--primary-color);"></i> Dynamic Attributes
        </h3>
        <span class="badge" style="background: #f1f5f9; color: var(--text-primary); font-weight: 700;">{{ $category->attributes->count() }} Definitions</span>
    </div>

    <!-- Attributes Grid -->
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; max-height: 400px; overflow-y: auto; padding: 2px;">
        @forelse($category->attributes as $attr)
            <div style="background: white; border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1rem; transition: transform 0.2s; box-shadow: var(--shadow-sm);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                    <span style="font-weight: 700; color: var(--text-primary); font-size: 0.9375rem;">{{ $attr->name }}</span>
                    @if($attr->is_required)
                        <span style="font-size: 0.625rem; background: #fee2e2; color: #b91c1c; padding: 0.125rem 0.5rem; border-radius: 1rem; font-weight: 800;">REQUIRED</span>
                    @endif
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.4rem; font-size: 0.8125rem;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-secondary);">Data Type:</span>
                        <span style="font-weight: 600; color: var(--primary-color);">{{ strtoupper($attr->data_type) }}</span>
                    </div>
                    @if($attr->unit)
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-secondary);">Measurement Unit:</span>
                            <span style="font-weight: 600; color: var(--text-primary);">{{ $attr->unit }}</span>
                        </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; margin-top: 0.25rem; padding-top: 0.4rem; border-top: 1px solid #f8fafc;">
                        <span style="color: var(--text-secondary);">UI Order: #{{ $attr->display_order }}</span>
                        <span style="color: {{ $attr->is_active ? 'var(--success-color)' : 'var(--danger-color)' }}; font-weight: 700;">
                            {{ $attr->is_active ? 'Active' : 'Hidden' }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: span 2; text-align: center; padding: 3rem; background: #f8fafc; border: 1px dashed var(--border-color); border-radius: 1rem;">
                <i class="fas fa-layer-group fa-2x" style="opacity: 0.2; margin-bottom: 1rem; display: block;"></i>
                <p style="margin: 0; color: var(--text-secondary);">No attributes defined for this category.</p>
            </div>
        @endforelse
    </div>

    <!-- Actions -->
    <div style="margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 1rem;">
        <button onclick="closeModal()" class="btn" style="background: #f1f5f9; color: var(--text-secondary); font-weight: 600;">Dismiss</button>
        <a href="{{ route('admin.categories.attributes.index', $category->id) }}" class="btn btn-primary" style="padding: 0.625rem 1.5rem;">
            <i class="fas fa-cog" style="margin-right: 0.5rem;"></i> Manage Attributes
        </a>
    </div>
</div>
