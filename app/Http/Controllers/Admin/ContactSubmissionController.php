<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;

class ContactSubmissionController extends Controller
{
    public function index()
    {
        $submissions = ContactSubmission::latest()->paginate(30);
        return view('admin.contact.index', compact('submissions'));
    }

    public function show(ContactSubmission $submission)
    {
        if ($submission->isUnread()) {
            $submission->update(['read_at' => now()]);
        }
        return view('admin.contact.show', compact('submission'));
    }

    public function markRead(ContactSubmission $submission)
    {
        $submission->update(['read_at' => now()]);
        return back();
    }

    public function destroy(ContactSubmission $submission)
    {
        $submission->delete();
        return redirect()->route('admin.contact.index')->with('success', 'Message deleted.');
    }
}
