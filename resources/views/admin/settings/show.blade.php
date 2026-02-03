@extends('admin.layouts.app')

@section('title', $setting->id ? 'Edit Setting' : 'Create Setting')
@section('page-title', $setting->id ? 'Edit System Setting' : 'Create System Setting')

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    <div class="card">
        <div class="card-header">
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">{{ $setting->id ? 'Edit' : 'Create' }} System Setting</h2>
                <p style="color: var(--text-secondary); font-size: 0.875rem;">{{ $setting->id ? 'Update the system setting values' : 'Add a new system setting configuration' }}</p>
            </div>
            <i class="fas fa-cog" style="font-size: 1.5rem; color: var(--primary-color); opacity: 0.5;"></i>
        </div>

        @if(session('success'))
            <div style="padding: 1rem 2rem; background: #dcfce7; border-left: 4px solid #22c55e; color: #166534; display: flex; gap: 1rem; align-items: center;">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ $setting->id ? route('admin.settings.update', $setting->id) : route('admin.settings.store') }}" method="POST" style="padding: 2rem;">
            @csrf
            @if($setting->id)
                @method('PUT')
            @endif

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Left Column -->
                <div>
                    <!-- Free Credits on Signup -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-gift" style="color: var(--primary-color); margin-right: 0.5rem;"></i>
                            Free Credits on Signup *
                        </label>
                        <input type="number" name="free_credits_on_signup" class="form-control @error('free_credits_on_signup') is-invalid @enderror"
                               value="{{ old('free_credits_on_signup', $setting->free_credits_on_signup ?? 0) }}"
                               placeholder="Number of free credits" min="0" required>
                        <small style="color: var(--text-secondary);">Credits given to new users upon signup</small>
                        @error('free_credits_on_signup') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- Default Post Credit Cost -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-file" style="color: var(--primary-color); margin-right: 0.5rem;"></i>
                            Default Post Credit Cost *
                        </label>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="number" name="default_post_credit_cost" class="form-control @error('default_post_credit_cost') is-invalid @enderror"
                                   value="{{ old('default_post_credit_cost', $setting->default_post_credit_cost ?? 0) }}"
                                   placeholder="0.00" step="0.01" min="0" required style="flex: 1;">
                        </div>
                        <small style="color: var(--text-secondary);">Cost in credits to create a post</small>
                        @error('default_post_credit_cost') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <!-- Free Post Views -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-eye" style="color: var(--primary-color); margin-right: 0.5rem;"></i>
                            Free Post Views *
                        </label>
                        <input type="number" name="free_post_views" class="form-control @error('free_post_views') is-invalid @enderror"
                               value="{{ old('free_post_views', $setting->free_post_views ?? 0) }}"
                               placeholder="Number of free views" min="0" required>
                        <small style="color: var(--text-secondary);">Free post views given to new users</small>
                        @error('free_post_views') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- Credit Price -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-dollar-sign" style="color: var(--primary-color); margin-right: 0.5rem;"></i>
                            Credit Price (in Currency) *
                        </label>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="number" name="credit_price" class="form-control @error('credit_price') is-invalid @enderror"
                                   value="{{ old('credit_price', $setting->credit_price ?? 0) }}"
                                   placeholder="0.00" step="0.01" min="0" required style="flex: 1;">
                        </div>
                        <small style="color: var(--text-secondary);">Price of one credit in local currency</small>
                        @error('credit_price') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Info Cards -->
            <div style="margin-top: 2.5rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
                <h3 style="font-size: 0.875rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 1rem;">
                    <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i> How These Settings Work
                </h3>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                    <div style="padding: 1rem; background: #f0f9ff; border-left: 4px solid #3b82f6; border-radius: 0.5rem;">
                        <p style="font-size: 0.875rem; font-weight: 600; color: #1e40af; margin-bottom: 0.5rem;">
                            <i class="fas fa-gift"></i> Free Credits
                        </p>
                        <p style="font-size: 0.8125rem; color: var(--text-secondary);">
                            When a new user signs up, they receive this many free credits to use for creating posts or other actions.
                        </p>
                    </div>
                    <div style="padding: 1rem; background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 0.5rem;">
                        <p style="font-size: 0.875rem; font-weight: 600; color: #b45309; margin-bottom: 0.5rem;">
                            <i class="fas fa-eye"></i> Free Views
                        </p>
                        <p style="font-size: 0.8125rem; color: var(--text-secondary);">
                            Free post views allocated to new users. After these are consumed, they need to purchase more credits.
                        </p>
                    </div>
                    <div style="padding: 1rem; background: #f5f3ff; border-left: 4px solid #8b5cf6; border-radius: 0.5rem;">
                        <p style="font-size: 0.875rem; font-weight: 600; color: #6d28d9; margin-bottom: 0.5rem;">
                            <i class="fas fa-file"></i> Post Cost
                        </p>
                        <p style="font-size: 0.8125rem; color: var(--text-secondary);">
                            Default number of credits required to create a new post. Can be adjusted per post type.
                        </p>
                    </div>
                    <div style="padding: 1rem; background: #fce7f3; border-left: 4px solid #ec4899; border-radius: 0.5rem;">
                        <p style="font-size: 0.875rem; font-weight: 600; color: #be185d; margin-bottom: 0.5rem;">
                            <i class="fas fa-dollar-sign"></i> Credit Price
                        </p>
                        <p style="font-size: 0.8125rem; color: var(--text-secondary);">
                            The price of one credit in your local currency. Users pay this to purchase additional credits.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end;">
                <a href="{{ route('admin.settings.index') }}" class="btn" style="background: #f1f5f9; color: var(--text-secondary);">
                    <i class="fas fa-times" style="margin-right: 0.5rem;"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.4);">
                    <i class="fas fa-save" style="margin-right: 0.5rem;"></i> {{ $setting->id ? 'Update' : 'Create' }} Setting
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
@endsection
