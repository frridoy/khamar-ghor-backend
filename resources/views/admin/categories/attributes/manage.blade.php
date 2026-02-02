@extends('admin.layouts.app')

@section('title', 'Manage Attributes')
@section('page-title', 'Sync Data Schema: ' . $category->name)

@section('content')
<div style="max-width: 1100px; margin: 0 auto;">
    <!-- Context Header -->
    <div style="background: white; border: 1px solid var(--border-color); border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; box-shadow: var(--shadow-sm);">
        <div style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 54px; height: 54px; background: #eef2ff; color: var(--primary-color); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fas fa-sync"></i>
            </div>
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0;">Bulk Manage: {{ $category->name }}</h2>
                <p style="margin: 0; font-size: 0.875rem; color: var(--text-secondary);">Add, remove, or update all attributes for this category in one go.</p>
            </div>
        </div>
        <a href="{{ route('admin.categories.attributes.index', $category->id) }}" class="btn" style="background: #f1f5f9; color: var(--text-secondary);">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card" style="padding: 2rem;">
        <form action="{{ route('admin.categories.attributes.update-bulk', $category->id) }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1rem; font-weight: 700; color: var(--text-primary);">Current Schema Overview</h3>
                <span class="badge" style="background: #f1f5f9; color: var(--text-primary); font-weight: 700;">{{ $category->attributes->count() }} Attributes</span>
            </div>

            <div id="attributes-container">
                @foreach($category->attributes as $index => $attr)
                <div class="attribute-row" style="background: #ffffff; padding: 1.25rem; border-radius: 0.75rem; margin-bottom: 1rem; border: 1px solid var(--border-color); position: relative; transition: all 0.2s; box-shadow: var(--shadow-sm);">
                    <input type="hidden" name="attributes[{{ $index }}][id]" value="{{ $attr->id }}">
                    <div style="display: grid; grid-template-columns: 2fr 1.5fr 1fr 1fr 0.5fr; gap: 1.25rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 0.75rem; color: var(--text-secondary);">Label Name</label>
                            <input type="text" name="attributes[{{ $index }}][name]" class="form-control" value="{{ $attr->name }}" required placeholder="e.g. Color, Height">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 0.75rem; color: var(--text-secondary);">Data Type</label>
                            <select name="attributes[{{ $index }}][data_type]" class="form-control" required>
                                <option value="string" {{ $attr->data_type == 'string' ? 'selected' : '' }}>Text (String)</option>
                                <option value="integer" {{ $attr->data_type == 'integer' ? 'selected' : '' }}>Number (Integer)</option>
                                <option value="decimal" {{ $attr->data_type == 'decimal' ? 'selected' : '' }}>Precise (Decimal)</option>
                                <option value="boolean" {{ $attr->data_type == 'boolean' ? 'selected' : '' }}>Toggle (Boolean)</option>
                                <option value="date" {{ $attr->data_type == 'date' ? 'selected' : '' }}>Date Picker</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 0.75rem; color: var(--text-secondary);">Unit</label>
                            <input type="text" name="attributes[{{ $index }}][unit]" class="form-control" value="{{ $attr->unit }}" placeholder="Unit">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" style="font-size: 0.75rem; color: var(--text-secondary);">Sort Order</label>
                            <input type="number" name="attributes[{{ $index }}][display_order]" class="form-control" value="{{ $attr->display_order }}">
                        </div>
                        <div style="display: flex; align-items: center; justify-content: flex-end; padding-top: 1.5rem;">
                             <label style="display: flex; flex-direction: column; align-items: center; cursor: pointer;">
                                <span style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.4rem;">Req?</span>
                                <input type="checkbox" name="attributes[{{ $index }}][is_required]" {{ $attr->is_required ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--primary-color);">
                             </label>
                        </div>
                    </div>
                    <button type="button" class="remove-row" style="position: absolute; top: -10px; right: -10px; width: 24px; height: 24px; border-radius: 50%; background: #fee2e2; border: 1px solid #fecaca; color: #b91c1c; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; box-shadow: var(--shadow-sm);">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                @endforeach
            </div>

            <div style="margin-top: 1.5rem; margin-bottom: 3rem;">
                <button type="button" id="add-more" class="btn" style="background: transparent; border: 2px dashed var(--border-color); color: var(--primary-color); width: 100%; padding: 1rem; border-radius: 0.75rem; font-weight: 700; transition: all 0.2s;">
                    <i class="fas fa-plus-circle" style="margin-right: 0.5rem;"></i> Add Another Attribute Field
                </button>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; border-top: 1px solid var(--border-color); padding-top: 2rem;">
                <a href="{{ route('admin.categories.attributes.index', $category->id) }}" class="btn" style="background: #f1f5f9; color: var(--text-secondary); font-weight: 700;">Discard Changes</a>
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2.5rem; font-weight: 700; box-shadow: var(--shadow-md);">
                    <i class="fas fa-save" style="margin-right: 0.5rem;"></i> Sync All Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let attributeCount = {{ $category->attributes->count() }};
    document.getElementById('add-more').addEventListener('click', function() {
        const container = document.getElementById('attributes-container');
        const newRow = document.createElement('div');
        newRow.className = 'attribute-row';
        newRow.style = 'background: #ffffff; padding: 1.25rem; border-radius: 0.75rem; margin-bottom: 1rem; border: 1px solid var(--border-color); position: relative; box-shadow: var(--shadow-sm); animation: slideDown 0.3s ease-out;';
        
        newRow.innerHTML = `
            <div style="display: grid; grid-template-columns: 2fr 1.5fr 1fr 1fr 0.5fr; gap: 1.25rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.75rem; color: var(--text-secondary);">Label Name</label>
                    <input type="text" name="attributes[${attributeCount}][name]" class="form-control" required placeholder="e.g. Color, Height">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.75rem; color: var(--text-secondary);">Data Type</label>
                    <select name="attributes[${attributeCount}][data_type]" class="form-control" required>
                        <option value="string">Text (String)</option>
                        <option value="integer">Number (Integer)</option>
                        <option value="decimal">Precise (Decimal)</option>
                        <option value="boolean">Toggle (Boolean)</option>
                        <option value="date">Date Picker</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.75rem; color: var(--text-secondary);">Unit</label>
                    <input type="text" name="attributes[${attributeCount}][unit]" class="form-control" placeholder="Unit">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 0.75rem; color: var(--text-secondary);">Sort Order</label>
                    <input type="number" name="attributes[${attributeCount}][display_order]" class="form-control" value="${attributeCount}">
                </div>
                <div style="display: flex; align-items: center; justify-content: flex-end; padding-top: 1.5rem;">
                     <label style="display: flex; flex-direction: column; align-items: center; cursor: pointer;">
                        <span style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.4rem;">Req?</span>
                        <input type="checkbox" name="attributes[${attributeCount}][is_required]" style="width: 18px; height: 18px; accent-color: var(--primary-color);">
                     </label>
                </div>
            </div>
            <button type="button" class="remove-row" style="position: absolute; top: -10px; right: -10px; width: 24px; height: 24px; border-radius: 50%; background: #fee2e2; border: 1px solid #fecaca; color: #b91c1c; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; box-shadow: var(--shadow-sm);">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        container.appendChild(newRow);
        
        newRow.querySelector('.remove-row').addEventListener('click', function() {
            if(confirm('Are you sure you want to remove this attribute row?')) {
                newRow.style.opacity = '0';
                newRow.style.transform = 'scale(0.95)';
                setTimeout(() => newRow.remove(), 200);
            }
        });
        
        attributeCount++;
    });

    // Handle initial remove buttons
    document.querySelectorAll('.remove-row').forEach(btn => {
        btn.addEventListener('click', function() {
            if(confirm('Warning: Removing this row will permanently delete this attribute from the database once you save. Continue?')) {
                const row = this.closest('.attribute-row');
                row.style.opacity = '0';
                row.style.transform = 'scale(0.95)';
                setTimeout(() => row.remove(), 200);
            }
        });
    });
</script>

<style>
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    #add-more:hover {
        background: #f8fafc !important;
        border-color: var(--primary-color) !important;
        transform: translateY(-2px);
    }
    .attribute-row:hover {
        border-color: #cbd5e1 !important;
        box-shadow: var(--shadow-md) !important;
    }
</style>
@endsection
