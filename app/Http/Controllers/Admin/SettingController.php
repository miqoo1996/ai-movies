<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $socialLinks = SocialLink::ordered()->get();
        $socialPresets = SocialLinkController::presets();
        return view('admin.settings.index', compact('socialLinks', 'socialPresets'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name'              => 'nullable|string|max:100',
            'site_tagline'           => 'nullable|string|max:255',
            'signin_url'             => 'nullable|url|max:255',
            'appstore_url'           => 'nullable|url|max:255',
            'playstore_url'          => 'nullable|url|max:255',
            'footer_copyright'       => 'nullable|string|max:255',
            'footer_tagline'         => 'nullable|string|max:255',
            'logo'                   => 'nullable|image|max:2048',
            'favicon'                => 'nullable|image|max:512',
            'og_image'               => 'nullable|image|max:4096',
            'seo_title_format'       => 'nullable|string|max:100',
            'seo_default_description'=> 'nullable|string|max:320',
            'google_analytics_id'    => 'nullable|string|max:50',
            'google_tag_manager_id'  => 'nullable|string|max:50',
            'search_console_verify'  => 'nullable|string|max:100',
            'robots_noindex'         => 'nullable|string|in:index,noindex',
            'meta_keywords'          => 'nullable|string|max:1000',
        ]);

        // Handle text fields
        $textKeys = ['site_name','site_tagline','signin_url','appstore_url','playstore_url',
                     'footer_copyright','footer_tagline','seo_title_format','seo_default_description',
                     'google_analytics_id','google_tag_manager_id','search_console_verify','robots_noindex',
                     'meta_keywords'];

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
