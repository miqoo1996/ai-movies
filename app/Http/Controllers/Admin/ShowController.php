<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Show;
use App\Models\ShowImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShowController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q', '');

        $shows = Show::withCount('episodes')
            ->when($q, fn($query) => $query->where('title', 'like', "%{$q}%")
                ->orWhere('original_title', 'like', "%{$q}%"))
            ->orderBy('id', 'desc')
            ->paginate(25)
            ->withQueryString();

        return view('admin.shows.index', compact('shows', 'q'));
    }

    public function create()
    {
        $genres   = Genre::orderBy('name')->get();
        $networks = Show::distinct()->orderBy('network')->pluck('network')->filter()->values();
        $statuses = ['Running', 'Returning Series', 'Ended', 'Cancelled', 'Hiatus'];

        return view('admin.shows.create', compact('genres', 'networks', 'statuses'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $show = Show::create($data);
        $show->genres()->sync($request->input('genre_ids', []));

        // Handle poster upload
        if ($request->hasFile('poster_file')) {
            $show->poster_local = $this->uploadPoster($request->file('poster_file'), $show->slug);
            $show->save();
        }

        return redirect()->route('admin.shows.edit', $show)
            ->with('success', "Show \"{$show->title}\" created.");
    }

    public function edit(Show $show)
    {
        $genres   = Genre::orderBy('name')->get();
        $networks = Show::distinct()->orderBy('network')->pluck('network')->filter()->values();
        $statuses = ['Running', 'Returning Series', 'Ended', 'Cancelled', 'Hiatus'];
        $images   = $show->images()->latest()->get();

        return view('admin.shows.edit', compact('show', 'genres', 'networks', 'statuses', 'images'));
    }

    public function update(Request $request, Show $show)
    {
        $data = $this->validated($request, $show);
        $show->update($data);
        $show->genres()->sync($request->input('genre_ids', []));

        // Handle poster upload
        if ($request->hasFile('poster_file')) {
            // Delete old local poster
            if ($show->poster_local && Storage::disk('public')->exists($show->poster_local)) {
                Storage::disk('public')->delete($show->poster_local);
            }
            $show->poster_local = $this->uploadPoster($request->file('poster_file'), $show->slug);
            $show->save();
        }

        // Handle gallery image uploads
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                $path = $file->store("show-images/{$show->id}", 'public');
                ShowImage::create([
                    'show_id'    => $show->id,
                    'url'        => asset('storage/' . $path),
                    'local_path' => $path,
                    'mime_type'  => $file->getMimeType(),
                    'width'      => null,
                    'height'     => null,
                    'collection' => 'gallery',
                ]);
            }
        }

        return redirect()->route('admin.shows.edit', $show)
            ->with('success', "Show updated.");
    }

    public function destroy(Show $show)
    {
        $title = $show->title;
        // Clean up local files
        if ($show->poster_local) Storage::disk('public')->delete($show->poster_local);
        $show->images->each(fn($img) => $img->local_path && Storage::disk('public')->delete($img->local_path));
        $show->delete();

        return redirect()->route('admin.shows.index')
            ->with('success', "Show \"{$title}\" deleted.");
    }

    // Delete a single gallery image
    public function destroyImage(Show $show, ShowImage $image)
    {
        if ($image->local_path) Storage::disk('public')->delete($image->local_path);
        $image->delete();

        return back()->with('success', 'Image deleted.');
    }

    private function uploadPoster($file, string $slug): string
    {
        return $file->storeAs('posters', $slug . '.' . $file->getClientOriginalExtension(), 'public');
    }

    private function validated(Request $request, ?Show $show = null): array
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'original_title'   => 'nullable|string|max:255',
            'turkish_title'    => 'nullable|string|max:255',
            'ai_title'         => 'nullable|string|max:255',
            'ai_turkish_title' => 'nullable|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:shows,slug' . ($show ? ",{$show->id}" : ''),
            'status'           => 'nullable|string|max:50',
            'network'          => 'nullable|string|max:100',
            'runtime'          => 'nullable|integer|min:1',
            'premiered'        => 'nullable|date',
            'year'             => 'nullable|integer|min:1900|max:2100',
            'synopsis'         => 'nullable|string',
            'ai_synopsis'      => 'nullable|string',
            'rating'           => 'nullable|numeric|min:0|max:10',
            'subscribers'      => 'nullable|integer|min:0',
            'poster'           => 'nullable|string|max:500',
            'poster_file'      => 'nullable|image|max:4096',
            'gallery_files.*'  => 'nullable|image|max:4096',
        ]);

        if (empty($data['slug'])) {
            $base = Str::slug($data['title']);
            $slug = $base;
            $i    = 1;
            while (Show::where('slug', $slug)->when($show, fn($q) => $q->where('id', '!=', $show->id))->exists()) {
                $slug = "{$base}-{$i}";
                $i++;
            }
            $data['slug'] = $slug;
        }

        return $data;
    }
}
