@extends('admin.layouts.app')

@section('title', 'Store Management')
@section('page-title', 'Store Management')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="font-size: 1.25rem; font-weight: 700;">Store Database</h2>
        <a href="{{ route('admin.stores.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Store
        </a>
    </div>

    <!-- Filters Section -->
    <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.75rem; margin-bottom: 2rem; border: 1px solid var(--border-color);">
        <form action="{{ route('admin.stores.index') }}" method="GET" id="filter-form" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.75rem;">Search</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Store name, type, or address..." style="padding: 0.5rem 0.75rem;">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.75rem;">Owner</label>
                <select name="user_id" class="form-control" style="padding: 0.5rem 0.75rem;" onchange="this.form.submit()">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->code }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.75rem;">Verification</label>
                <select name="is_verified" class="form-control" style="padding: 0.5rem 0.75rem;" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="1" {{ $isVerified == 1 ? 'selected' : '' }}>Verified</option>
                    <option value="0" {{ $isVerified == 0 ? 'selected' : '' }}>Not Verified</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.75rem;">Show Rows</label>
                <select name="per_page" class="form-control" style="padding: 0.5rem 0.75rem;" onchange="this.form.submit()">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 Rows</option>
                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20 Rows</option>
                    <option value="30" {{ $perPage == 30 ? 'selected' : '' }}>30 Rows</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 Rows</option>
                </select>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem;">
                    <i class="fas fa-filter"></i>
                </button>
                <a href="{{ route('admin.stores.index') }}" class="btn" style="background: white; border: 1px solid #e2e8f0; color: var(--text-secondary); padding: 0.5rem 1rem;">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Store Name</th>
                    <th>Owner</th>
                    <th>Status</th>
                    <th>Location</th>
                    <th>Phone</th>
                    <th>Verification</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stores as $store)
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-primary); font-size: 0.9375rem;">{{ $store->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">ID: {{ $store->id }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 500;">{{ $store->user->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $store->user->code ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <span class="badge" style="background: #e0f2fe; color: #0369a1;">
                            {{ $store->is_active == 1 ? 'Active' : ($store->is_active == 2 ? 'Inactive' : 'Suspended') }}
                        </span>
                    </td>
                    <td>
                        <div style="font-size: 0.875rem; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $store->address ?? 'N/A' }}
                        </div>
                    </td>
                    <td>
                        {{ $store->phone ?? 'N/A' }}
                    </td>
                    <td>
                        @if($store->is_verified == 1)
                            <span class="badge badge-success">
                                <i class="fas fa-check-circle"></i> Verified
                            </span>
                        @else
                            <span class="badge badge-warning" style="background: #fef3c7; color: #92400e;">
                                <i class="fas fa-hourglass-half"></i> Not Verified
                            </span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <button onclick="openModal('{{ route('admin.stores.show', $store->id) }}')" class="btn btn-sm" style="background: #f8fafc; color: var(--text-primary); border: 1px solid #e2e8f0;" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="{{ route('admin.stores.edit', $store->id) }}" class="btn btn-sm" style="background: #eff6ff; color: #0369a1; border: 1px solid #bfdbfe; margin-left: 0.5rem;" title="Edit Store">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                        <i class="fas fa-store" style="font-size: 2rem; display: block; margin-bottom: 1rem; opacity: 0.5;"></i>
                        No stores found matching your criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 0.875rem; color: var(--text-secondary);">
            Showing {{ $stores->firstItem() ?? 0 }} to {{ $stores->lastItem() ?? 0 }} of {{ $stores->total() }} results
        </div>
        <div>
            {{ $stores->links() }}
        </div>
    </div>
</div>

<!-- Modal for Viewing Store -->
<div id="viewModal" class="modal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div class="modal-content" style="background: white; border-radius: 0.75rem; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
        <div id="modalBody"></div>
    </div>
</div>

@endsection

@section('styles')
<style>
    .modal {
        display: none !important;
    }

    .modal.active {
        display: flex !important;
    }

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

<script>
function openModal(url) {
    fetch(url)
        .then(response => response.text())
        .then(html => {
            document.getElementById('modalBody').innerHTML = html;
            document.getElementById('viewModal').classList.add('active');
        });
}

function closeModal() {
    document.getElementById('viewModal').classList.remove('active');
}

function verifyStoreAction(storeId, isVerify) {
    const message = isVerify
        ? 'Are you sure you want to verify this store?'
        : 'Are you sure you want to unverify this store?';

    if (confirm(message)) {
        const routeUrl = '{{ route("admin.stores.verify", ":id") }}'.replace(':id', storeId);

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = routeUrl;

        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = '_token';
            input.value = csrfToken.getAttribute('content');
            form.appendChild(input);
        } else {
            console.error('CSRF token not found');
            alert('Security token not found. Please refresh the page.');
            return;
        }

        const isVerifiedInput = document.createElement('input');
        isVerifiedInput.type = 'hidden';
        isVerifiedInput.name = 'is_verified';
        isVerifiedInput.value = isVerify ? 1 : 0;
        form.appendChild(isVerifiedInput);

        document.body.appendChild(form);
        form.submit();
    }
}

document.getElementById('viewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>
@endsection
