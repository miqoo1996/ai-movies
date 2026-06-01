<?php

namespace App\Http\Controllers;

use App\Models\Person;

class PersonController extends Controller
{
    public function show(Person $person)
    {
        $person->load([
            'shows' => fn($q) => $q->with('genres')
                ->orderBy('subscribers', 'desc'),
        ]);

        return view('people.show', compact('person'));
    }
}
