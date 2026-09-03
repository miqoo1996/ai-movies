<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $q      = $request->get('q', '');
        $status = $request->get('status', '');

        $articles = Article::query()
            ->when($q, fn ($qr) => $qr->where('title', 'like', "%{$q}%"))
            ->when($status === 'published', fn ($qr) => $qr->where('is_published', true))
            ->when($status === 'draft', fn ($qr) => $qr->where('is_published', false))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.articles.index', compact('articles', 'q', 'status'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $article = Article::create($data);

        if ($request->hasFile('cover_file')) {
            $article->cover_image = $this->uploadCover($request->file('cover_file'), $article->slug);
            $article->save();
        }

        return redirect()->route('admin.articles.edit', $article)
            ->with('success', "Article \"{$article->title}\" created.");
    }

    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $data = $this->validated($request, $article);

        if ($data['is_published'] && ! $article->published_at && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $article->update($data);

        if ($request->hasFile('cover_file')) {
            $this->deleteStoredCover($article);
            $article->cover_image = $this->uploadCover($request->file('cover_file'), $article->slug);
            $article->save();
        }

        return redirect()->route('admin.articles.edit', $article)
            ->with('success', 'Article updated.');
    }

    public function destroy(Article $article)
    {
        $title = $article->title;
        $this->deleteStoredCover($article);
        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', "Article \"{$title}\" deleted.");
    }

    private function uploadCover($file, string $slug): string
    {
        return $file->storeAs('articles', $slug . '-' . time() . '.' . $file->getClientOriginalExtension(), 'public');
    }

    private function deleteStoredCover(Article $article): void
    {
        if ($article->hasStoredCover()) {
            Storage::disk('public')->delete($article->cover_image);
        }
    }

    private function validated(Request $request, ?Article $article = null): array
    {
        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'slug'            => [
                'nullable',
                'string',
                'max:255',
                'not_regex:/\//',
                'unique:articles,slug' . ($article ? ",{$article->id}" : ''),
            ],
            'excerpt'         => 'nullable|string|max:500',
            'content'         => 'required|string',
            'cover_image'     => 'nullable|string|max:500',
            'cover_file'      => 'nullable|image|max:4096',
            'published_at'    => 'nullable|date',
            'is_published'    => 'nullable|boolean',
            'seo_title'       => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:320',
            'focus_keyword'   => 'nullable|string|max:255',
            'schema_markup'   => 'nullable|json',
            'noindex'         => 'nullable|boolean',
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['noindex']      = $request->boolean('noindex');

        // A newly uploaded file wins over the URL field; it is stored after save.
        unset($data['cover_file']);

        if (empty($data['slug'])) {
            $data['slug'] = $this->uniqueSlug($data['title'], $article?->id);
        }

        return $data;
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'article';
        $slug = $base;
        $i    = 2;

        while (
            Article::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
