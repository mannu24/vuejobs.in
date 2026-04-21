<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::query()
            ->where('author_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blogs.form', ['blog' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'hero_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'tags' => 'nullable|string',
            'status' => 'required|in:draft,published',
        ]);

        $data['author_id'] = auth()->id();
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(8);
        $data['tags'] = $this->parseTags($data['tags'] ?? '');
        $data['published_at'] = $data['status'] === 'published' ? now() : null;
        $data['hero_image'] = $this->uploadImage($request);

        Blog::create($data);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog created.');
    }

    public function edit(Blog $blog)
    {
        $this->ensureAuthor($blog);

        return view('admin.blogs.form', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $this->ensureAuthor($blog);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'hero_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'tags' => 'nullable|string',
            'status' => 'required|in:draft,published',
        ]);

        $data['tags'] = $this->parseTags($data['tags'] ?? '');

        if ($data['status'] === 'published' && $blog->status !== 'published') {
            $data['published_at'] = now();
        }

        if ($request->hasFile('hero_image')) {
            $this->deleteOldImage($blog);
            $data['hero_image'] = $this->uploadImage($request);
        } elseif ($request->boolean('remove_hero_image')) {
            $this->deleteOldImage($blog);
            $data['hero_image'] = null;
        } else {
            unset($data['hero_image']);
        }

        $blog->update($data);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated.');
    }

    public function destroy(Blog $blog)
    {
        $this->ensureAuthor($blog);

        $this->deleteOldImage($blog);
        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog deleted.');
    }

    private function uploadImage(Request $request): ?string
    {
        if (! $request->hasFile('hero_image')) {
            return null;
        }

        $path = $request->file('hero_image')->store('blogs', 'public');

        return '/storage/' . $path;
    }

    private function deleteOldImage(Blog $blog): void
    {
        if (! $blog->hero_image) {
            return;
        }

        $path = str_replace('/storage/', '', $blog->hero_image);
        Storage::disk('public')->delete($path);
    }

    private function parseTags(?string $tags): array
    {
        if (! $tags) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $tags))));
    }

    private function ensureAuthor(Blog $blog): void
    {
        abort_unless($blog->author_id === auth()->id(), 403);
    }
}
