@extends('admin.layouts.app')

@section('title', 'Edit Customer')
@section('page-title', 'Edit Customer: ' . $user->name)

@section('content')
<div style="max-width: 900px; margin: 0 auto;">
    <div class="card">
        <div class="card-header">
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">Update Information</h2>
                <p style="color: var(--text-secondary); font-size: 0.875rem;">Modify the details for <strong>{{ $user->name }}</strong> (Code: {{ $user->code }})</p>
            </div>
            <i class="fas fa-user-edit" style="font-size: 1.5rem; color: var(--primary-color); opacity: 0.5;"></i>
        </div>

        <form action="{{ route('admin.customers.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Left Column -->
                <div>
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required placeholder="e.g. John Doe">
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required placeholder="john@example.com">
                    </div>

                    <div class="form-group">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}" required placeholder="017XXXXXXXX">
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <div class="form-group">
                        <label for="user_role" class="form-label">System Role</label>
                        <select name="user_role" id="user_role" class="form-control" required>
                            @foreach(config('user_roles.roles') as $id => $name)
                                <option value="{{ $id }}" {{ old('user_role', $user->user_role) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="is_active" class="form-label">Account Status</label>
                        <select name="is_active" id="is_active" class="form-control" required>
                            <option value="1" {{ old('is_active', $user->is_active) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="2" {{ old('is_active', $user->is_active) == 2 ? 'selected' : '' }}>Inactive</option>
                            <option value="3" {{ old('is_active', $user->is_active) == 3 ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Update Password (Leave blank to keep current)</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="••••••••">
                        <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.5rem;">Only fill this if you want to change the password.</p>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
                <a href="{{ route('admin.customers.index') }}" class="btn" style="background: #f1f5f9; color: var(--text-secondary);">
                    Cancel & Return
                </a>
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.4);">
                    <i class="fas fa-save" style="margin-right: 0.5rem;"></i> Update Customer Account
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
