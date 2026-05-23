<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('id')->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $data = $request->validate([
            'content'         => 'nullable|string',
            'seo_title'       => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:320',
            'noindex'         => 'nullable|boolean',
        ]);

        $data['content'] = $request->input('content', '');
        $data['noindex'] = $request->boolean('noindex');

        $page->update($data);

        return back()->with('success', "\"{$page->title}\" saved.");
    }

    public function preview(Request $request, Page $page)
    {
        $page->content = $request->input('content', '');
        $view = view()->exists($page->slug) ? $page->slug : 'page';
        return view($view, compact('page'));
    }
}
