@extends('admin.layouts.app')

@section('title', 'Edit Store')
@section('page-title', 'Edit Store Information')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <div class="card">
        <div class="card-header">
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">Edit Store Information</h2>
                <p style="color: var(--text-secondary); font-size: 0.875rem;">Update store details</p>
            </div>
            <i class="fas fa-edit" style="font-size: 1.5rem; color: var(--primary-color); opacity: 0.5;"></i>
        </div>

        <form action="{{ route('admin.stores.update', $store->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; padding: 2rem;">
                <!-- Left Column -->
                <div>
                    <!-- Store Owner (Disabled) -->
                    <div class="form-group">
                        <label class="form-label">Store Owner</label>
                        <input type="text" class="form-control" value="{{ $store->user->name }} ({{ $store->user->code }})" disabled>
                        <small style="color: var(--text-secondary);">Cannot change store owner</small>
                    </div>

                    <!-- Store Name -->
                    <div class="form-group">
                        <label class="form-label">Store Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $store->name) }}" placeholder="Enter store name" required>
                        @error('name') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Store description" rows="3">{{ old('description', $store->description) }}</textarea>
                        @error('description') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $store->phone) }}" placeholder="+880 1234567890">
                        @error('phone') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $store->email) }}" placeholder="store@example.com">
                        @error('email') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- Website -->
                    <div class="form-group">
                        <label class="form-label">Website</label>
                        <input type="url" name="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website', $store->website) }}" placeholder="https://example.com">
                        @error('website') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- Trade License Number -->
                    <div class="form-group">
                        <label class="form-label">Trade License Number</label>
                        <input type="text" name="trade_license_number" class="form-control @error('trade_license_number') is-invalid @enderror" value="{{ old('trade_license_number', $store->trade_license_number) }}" placeholder="TL-123456789">
                        @error('trade_license_number') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- Established Date -->
                    <div class="form-group">
                        <label class="form-label">Established Date</label>
                        <input type="date" name="established_date" class="form-control @error('established_date') is-invalid @enderror" value="{{ old('established_date', is_string($store->established_date) ? $store->established_date : ($store->established_date?->format('Y-m-d') ?? '')) }}">
                        @error('established_date') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- Store Logo -->
                    <div class="form-group">
                        <label class="form-label">Store Logo</label>
                        @if($store->logo)
                            <div style="margin-bottom: 0.75rem;">
                                <img src="{{ asset('storage/' . $store->logo) }}" alt="Current Logo" style="max-width: 100%; height: auto; max-height: 120px; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                            </div>
                        @endif
                        <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                        <small style="color: var(--text-secondary);">Max 2MB, JPG/PNG/WebP. Leave empty to keep current logo.</small>
                        @error('logo') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- Store Cover Image -->
                    <div class="form-group">
                        <label class="form-label">Store Cover Image</label>
                        @if($store->cover_image)
                            <div style="margin-bottom: 0.75rem;">
                                <img src="{{ asset('storage/' . $store->cover_image) }}" alt="Current Cover" style="max-width: 100%; height: auto; max-height: 120px; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                            </div>
                        @endif
                        <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror" accept="image/*">
                        <small style="color: var(--text-secondary);">Max 2MB, JPG/PNG/WebP. Leave empty to keep current cover image.</small>
                        @error('cover_image') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Right Column - Location & Media -->
                <div>
                    <!-- Country -->
                    <div class="form-group">
                        <label class="form-label">Country *</label>
                        <select name="country_id" id="country_id" class="form-control @error('country_id') is-invalid @enderror" onchange="loadDivisions()" required>
                            <option value="">-- Select Country --</option>
                            @forelse($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id', $store->country_id) == $country->id ? 'selected' : '' }}>{{ $country->name_en }}</option>
                            @empty
                                <option disabled>No countries available</option>
                            @endforelse
                        </select>
                        @error('country_id') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- Division -->
                    <div class="form-group">
                        <label class="form-label">Division</label>
                        <select name="division_id" id="division_id" class="form-control @error('division_id') is-invalid @enderror" onchange="loadDistricts()">
                            <option value="">-- Select Division --</option>
                            @if(old('country_id', $store->country_id))
                                @php
                                    $divisions = \App\Models\Division::where('country_id', old('country_id', $store->country_id))->orderBy('name_en')->get();
                                @endphp
                                @forelse($divisions as $division)
                                    <option value="{{ $division->id }}" {{ old('division_id', $store->division_id) == $division->id ? 'selected' : '' }}>{{ $division->name_en }}</option>
                                @empty
                                @endforelse
                            @endif
                        </select>
                        @error('division_id') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- District -->
                    <div class="form-group">
                        <label class="form-label">District</label>
                        <select name="district_id" id="district_id" class="form-control @error('district_id') is-invalid @enderror" onchange="loadThanas()">
                            <option value="">-- Select District --</option>
                            @if(old('division_id', $store->division_id))
                                @php
                                    $districts = \App\Models\District::where('division_id', old('division_id', $store->division_id))->orderBy('name_en')->get();
                                @endphp
                                @forelse($districts as $district)
                                    <option value="{{ $district->id }}" {{ old('district_id', $store->district_id) == $district->id ? 'selected' : '' }}>{{ $district->name_en }}</option>
                                @empty
                                @endforelse
                            @endif
                        </select>
                        @error('district_id') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- Thana -->
                    <div class="form-group">
                        <label class="form-label">Thana</label>
                        <select name="thana_id" id="thana_id" class="form-control @error('thana_id') is-invalid @enderror">
                            <option value="">-- Select Thana --</option>
                            @if(old('district_id', $store->district_id))
                                @php
                                    $thanas = \App\Models\Thana::where('district_id', old('district_id', $store->district_id))->orderBy('name_en')->get();
                                @endphp
                                @forelse($thanas as $thana)
                                    <option value="{{ $thana->id }}" {{ old('thana_id', $store->thana_id) == $thana->id ? 'selected' : '' }}>{{ $thana->name_en }}</option>
                                @empty
                                @endforelse
                            @endif
                        </select>
                        @error('thana_id') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- Area Name -->
                    <div class="form-group">
                        <label class="form-label">Area Name</label>
                        <input type="text" name="area_name" class="form-control @error('area_name') is-invalid @enderror" value="{{ old('area_name', $store->area_name) }}" placeholder="e.g., Mirpur, Gulshan">
                        @error('area_name') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- Postal Code -->
                    <div class="form-group">
                        <label class="form-label">Postal Code</label>
                        <input type="text" name="postal_code" class="form-control @error('postal_code') is-invalid @enderror" value="{{ old('postal_code', $store->postal_code) }}" placeholder="1212">
                        @error('postal_code') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- Address -->
                    <div class="form-group">
                        <label class="form-label">Full Address</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" placeholder="Enter complete store address" rows="2">{{ old('address', $store->address) }}</textarea>
                        @error('address') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-control @error('is_active') is-invalid @enderror">
                            <option value="1" {{ old('is_active', $store->is_active) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="2" {{ old('is_active', $store->is_active) == 2 ? 'selected' : '' }}>Inactive</option>
                            <option value="3" {{ old('is_active', $store->is_active) == 3 ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @error('is_active') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div style="padding: 0 2rem 2rem 2rem; display: flex; gap: 1rem; justify-content: flex-end;">
                <a href="{{ route('admin.stores.index') }}" class="btn" style="background: #f1f5f9; color: var(--text-secondary);">
                    Cancel & Return
                </a>
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.4);">
                    <i class="fas fa-save" style="margin-right: 0.5rem;"></i> Update Store
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('styles')
<style>
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        font-size: 0.9375rem;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .form-control:disabled {
        background-color: #f1f5f9;
        cursor: not-allowed;
    }

    .form-control.is-invalid {
        border-color: #dc2626;
    }

    .error-message {
        display: block;
        color: #dc2626;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }
</style>

<script>
function loadDivisions() {
    const countryId = document.getElementById('country_id').value;
    const divisionSelect = document.getElementById('division_id');

    divisionSelect.innerHTML = '<option value="">-- Select Division --</option>';
    document.getElementById('district_id').innerHTML = '<option value="">-- Select District --</option>';
    document.getElementById('thana_id').innerHTML = '<option value="">-- Select Thana --</option>';

    if (!countryId) return;

    fetch(`/admin/divisions/${countryId}`)
        .then(r => r.json())
        .then(divisions => {
            divisions.forEach(d => {
                const option = document.createElement('option');
                option.value = d.id;
                option.textContent = d.name;
                divisionSelect.appendChild(option);
            });
        });
}

function loadDistricts() {
    const divisionId = document.getElementById('division_id').value;
    const districtSelect = document.getElementById('district_id');

    districtSelect.innerHTML = '<option value="">-- Select District --</option>';
    document.getElementById('thana_id').innerHTML = '<option value="">-- Select Thana --</option>';

    if (!divisionId) return;

    fetch(`/admin/districts/${divisionId}`)
        .then(r => r.json())
        .then(districts => {
            districts.forEach(d => {
                const option = document.createElement('option');
                option.value = d.id;
                option.textContent = d.name;
                districtSelect.appendChild(option);
            });
        });
}

function loadThanas() {
    const districtId = document.getElementById('district_id').value;
    const thanaSelect = document.getElementById('thana_id');

    thanaSelect.innerHTML = '<option value="">-- Select Thana --</option>';

    if (!districtId) return;

    fetch(`/admin/thanas/${districtId}`)
        .then(r => r.json())
        .then(thanas => {
            thanas.forEach(t => {
                const option = document.createElement('option');
                option.value = t.id;
                option.textContent = t.name;
                thanaSelect.appendChild(option);
            });
        });
}
</script>
@endsection
