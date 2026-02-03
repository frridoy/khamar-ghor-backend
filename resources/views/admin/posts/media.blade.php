@extends('admin.layouts.app')

@section('title', 'Post Media')
@section('page-title', 'Post Media')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="font-size: 1.25rem; font-weight: 700;">Post Media List</h2>
    </div>

    <!-- Filters Section -->
    <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.75rem; margin-bottom: 2rem; border: 1px solid var(--border-color);">
        <form action="{{ route('admin.posts.media') }}" method="GET" id="filter-form" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.75rem;">Search</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Post, User or Store..." style="padding: 0.5rem 0.75rem;">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.75rem;">Show Rows</label>
                <select name="per_page" class="form-control" style="padding: 0.5rem 0.75rem;">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 Rows</option>
                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20 Rows</option>
                    <option value="30" {{ $perPage == 30 ? 'selected' : '' }}>30 Rows</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 Rows</option>
                </select>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem;">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('admin.posts.media') }}" class="btn" style="background: white; border: 1px solid #e2e8f0; color: var(--text-secondary); padding: 0.5rem 1rem;">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Table View -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Post Info</th>
                    <th>User & Store</th>
                    <th>Images (Max 3)</th>
                    <th>Video</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr>
                    <td style="width: 250px;">
                        <div style="font-weight: 700; color: var(--text-primary); font-size: 0.9375rem;">{{ $post->title }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">SLUG: /{{ $post->slug }}</div>
                        <div style="margin-top: 5px;">
                            <span class="badge" style="background: #eef2ff; color: var(--primary-color);">{{ $post->category->name ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: var(--text-primary);">{{ $post->user->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--primary-color);">
                            <i class="fas fa-store" style="opacity: 0.7;"></i> {{ $post->store->name }}
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            @php
                                $images = $post->media->where('type', 'image')->take(3);
                            @endphp
                            @forelse($images as $image)
                                <div style="width: 60px; height: 60px; border-radius: 4px; overflow: hidden; border: 1px solid #eee; cursor: pointer;" 
                                     onclick="previewImage('{{ asset('storage/' . $image->file_path) }}')">
                                    <img src="{{ asset('storage/' . $image->file_path) }}" alt="Post Image" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            @empty
                                <span style="color: #ccc; font-size: 0.75rem;">No images</span>
                            @endforelse
                        </div>
                    </td>
                    <td>
                        @php
                            $video = $post->media->where('type', 'video')->first();
                        @endphp
                        @if($video)
                            <div style="width: 100px; height: 60px; border-radius: 4px; overflow: hidden; border: 1px solid #eee; position: relative; background: #000; cursor: pointer;" onclick="previewVideo('{{ asset('storage/' . $video->file_path) }}')">
                                <video style="width: 100%; height: 100%; object-fit: cover;">
                                    <source src="{{ asset('storage/' . $video->file_path) }}">
                                </video>
                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; opacity: 0.8;">
                                    <i class="fas fa-play-circle" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        @else
                            <span style="color: #ccc; font-size: 0.75rem;">No video</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <button onclick="openModal('{{ route('admin.posts.show', $post->id) }}')" class="btn btn-sm" style="background: #f8fafc; color: var(--text-primary); border: 1px solid #e2e8f0;" title="View Details">
                            <i class="fas fa-eye"></i> View
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 4rem; color: var(--text-secondary);">
                        <i class="fas fa-photo-video" style="font-size: 2.5rem; display: block; margin-bottom: 1rem; opacity: 0.2;"></i>
                        No posts with media found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-size: 0.875rem; color: var(--text-secondary);">
            Showing {{ $posts->firstItem() ?? 0 }} to {{ $posts->lastItem() ?? 0 }} of {{ $posts->total() }} results
        </div>
        <div>
            {{ $posts->links() }}
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imagePreviewModal" class="image-preview-modal">
    <span class="close-preview" onclick="closeImagePreview()">&times;</span>
    <div class="preview-content-container">
        <img id="previewImg" src="" alt="Preview">
        <div class="preview-actions">
            <a id="downloadBtn" href="" download class="btn btn-primary">
                <i class="fas fa-download"></i> Download Image
            </a>
        </div>
    </div>
</div>

<!-- Video Preview Modal -->
<div id="videoPreviewModal" class="image-preview-modal">
    <span class="close-preview" onclick="closeVideoPreview()">&times;</span>
    <div class="preview-content-container">
        <video id="previewVid" controls style="max-width: 100%; max-height: 80vh;">
            <source src="" type="video/mp4">
        </video>
        <div class="preview-actions">
            <a id="downloadVideoBtn" href="" download class="btn btn-primary">
                <i class="fas fa-download"></i> Download Video
            </a>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function previewImage(src) {
        const modal = document.getElementById('imagePreviewModal');
        const img = document.getElementById('previewImg');
        const downloadBtn = document.getElementById('downloadBtn');
        
        img.src = src;
        downloadBtn.href = src;
        modal.style.display = 'flex';
    }

    function closeImagePreview() {
        document.getElementById('imagePreviewModal').style.display = 'none';
    }

    function previewVideo(src) {
        const modal = document.getElementById('videoPreviewModal');
        const video = document.getElementById('previewVid');
        const downloadBtn = document.getElementById('downloadVideoBtn');
        
        video.src = src;
        downloadBtn.href = src;
        modal.style.display = 'flex';
        video.play();
    }

    function closeVideoPreview() {
        const modal = document.getElementById('videoPreviewModal');
        const video = document.getElementById('previewVid');
        video.pause();
        modal.style.display = 'none';
    }

    // Close modals on clicking outside or escape
    window.onclick = function(event) {
        const imgModal = document.getElementById('imagePreviewModal');
        const vidModal = document.getElementById('videoPreviewModal');
        if (event.target == imgModal) {
            closeImagePreview();
        }
        if (event.target == vidModal) {
            closeVideoPreview();
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeImagePreview();
            closeVideoPreview();
        }
    });
</script>
@endsection

@section('styles')
<style>
    .pagination { display: flex; list-style: none; gap: 0.25rem; padding: 0; }
    .page-item {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 32px; height: 32px; padding: 0 0.5rem; border: 1px solid #e2e8f0;
        border-radius: 0.375rem; text-decoration: none; color: var(--text-secondary);
        font-size: 0.875rem; transition: all 0.2s;
    }
    .page-item:hover:not(.disabled) { background-color: #f1f5f9; border-color: #cbd5e1; }
    .page-item.active { background-color: var(--primary-color); color: white; border-color: var(--primary-color); }
    .page-item.disabled { opacity: 0.5; cursor: not-allowed; }
    
    .table-container th {
        background: #f8fafc;
        padding: 1rem;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border-color);
    }
    
    .table-container td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
    }

    /* Preview Modal Styles */
    .image-preview-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.9);
        backdrop-filter: blur(5px);
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }

    .preview-content-container {
        text-align: center;
        max-width: 90%;
        max-height: 90%;
    }

    .preview-content-container img {
        max-width: 100%;
        max-height: 80vh;
        border-radius: 8px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .preview-actions {
        margin-top: 20px;
    }

    .close-preview {
        position: absolute;
        top: 20px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        cursor: pointer;
    }

    .close-preview:hover,
    .close-preview:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }
</style>
@endsection
