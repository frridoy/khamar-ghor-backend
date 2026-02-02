<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border-color);">
    <div style="display: flex; align-items: center; gap: 1.25rem;">
        <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%); color: white; border-radius: 1rem; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; font-weight: 700; box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.3);">
            <i class="fas fa-store"></i>
        </div>
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">{{ $store->name }}</h2>
            <p style="color: var(--text-secondary); font-size: 0.875rem;">Store ID: #{{ $store->id }}</p>
        </div>
    </div>
    <button onclick="closeModal()" class="btn" style="background: #f1f5f9; color: var(--text-secondary); border: none; font-size: 1.25rem; padding: 0.5rem 0.75rem;">
        <i class="fas fa-times"></i>
    </button>
</div>

    <!-- Store Information -->
    <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-color);">
        <h3 style="font-size: 0.875rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 1.5rem;">
            <i class="fas fa-info-circle"></i> Store Information
        </h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
            <div>
                <span style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700;">Store Name</span>
                <div style="font-size: 0.9375rem; color: var(--text-primary); font-weight: 600; margin-top: 0.5rem;">{{ $store->name }}</div>
            </div>
            <div>
                <span style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700;">Phone</span>
                <div style="font-size: 0.9375rem; color: var(--text-primary); font-weight: 600; margin-top: 0.5rem;">{{ $store->phone ?? 'N/A' }}</div>
            </div>
            <div>
                <span style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700;">Email</span>
                <div style="font-size: 0.9375rem; color: var(--text-primary); font-weight: 600; margin-top: 0.5rem;">{{ $store->email ?? 'N/A' }}</div>
            </div>
            <div>
                <span style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700;">Registration Date</span>
                <div style="font-size: 0.9375rem; color: var(--text-primary); font-weight: 600; margin-top: 0.5rem;">{{ $store->created_at?->format('d M Y') ?? 'N/A' }}</div>
            </div>
            <div style="grid-column: span 2;">
                <span style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700;">Address</span>
                <div style="font-size: 0.9375rem; color: var(--text-primary); font-weight: 500; margin-top: 0.5rem;">{{ $store->address ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Owner Information -->
    <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-color);">
        <h3 style="font-size: 0.875rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 1.5rem;">
            <i class="fas fa-user"></i> Owner Information
        </h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
            <div>
                <span style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700;">Owner Name</span>
                <div style="font-size: 0.9375rem; color: var(--text-primary); font-weight: 600; margin-top: 0.5rem;">{{ $store->user->name }}</div>
            </div>
            <div>
                <span style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700;">User Code</span>
                <div style="font-size: 0.9375rem; color: var(--text-primary); font-weight: 600; margin-top: 0.5rem;"><code>{{ $store->user->code ?? 'N/A' }}</code></div>
            </div>
            <div>
                <span style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700;">Email</span>
                <div style="font-size: 0.9375rem; color: var(--text-primary); font-weight: 600; margin-top: 0.5rem;">{{ $store->user->email }}</div>
            </div>
            <div>
                <span style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700;">Phone</span>
                <div style="font-size: 0.9375rem; color: var(--text-primary); font-weight: 600; margin-top: 0.5rem;">{{ $store->user->phone ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Location Information -->
    <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-color);">
        <h3 style="font-size: 0.875rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 1.5rem;">
            <i class="fas fa-map-marker-alt"></i> Location Information
        </h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
            <div>
                <span style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700;">Country</span>
                <div style="font-size: 0.9375rem; color: var(--text-primary); font-weight: 600; margin-top: 0.5rem;">{{ $store->country->name_en ?? 'N/A' }}</div>
            </div>
            <div>
                <span style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700;">Division</span>
                <div style="font-size: 0.9375rem; color: var(--text-primary); font-weight: 600; margin-top: 0.5rem;">{{ $store->division->name_en ?? 'N/A' }}</div>
            </div>
            <div>
                <span style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700;">District</span>
                <div style="font-size: 0.9375rem; color: var(--text-primary); font-weight: 600; margin-top: 0.5rem;">{{ $store->district->name_en ?? 'N/A' }}</div>
            </div>
            <div>
                <span style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700;">Thana</span>
                <div style="font-size: 0.9375rem; color: var(--text-primary); font-weight: 600; margin-top: 0.5rem;">{{ $store->thana->name_en ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Media Section -->
    <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-color);">
        <h3 style="font-size: 0.875rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 1.5rem;">
            <i class="fas fa-images"></i> Media
        </h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
            <div>
                <span style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700;">Store Logo</span>
                @if($store->logo)
                    <div style="margin-top: 0.5rem;">
                        <img src="{{ asset('storage/' . $store->logo) }}" alt="Store Logo" style="max-width: 100%; height: auto; max-height: 200px; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                    </div>
                @else
                    <div style="font-size: 0.9375rem; color: var(--text-secondary); margin-top: 0.5rem;">No logo uploaded</div>
                @endif
            </div>
            <div>
                <span style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700;">Cover Image</span>
                @if($store->cover_image)
                    <div style="margin-top: 0.5rem;">
                        <img src="{{ asset('storage/' . $store->cover_image) }}" alt="Cover Image" style="max-width: 100%; height: auto; max-height: 200px; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                    </div>
                @else
                    <div style="font-size: 0.9375rem; color: var(--text-secondary); margin-top: 0.5rem;">No cover image uploaded</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Verification Status -->
    <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-color);">
        <h3 style="font-size: 0.875rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 1.5rem;">
            <i class="fas fa-shield-alt"></i> Verification Status
        </h3>
        <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8fafc; border-radius: 0.5rem;">
            <div>
                @if($store->is_verified == 1)
                    <span class="badge badge-success" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                        <i class="fas fa-check-circle"></i> Verified
                    </span>
                    <p style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.5rem;">This store is verified and approved.</p>
                @else
                    <span class="badge badge-warning" style="background: #fef3c7; color: #92400e; font-size: 0.875rem; padding: 0.5rem 1rem;">
                        <i class="fas fa-hourglass-half"></i> Not Verified
                    </span>
                    <p style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.5rem;">This store is waiting for verification.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div style="padding: 1.5rem; display: flex; gap: 1rem; justify-content: flex-end; border-top: 1px solid var(--border-color);">
        <button onclick="closeModal()" class="btn" style="background: #f1f5f9; color: var(--text-secondary);">
            Close
        </button>
        @if($store->is_verified == 0)
            <button onclick="verifyStoreAction({{ $store->id }}, true)" class="btn btn-primary" style="box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.3);">
                <i class="fas fa-check"></i> Verify Store
            </button>
        @else
            <button onclick="verifyStoreAction({{ $store->id }}, false)" class="btn" style="background: #fee2e2; color: #991b1b;">
                <i class="fas fa-times"></i> Unverify Store
            </button>
        @endif
    </div>
</div>
