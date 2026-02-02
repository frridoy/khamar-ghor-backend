@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="stats-grid">
    <div class="card stat-card">
        <h3>Total Users</h3>
        <div class="value">{{ $totalUsers }}</div>
    </div>
    <div class="card stat-card">
        <h3>Active Sales</h3>
        <div class="value">24</div> <!-- Placeholder for now -->
    </div>
    <div class="card stat-card">
        <h3>Pending Queries</h3>
        <div class="value">12</div> <!-- Placeholder for now -->
    </div>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.25rem; font-weight: 600;">Recently Joined Users</h2>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-primary btn-sm">View All Users</a>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentUsers as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge {{ $user->user_role == 0 ? 'badge-primary' : 'badge-warning' }}" style="{{ $user->user_role == 0 ? 'background: #e0e7ff; color: #3730a3;' : '' }}">
                            {{ $user->role_name }}
                        </span>
                    </td>
                    <td>{{ $user->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
