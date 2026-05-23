<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name'        => 'nullable|string|max:100',
            'site_tagline'     => 'nullable|string|max:255',
            'signin_url'       => 'nullable|url|max:255',
            'facebook_url'     => 'nullable|url|max:255',
            'instagram_url'    => 'nullable|url|max:255',
            'twitter_url'      => 'nullable|url|max:255',
            'appstore_url'     => 'nullable|url|max:255',
            'playstore_url'    => 'nullable|url|max:255',
            'footer_copyright' => 'nullable|string|max:255',
            'footer_tagline'   => 'nullable|string|max:255',
            'logo'             => 'nullable|image|max:2048',
            'favicon'          => 'nullable|image|max:512',
            'og_image'         => 'nullable|image|max:4096',
        ]);

        // Handle text fields
        $textKeys = ['site_name','site_tagline','signin_url','facebook_url','instagram_url',
                     'twitter_url','appstore_url','playstore_url','footer_copyright','footer_tagline'];

        $pairs = [];
        foreach ($textKeys as $key) {
            if (array_key_exists($key, $data)) {
                $pairs[$key] = $data[$key];
            }
        }

        // Handle image uploads
        foreach (['logo', 'favicon', 'og_image'] as $key) {
            if ($request->hasFile($key)) {
                // Delete old file if present
                $old = Setting::get($key);
                if ($old) Storage::disk('public')->delete($old);

                $path = $request->file($key)->store('settings', 'public');
                $pairs[$key] = $path;
            }
        }

        Setting::setMany($pairs);

        return back()->with('success', 'Settings saved.');
    }
}
