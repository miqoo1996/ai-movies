<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EpisodeController extends Controller
{
    public function index(Show $show)
    {
        $seasons = $show->episodes()
            ->orderBy('season_number')
            ->orderBy('episode_number')
            ->get()
            ->groupBy('season_number');

        return view('admin.episodes.index', compact('show', 'seasons'));
    }

    public function create(Show $show)
    {
        $nextSeason  = $show->episodes()->max('season_number') ?? 1;
        $nextEpisode = ($show->episodes()->where('season_number', $nextSeason)->max('episode_number') ?? 0) + 1;

        return view('admin.episodes.create', compact('show', 'nextSeason', 'nextEpisode'));
    }

    public function store(Request $request, Show $show)
    {
        $data = $this->validated($request);
        $data['show_id'] = $show->id;

        $episode = Episode::create($data);

        if ($request->hasFile('thumb_file')) {
            $episode->thumb_local = $this->uploadThumb($request->file('thumb_file'), $show->id, $episode->id);
            $episode->save();
        }

        return redirect()->route('admin.shows.episodes.index', $show)
            ->with('success', "S{$episode->season_number}E{$episode->episode_number} added.");
    }

    public function edit(Show $show, Episode $episode)
    {
        return view('admin.episodes.edit', compact('show', 'episode'));
    }

    public function update(Request $request, Show $show, Episode $episode)
    {
        $episode->update($this->validated($request));

        if ($request->hasFile('thumb_file')) {
            if ($episode->thumb_local) {
                Storage::disk('public')->delete($episode->thumb_local);
            }
            $episode->thumb_local = $this->uploadThumb($request->file('thumb_file'), $show->id, $episode->id);
            $episode->save();
        }

        return redirect()->route('admin.shows.episodes.index', $show)
            ->with('success', "S{$episode->season_number}E{$episode->episode_number} updated.");
    }

    public function destroy(Show $show, Episode $episode)
    {
        $label = "S{$episode->season_number}E{$episode->episode_number}";
        $episode->delete();

        return redirect()->route('admin.shows.episodes.index', $show)
            ->with('success', "{$label} deleted.");
    }

    private function uploadThumb($file, int $showId, int $episodeId): string
    {
        return $file->storeAs(
            "episode-thumbs/{$showId}",
            "{$episodeId}." . $file->getClientOriginalExtension(),
            'public'
        );
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'season_number'  => 'required|integer|min:1',
            'episode_number' => 'required|integer|min:1',
            'title'          => 'nullable|string|max:255',
            'overview'       => 'nullable|string',
            'airs_on'        => 'nullable|date',
            'has_aired'      => 'boolean',
            'season_finale'  => 'boolean',
            'thumb'          => 'nullable|string|max:500',
            'thumb_file'     => 'nullable|image|max:4096',
        ]);
    }
}
