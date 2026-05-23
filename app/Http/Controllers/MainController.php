<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Page;

class MainController extends Controller
{
    public function faq()
    {
        $faqs = Faq::ordered()->get();
        return view('faq', compact('faqs'));
    }
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
