@extends('admin.layouts.app')

@section('title', 'System Settings')
@section('page-title', 'Global System Settings')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">System Settings</h2>
                <p style="color: var(--text-secondary); font-size: 0.875rem;">Manage global application settings</p>
            </div>
            <a href="{{ route('admin.settings.create') }}" class="btn btn-primary" style="padding: 0.5rem 1rem;">
                <i class="fas fa-plus" style="margin-right: 0.5rem;"></i> Create Setting
            </a>
        </div>

        @if(session('success'))
            <div style="padding: 1rem 2rem; background: #dcfce7; border-left: 4px solid #22c55e; color: #166534; display: flex; gap: 1rem; align-items: center;">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Settings Table -->
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid var(--border-color);">
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--text-secondary); font-size: 0.875rem; text-transform: uppercase;">ID</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--text-secondary); font-size: 0.875rem; text-transform: uppercase;">Free Credits</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--text-secondary); font-size: 0.875rem; text-transform: uppercase;">Free Views</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--text-secondary); font-size: 0.875rem; text-transform: uppercase;">Post Cost</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: var(--text-secondary); font-size: 0.875rem; text-transform: uppercase;">Credit Price</th>
                        <th style="padding: 1rem; text-align: center; font-weight: 600; color: var(--text-secondary); font-size: 0.875rem; text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($settings as $setting)
                        <tr style="border-bottom: 1px solid var(--border-color); transition: background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                            <td style="padding: 1rem;">{{ $setting->id }}</td>
                            <td style="padding: 1rem;">{{ $setting->free_credits_on_signup }}</td>
                            <td style="padding: 1rem;">{{ $setting->free_post_views }}</td>
                            <td style="padding: 1rem;">{{ number_format($setting->default_post_credit_cost, 2) }}</td>
                            <td style="padding: 1rem;">{{ number_format($setting->credit_price, 2) }}</td>
                            <td style="padding: 1rem; text-align: center;">
                                <a href="{{ route('admin.settings.show', $setting->id) }}" class="btn btn-sm" style="background: #3b82f6; color: white; padding: 0.4rem 0.8rem; text-decoration: none; border-radius: 0.375rem; font-size: 0.875rem; display: inline-block;">
                                    <i class="fas fa-edit" style="margin-right: 0.25rem;"></i> Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 2rem; text-align: center; color: var(--text-secondary);">
                                <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 1rem; opacity: 0.5; display: block;"></i>
                                No settings found. <a href="{{ route('admin.settings.create') }}" style="color: var(--primary-color); text-decoration: none;">Create one</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .btn-sm:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }
</style>

@endsection

