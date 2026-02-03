@extends('admin.layouts.app')

@section('title', 'Customer Management')
@section('page-title', 'Customer Management')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="font-size: 1.25rem; font-weight: 700;">Customer Database</h2>
        <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Customer
        </a>
    </div>

    <div
        style="background: #f8fafc; padding: 1.5rem; border-radius: 0.75rem; margin-bottom: 2rem; border: 1px solid var(--border-color);">
        <form action="{{ route('admin.customers.index') }}" method="GET" id="filter-form"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.75rem;">Search</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control"
                    placeholder="Name, email, phone or code..." style="padding: 0.5rem 0.75rem;">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.75rem;">Role</label>
                <select name="role" class="form-control" style="padding: 0.5rem 0.75rem;" onchange="this.form.submit()">
                    <option value="">All Roles</option>
                    @foreach(config('user_roles.roles') as $id => $name)
                    <option value="{{ $id }}" {{ $role==$id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.75rem;">Show Rows</label>
                <select name="per_page" class="form-control" style="padding: 0.5rem 0.75rem;"
                    onchange="this.form.submit()">
                    <option value="10" {{ $perPage==10 ? 'selected' : '' }}>10 Rows</option>
                    <option value="20" {{ $perPage==20 ? 'selected' : '' }}>20 Rows</option>
                    <option value="30" {{ $perPage==30 ? 'selected' : '' }}>30 Rows</option>
                    <option value="50" {{ $perPage==50 ? 'selected' : '' }}>50 Rows</option>
                </select>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem;">
                    <i class="fas fa-filter"></i>
                </button>
                <a href="{{ route('admin.customers.index') }}" class="btn"
                    style="background: white; border: 1px solid #e2e8f0; color: var(--text-secondary); padding: 0.5rem 1rem;">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Customer Name</th>
                    <th>Email & Phone</th>
                    <th>Store/Company</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td><code>{{ $user->code ?? 'N/A' }}</code></td>
                    <td>
                        <div style="font-weight: 600;">{{ $user->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">Joined {{
                            $user->created_at->format('d M Y') }}</div>
                    </td>
                    <td>
                        <div>{{ $user->email }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $user->phone_number }}</div>
                    </td>
                    <td>
                        @if($user->store)
                        <span style="font-weight: 500; color: var(--primary-color);">{{ $user->store->name }}</span>
                        @else
                        <span style="color: var(--text-secondary); font-style: italic;">No Store</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge" style="background: #e0f2fe; color: #0369a1;">
                            {{ $user->role_name }}
                        </span>
                    </td>
                    <td>
                        @if($user->is_active == 1)
                        <span class="badge badge-success">Active</span>
                        @elseif($user->is_active == 2)
                        <span class="badge badge-danger">Inactive</span>
                        @elseif($user->is_active == 3)
                        <span class="badge badge-warning" style="background: #fef3c7; color: #92400e;">Suspended</span>
                        @else
                        <span class="badge badge-secondary">Unknown</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <button onclick="openModal('{{ route('admin.customers.show', $user->id) }}')" class="btn btn-sm"
                            style="background: #f8fafc; color: var(--text-primary); border: 1px solid #e2e8f0;"
                            title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="{{ route('admin.customers.edit', $user->id) }}" class="btn btn-sm"
                            style="background: #f8fafc; color: var(--primary-color); border: 1px solid #e2e8f0;"
                            title="Edit Customer">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.customers.delete', $user->id) }}" method="POST"
                            style="display:inline;"
                            onsubmit="return confirm('Are you sure you want to delete this customer?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm"
                                style="background: #fff1f2; color: var(--danger-color); border: 1px solid #fecaca;"
                                title="Delete Customer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                        <i class="fas fa-users-slash"
                            style="font-size: 2rem; display: block; margin-bottom: 1rem; opacity: 0.5;"></i>
                        No customers found matching your criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 0.875rem; color: var(--text-secondary);">
            Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} results
        </div>
        <div>
            {{ $users->links() }}
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
