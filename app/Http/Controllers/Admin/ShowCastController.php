<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ShowCastController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->input('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $people = Person::where('name', 'like', "%{$q}%")
            ->orderBy('name')
            ->limit(15)
            ->get(['id', 'name', 'photo', 'photo_local']);

        return response()->json($people->map(fn($p) => [
            'id'        => $p->id,
            'name'      => $p->name,
            'photo_url' => $p->photo_url,
        ]));
    }

    public function store(Request $request, Show $show)
    {
        $data = $request->validate([
            'person_id'       => 'nullable|integer|exists:people,id',
            'person_name'     => 'required_without:person_id|nullable|string|max:255',
            'person_photo'    => 'nullable|string|max:500',
            'person_photo_file' => 'nullable|image|max:4096',
            'character_name'  => 'nullable|string|max:255',
            'sort_order'      => 'nullable|integer|min:0',
        ]);

        if (empty($data['person_id'])) {
            $person = Person::create([
                'name'  => $data['person_name'],
                'photo' => $data['person_photo'] ?? null,
            ]);
        } else {
            $person = Person::findOrFail($data['person_id']);
        }

        if ($request->hasFile('person_photo_file')) {
            $person->photo_local = $this->uploadPhoto($request->file('person_photo_file'), $person->id);
            $person->save();
        }

        $maxOrder = DB::table('show_person')
            ->where('show_id', $show->id)
            ->where('department', 'cast')
            ->max('sort_order') ?? -1;

        DB::table('show_person')->insertOrIgnore([
            'show_id'        => $show->id,
            'person_id'      => $person->id,
            'department'     => 'cast',
            'character_name' => $data['character_name'] ?? null,
            'sort_order'     => $data['sort_order'] ?? $maxOrder + 1,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return back()->with('cast_success', "\"{$person->name}\" added to cast.");
    }

    public function update(Request $request, Show $show, int $entry)
    {
        $data = $request->validate([
            'person_name'       => 'required|string|max:255',
            'person_photo'      => 'nullable|string|max:500',
            'person_photo_file' => 'nullable|image|max:4096',
            'character_name'    => 'nullable|string|max:255',
            'sort_order'        => 'nullable|integer|min:0',
        ]);

        $pivot  = DB::table('show_person')->where('id', $entry)->where('show_id', $show->id)->firstOrFail();
        $person = Person::findOrFail($pivot->person_id);

        $personData = [
            'name'  => $data['person_name'],
            'photo' => $data['person_photo'] ?: $person->getRawOriginal('photo'),
        ];

        if ($request->hasFile('person_photo_file')) {
            // Delete old local file if present
            if ($person->photo_local) {
                Storage::disk('public')->delete($person->photo_local);
            }
            $personData['photo_local'] = $this->uploadPhoto($request->file('person_photo_file'), $person->id);
        }

        $person->update($personData);

        DB::table('show_person')->where('id', $entry)->update([
            'character_name' => $data['character_name'] ?? null,
            'sort_order'     => $data['sort_order'] ?? $pivot->sort_order,
            'updated_at'     => now(),
        ]);

        return back()->with('cast_success', 'Cast entry updated.');
    }

    public function destroy(Show $show, int $entry)
    {
        DB::table('show_person')->where('id', $entry)->where('show_id', $show->id)->delete();

        return back()->with('cast_success', 'Cast member removed.');
    }

    private function uploadPhoto($file, int $personId): string
    {
        return $file->store("people/{$personId}", 'public');
    }
}
