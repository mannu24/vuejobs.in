@extends('admin.layouts.app')
@section('title', $blog ? 'Edit Blog' : 'New Blog')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">{{ $blog ? 'Edit Blog' : 'New Blog' }}</h4>
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<form method="POST" action="{{ $blog ? route('admin.blogs.update', $blog) : route('admin.blogs.store') }}" enctype="multipart/form-data">
    @csrf
    @if($blog) @method('PUT') @endif

    <div class="row g-4">
        <!-- Main column -->
        <div class="col-lg-8">
            <!-- Title -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-medium">Title</label>
                        <input
                            id="title" name="title" type="text"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title', $blog?->title) }}" required
                            placeholder="How to Build a Vue.js Portfolio"
                        >
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Content with TinyMCE -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-medium">Content</h6>
                </div>
                <div class="card-body">
                    <textarea id="content" name="content">{{ old('content', $blog?->content) }}</textarea>
                    @error('content') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- SEO -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-medium"><i class="bi bi-search me-1"></i> SEO</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label small fw-medium">Meta Title</label>
                        <input
                            id="meta_title" name="meta_title" type="text"
                            class="form-control @error('meta_title') is-invalid @enderror"
                            value="{{ old('meta_title', $blog?->meta_title) }}"
                            placeholder="SEO title (defaults to blog title)"
                        >
                        @error('meta_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label for="meta_description" class="form-label small fw-medium">Meta Description</label>
                        <textarea
                            id="meta_description" name="meta_description" rows="2"
                            class="form-control @error('meta_description') is-invalid @enderror"
                            placeholder="Brief description for search engines..."
                        >{{ old('meta_description', $blog?->meta_description) }}</textarea>
                        @error('meta_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar column -->
        <div class="col-lg-4">
            <!-- Publish -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-medium">Publish</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="status" class="form-label small fw-medium">Status</label>
                        <select id="status" name="status" class="form-select">
                            <option value="draft" {{ old('status', $blog?->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $blog?->status) === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>

                    @if($blog?->published_at)
                        <div class="mb-3">
                            <small class="text-muted">Published: {{ $blog->published_at->format('M d, Y \a\t h:i A') }}</small>
                        </div>
                    @endif

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-vue">
                            <i class="bi bi-check-lg me-1"></i>
                            {{ $blog ? 'Update Blog' : 'Create Blog' }}
                        </button>
                        <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>

            <!-- Hero Image -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-medium">Hero Image</h6>
                </div>
                <div class="card-body">
                    @if($blog?->hero_image)
                        <div class="mb-3 position-relative">
                            <img src="{{ $blog->hero_image }}" alt="Hero preview" class="img-fluid rounded" style="max-height:160px;object-fit:cover;width:100%;">
                            <div class="form-check mt-2">
                                <input type="checkbox" name="remove_hero_image" value="1" class="form-check-input" id="removeImage">
                                <label class="form-check-label small text-danger" for="removeImage">Remove image</label>
                            </div>
                        </div>
                    @endif
                    <input
                        id="hero_image" name="hero_image" type="file"
                        accept="image/jpeg,image/png,image/webp"
                        class="form-control @error('hero_image') is-invalid @enderror"
                    >
                    @error('hero_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <small class="text-muted">JPG, PNG or WebP. Max 2MB.</small>
                </div>
            </div>

            <!-- Tags -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-medium">Tags</h6>
                </div>
                <div class="card-body">
                    <input
                        id="tags" name="tags" type="text"
                        class="form-control"
                        value="{{ old('tags', $blog ? implode(', ', $blog->tags ?? []) : '') }}"
                        placeholder="vue, nuxt, career"
                    >
                    <small class="text-muted">Separate with commas</small>
                </div>
            </div>

            @if($blog)
            <!-- Info -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-medium">Info</h6>
                </div>
                <div class="card-body small text-muted">
                    <div class="mb-1"><strong>Slug:</strong> {{ $blog->slug }}</div>
                    <div class="mb-1"><strong>Created:</strong> {{ $blog->created_at->format('M d, Y') }}</div>
                    <div><strong>Updated:</strong> {{ $blog->updated_at->format('M d, Y') }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.6.1/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#content',
        height: 500,
        menubar: true,
        plugins: 'lists link image code table wordcount fullscreen preview autolink',
        toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright | bullist numlist | link image | code fullscreen',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; font-size: 15px; line-height: 1.7; }',
        branding: false,
        promotion: false,
        convert_urls: false,
        relative_urls: false,
    });
</script>
@endsection
