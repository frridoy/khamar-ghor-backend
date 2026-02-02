<div style="padding: 0.5rem;">
    <!-- Customer Header -->
    <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid var(--border-color);">
        <div style="width: 70px; height: 70px; background: linear-gradient(135deg, var(--primary-color) 0%, #4f46e5 100%); color: white; border-radius: 1.5rem; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 800; box-shadow: var(--shadow-md);">
            {{ substr($user->name, 0, 1) }}
        </div>
        <div style="flex: 1;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin-bottom: 0.25rem;">{{ $user->name }}</h2>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <span class="badge" style="background: #e0f2fe; color: #0369a1; font-size: 0.75rem;">{{ $user->role_name }}</span>
                        <span style="font-size: 0.875rem; color: var(--text-secondary);"><i class="fas fa-barcode"></i> {{ $user->code ?? 'NO-CODE' }}</span>
                    </div>
                </div>
                <div style="text-align: right;">
                    @if($user->is_active == 1)
                        <span class="badge badge-success">Active</span>
                    @elseif($user->is_active == 2)
                        <span class="badge badge-danger">Inactive</span>
                    @elseif($user->is_active == 3)
                        <span class="badge badge-warning" style="background: #fef3c7; color: #92400e;">Suspended</span>
                    @else
                        <span class="badge badge-secondary">Unknown</span>
                    @endif
                    <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.5rem;">Joined {{ $user->created_at->format('d M Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Contact Information -->
        <div style="background: #f8fafc; padding: 1.25rem; border-radius: 0.75rem; border: 1px solid var(--border-color);">
            <h3 style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-user-circle" style="color: var(--primary-color);"></i> Personal Details
            </h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--text-secondary); font-size: 0.875rem;">Email</span>
                    <span style="font-weight: 600; font-size: 0.875rem; color: var(--text-primary);">{{ $user->email }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--text-secondary); font-size: 0.875rem;">Phone</span>
                    <span style="font-weight: 600; font-size: 0.875rem; color: var(--text-primary);">{{ $user->phone_number }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--text-secondary); font-size: 0.875rem;">Profile Status</span>
                    <span style="font-weight: 600; font-size: 0.875rem; color: {{ $user->is_profile_completed ? 'var(--success-color)' : 'var(--warning-color)' }}">
                        {{ $user->is_profile_completed ? 'Completed' : 'Pending' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Bio or Notes -->
        <div style="background: #f8fafc; padding: 1.25rem; border-radius: 0.75rem; border: 1px solid var(--border-color);">
            <h3 style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-info-circle" style="color: var(--primary-color);"></i> Biography
            </h3>
            <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.6; margin: 0;">
                {{ $user->userProfile->bio ?? 'No biography details provided by this user.' }}
            </p>
        </div>
    </div>

    <!-- Company/Store Info Section -->
    <div style="margin-top: 1rem; padding: 1.75rem; background: #ffffff; border: 1px dashed #cbd5e1; border-radius: 1.25rem; position: relative;">
        <div style="position: absolute; top: -12px; left: 24px; background: white; padding: 0 10px; font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em;">
            <i class="fas fa-building" style="margin-right: 4px;"></i> Company Info
        </div>
        
        @if($user->store)
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div style="grid-column: span 2;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                        @if($user->store->logo)
                            <img src="{{ asset('storage/'.$user->store->logo) }}" style="width: 50px; height: 50px; border-radius: 0.5rem; object-fit: cover;">
                        @else
                            <div style="width: 50px; height: 50px; background: #f1f5f9; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                                <i class="fas fa-store fa-lg"></i>
                            </div>
                        @endif
                        <div>
                            <div style="font-size: 1.25rem; font-weight: 800; color: var(--primary-color);">{{ $user->store->name }}</div>
                            <div style="font-size: 0.875rem; color: var(--text-secondary);">Managed by {{ $user->name }}</div>
                        </div>
                    </div>
                </div>

                <div style="grid-column: span 2;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase;">Overview</label>
                    <p style="font-size: 0.9375rem; color: var(--text-primary); line-height: 1.7;">
                        {{ $user->store->description ?? 'No business description provided.' }}
                    </p>
                </div>

                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase;">Business Contact</label>
                    <div style="font-size: 0.875rem; color: var(--text-primary); display: flex; flex-direction: column; gap: 0.5rem;">
                        <span><i class="fas fa-phone-alt" style="width: 20px; color: #94a3b8;"></i> {{ $user->store->phone ?? 'N/A' }}</span>
                        <span><i class="fas fa-envelope" style="width: 20px; color: #94a3b8;"></i> {{ $user->store->email ?? 'N/A' }}</span>
                        @if($user->store->website)
                            <a href="{{ $user->store->website }}" target="_blank" style="color: var(--primary-color); text-decoration: none;"><i class="fas fa-globe" style="width: 20px; color: #94a3b8;"></i> Website</a>
                        @endif
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase;">Location</label>
                    <div style="font-size: 0.875rem; color: var(--text-primary); line-height: 1.5;">
                        <i class="fas fa-map-marker-alt" style="width: 20px; color: #94a3b8;"></i>
                        {{ $user->store->address ?? 'Address not specified' }}<br>
                        <span style="margin-left: 20px;">{{ $user->store->area_name }}{{ $user->store->postal_code ? ', '.$user->store->postal_code : '' }}</span>
                    </div>
                </div>
            </div>
        @else
            <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                <i class="fas fa-store-slash" style="font-size: 2.5rem; display: block; margin-bottom: 1rem; opacity: 0.3;"></i>
                <div style="font-weight: 600;">No Store profile found</div>
                <div style="font-size: 0.875rem; margin-top: 0.25rem;">This customer hasn't registered their business info yet.</div>
            </div>
        @endif
    </div>

    <div style="margin-top: 3rem; display: flex; justify-content: flex-end; gap: 1rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
        <button onclick="closeModal()" class="btn" style="background: #f1f5f9; color: var(--text-secondary); font-weight: 700; padding: 0.75rem 1.5rem;">CLOSE</button>
        <a href="{{ route('admin.customers.edit', $user->id) }}" class="btn btn-primary" style="padding: 0.75rem 2rem; font-weight: 700; box-shadow: var(--shadow-md);">
            <i class="fas fa-user-edit" style="margin-right: 0.5rem;"></i> MODIFY CUSTOMER
        </a>
    </div>
</div>
