<?php

namespace App\Http\Controllers;

use App\Models\Page;

class MainController extends Controller
{
    public function faq()     { return view('faq'); }
    public function contact()
    {
        $page = Page::where('slug', 'contact')->firstOrFail();
        return view('contact', compact('page'));
    }

    public function terms()
    {
        $page = Page::where('slug', 'terms')->firstOrFail();
        return view('terms', compact('page'));
    }

    public function privacy()
    {
        $page = Page::where('slug', 'privacy')->firstOrFail();
        return view('privacy', compact('page'));
    }
}
