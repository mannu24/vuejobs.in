@extends('admin.layouts.app')
@section('title', 'All Blogs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Blogs</h4>
    <a href="{{ route('admin.blogs.create') }}" class="btn btn-vue btn-sm">
        <i class="bi bi-plus-lg me-1"></i> New Blog
    </a>
</div>

<div class="card border-0 shadow-sm">
    @if($blogs->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="min-width:280px;">Title</th>
                        <th style="width:100px;">Status</th>
                        <th style="width:140px;" class="d-none d-md-table-cell">Published</th>
                        <th style="width:140px;" class="d-none d-md-table-cell">Updated</th>
                        <th style="width:120px;" class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($blogs as $blog)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-medium">{{ $blog->title }}</div>
                            <small class="text-muted">{{ $blog->slug }}</small>
                        </td>
                        <td>
                            @if($blog->status === 'published')
                                <span class="badge bg-success-subtle text-success">Published</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning">Draft</span>
                            @endif
                        </td>
                        <td class="d-none d-md-table-cell">
                            <small class="text-muted">{{ $blog->published_at?->format('M d, Y') ?? '—' }}</small>
                        </td>
                        <td class="d-none d-md-table-cell">
                            <small class="text-muted">{{ $blog->updated_at->format('M d, Y') }}</small>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn btn-sm btn-outline-secondary me-1" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" class="d-inline" onsubmit="return confirm('Delete this blog?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($blogs->hasPages())
            <div class="card-footer bg-white border-top">
                {{ $blogs->links() }}
            </div>
        @endif
    @else
        <div class="card-body text-center py-5">
            <i class="bi bi-journal-text text-muted" style="font-size:2rem;"></i>
            <p class="text-muted mt-2 mb-3">No blogs yet</p>
            <a href="{{ route('admin.blogs.create') }}" class="btn btn-vue btn-sm">Create your first blog</a>
        </div>
    @endif
</div>
@endsection
