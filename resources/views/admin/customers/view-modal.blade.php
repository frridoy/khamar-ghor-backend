<div style="padding: 0.5rem;">
    <!-- Modal Header -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border-color);">
        <div style="display: flex; align-items: center; gap: 1.25rem;">
            <div style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary-color) 0%, #4338ca 100%); color: white; border-radius: 1rem; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; font-weight: 700; box-shadow: var(--shadow-md);">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div>
                <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin: 0;">{{ $user->name }}</h2>
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-top: 0.25rem;">
                    <span class="badge" style="background: #e0f2fe; color: #0369a1; font-weight: 600; font-size: 0.75rem;">{{ $user->role_name }}</span>
                    <span style="color: var(--text-secondary); font-size: 0.8125rem;">ID: #{{ $user->code ?? $user->id }}</span>
                </div>
            </div>
        </div>
        <div style="text-align: right;">
            @if($user->is_active == 1)
                <span class="badge badge-success" style="padding: 0.4rem 0.8rem; font-size: 0.75rem; border-radius: 2rem;">● Active</span>
            @elseif($user->is_active == 2)
                <span class="badge badge-danger" style="padding: 0.4rem 0.8rem; font-size: 0.75rem; border-radius: 2rem;">● Inactive</span>
            @else
                <span class="badge badge-warning" style="padding: 0.4rem 0.8rem; font-size: 0.75rem; border-radius: 2rem;">● Suspended</span>
            @endif
        </div>
    </div>

    <!-- Info Grid -->
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
        <!-- Contact Block -->
        <div style="background: #ffffff; border: 1px solid var(--border-color); border-radius: 1rem; padding: 1.5rem; box-shadow: var(--shadow-sm);">
            <h3 style="font-size: 0.875rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-address-book" style="color: var(--primary-color);"></i> Contact Info
            </h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div>
                    <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Email Address</div>
                    <div style="font-weight: 600; color: var(--text-primary);">{{ $user->email }}</div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Phone Number</div>
                    <div style="font-weight: 600; color: var(--text-primary);">{{ $user->phone_number }}</div>
                </div>
            </div>
        </div>

        <!-- System Status Block -->
        <div style="background: #ffffff; border: 1px solid var(--border-color); border-radius: 1rem; padding: 1.5rem; box-shadow: var(--shadow-sm);">
            <h3 style="font-size: 0.875rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-shield-alt" style="color: var(--primary-color);"></i> Account Status
            </h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div>
                    <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Registration Date</div>
                    <div style="font-weight: 600; color: var(--text-primary);">{{ $user->created_at->format('M d, Y') }}</div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.25rem;">Profile Status</div>
                    <div style="font-weight: 600; color: {{ $user->is_profile_completed ? 'var(--success-color)' : 'var(--warning-color)' }};">
                        {{ $user->is_profile_completed ? '● Completed' : '● Pending' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Company/Store Section if exists -->
    @if($user->store)
    <div style="margin-top: 1.5rem; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 1rem; padding: 1.5rem;">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
            <div style="width: 48px; height: 48px; background: white; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-color); color: var(--primary-color);">
                <i class="fas fa-store fa-lg"></i>
            </div>
            <div>
                <h4 style="margin: 0; font-size: 1rem; font-weight: 700; color: var(--text-primary);">{{ $user->store->name }}</h4>
                <p style="margin: 0; font-size: 0.75rem; color: var(--text-secondary);">Business Store Profile</p>
            </div>
        </div>
        <p style="margin: 0; font-size: 0.875rem; color: var(--text-secondary); line-height: 1.6;">
            {{ $user->store->description ?? 'No business description provided.' }}
        </p>
    </div>
    @endif

    <!-- Personal Notes/Bio -->
    @if($user->userProfile && $user->userProfile->bio)
    <div style="margin-top: 1.5rem; background: white; border: 1px solid var(--border-color); border-radius: 1rem; padding: 1.25rem;">
        <h4 style="margin-bottom: 0.75rem; font-size: 0.8125rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase;">User Biography</h4>
        <div style="font-size: 0.875rem; color: var(--text-primary); line-height: 1.6;">
            {{ $user->userProfile->bio }}
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div style="margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 1rem;">
        <button onclick="closeModal()" class="btn" style="background: #f1f5f9; color: var(--text-secondary); font-weight: 600;">Dismiss</button>
        <a href="{{ route('admin.customers.edit', $user->id) }}" class="btn btn-primary" style="padding: 0.625rem 1.5rem;">
            <i class="fas fa-edit" style="margin-right: 0.5rem;"></i> Edit Profile
        </a>
    </div>
</div>
