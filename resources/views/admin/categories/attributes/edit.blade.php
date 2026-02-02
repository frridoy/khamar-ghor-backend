@extends('admin.layouts.app')

@section('title', 'Edit Attribute')
@section('page-title', 'Edit Attribute: ' . $attribute->name)

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div class="card">
        <form action="{{ route('admin.categories.attributes.update', [$category->id, $attribute->id]) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name" class="form-label">Attribute Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $attribute->name }}" required>
            </div>

            <div class="form-group">
                <label for="data_type" class="form-label">Data Type</label>
                <select name="data_type" id="data_type" class="form-control" required>
                    <option value="string" {{ $attribute->data_type == 'string' ? 'selected' : '' }}>String</option>
                    <option value="integer" {{ $attribute->data_type == 'integer' ? 'selected' : '' }}>Integer</option>
                    <option value="decimal" {{ $attribute->data_type == 'decimal' ? 'selected' : '' }}>Decimal</option>
                    <option value="boolean" {{ $attribute->data_type == 'boolean' ? 'selected' : '' }}>Boolean</option>
                    <option value="date" {{ $attribute->data_type == 'date' ? 'selected' : '' }}>Date</option>
                </select>
            </div>

            <div class="form-group">
                <label for="unit" class="form-label">Unit (Optional)</label>
                <input type="text" name="unit" id="unit" class="form-control" value="{{ $attribute->unit }}" placeholder="e.g. kg, ft, cc">
            </div>

            <div class="form-group">
                <label for="display_order" class="form-label">Display Order</label>
                <input type="number" name="display_order" id="display_order" class="form-control" value="{{ $attribute->display_order }}">
            </div>

            <div class="form-group" style="display: flex; gap: 2rem; margin-top: 1rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="is_required" value="1" {{ $attribute->is_required ? 'checked' : '' }}>
                    <span>Mark as Required</span>
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ $attribute->is_active ? 'checked' : '' }}>
                    <span>Active</span>
                </label>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">Update Attribute</button>
                <a href="{{ route('admin.categories.attributes.index', $category->id) }}" class="btn" style="background: var(--secondary-color); color: white;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
