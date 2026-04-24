<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Admin: list all blogs (drafts + published).
     */
    public function index(Request $request)
    {
        $blogs = Blog::query()
            ->where('author_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return BlogResource::collection($blogs);
    }

    /**
     * Admin: create a new blog post.
     */
    public function store(BlogRequest $request): JsonResponse
    {
        $data = $request->validated();

        $blog = Blog::create(array_merge($data, [
            'author_id' => $request->user()->id,
            'slug' => Str::slug($data['title']) . '-' . Str::random(8),
            'published_at' => ($data['status'] ?? 'draft') === 'published' ? now() : null,
        ]));

        Cache::flush();

        return response()->json([
            'blog' => new BlogResource($blog),
            'message' => 'Blog created',
        ], 201);
    }

    /**
     * Admin: show a single blog (any status).
     */
    public function show(Blog $blog): BlogResource
    {
        $this->ensureAuthor($blog);

        return new BlogResource($blog->load('author'));
    }

    /**
     * Admin: update a blog post.
     */
    public function update(BlogRequest $request, Blog $blog): JsonResponse
    {
        $this->ensureAuthor($blog);

        $data = $request->validated();

        // Set published_at when transitioning to published
        if (($data['status'] ?? null) === 'published' && $blog->status !== 'published') {
            $data['published_at'] = now();
        }

        $blog->update($data);

        Cache::flush();

        return response()->json([
            'blog' => new BlogResource($blog->fresh('author')),
            'message' => 'Blog updated',
        ]);
    }

    /**
     * Admin: delete a blog post.
     */
    public function destroy(Blog $blog): JsonResponse
    {
        $this->ensureAuthor($blog);

        $blog->delete();

        Cache::flush();

        return response()->json(['message' => 'Blog deleted']);
    }

    /**
     * Public: list all published blogs.
     */
    public function publicIndex(Request $request)
    {
        $cacheKey = 'blogs:public:' . md5($request->fullUrl());

        return Cache::remember($cacheKey, 300, function () use ($request) {
            $query = Blog::query()
                ->published()
                ->with('author');

            if ($tag = $request->string('tag')->toString()) {
                $query->whereJsonContains('tags', $tag);
            }

            $blogs = $query->latest('published_at')->paginate(12);

            return BlogResource::collection($blogs);
        });
    }

    /**
     * Public: show a single published blog by slug.
     */
    public function showPublic(Blog $blog): BlogResource
    {
        abort_unless($blog->status === 'published', 404);

        return Cache::remember("blogs:show:{$blog->id}", 300, function () use ($blog) {
            return new BlogResource($blog->load('author'));
        });
    }

    protected function ensureAuthor(Blog $blog): void
    {
        abort_unless($blog->author_id === request()->user()->id, 403);
    }
}
