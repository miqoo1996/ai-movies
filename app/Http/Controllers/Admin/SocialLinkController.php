<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'platform' => 'required|string|max:50',
            'url'      => 'required|url|max:255',
        ]);

        $presets = self::presets();
        $preset  = $presets[$data['platform']] ?? null;

        SocialLink::create([
            'platform'   => $data['platform'],
            'label'      => $preset['label'] ?? ucfirst($data['platform']),
            'icon'       => $preset['icon']  ?? 'fas fa-link',
            'url'        => $data['url'],
            'is_active'  => true,
            'sort_order' => SocialLink::max('sort_order') + 1,
        ]);

        return back()->with('success', 'Social link added.');
    }

    public function update(Request $request, SocialLink $socialLink)
    {
        $request->validate(['url' => 'required|url|max:255']);
        $socialLink->update(['url' => $request->url]);
        return back()->with('success', 'Link updated.');
    }

    public function toggle(SocialLink $socialLink)
    {
        $socialLink->update(['is_active' => ! $socialLink->is_active]);
        return back()->with('success', $socialLink->is_active ? 'Link activated.' : 'Link deactivated.');
    }

    public function destroy(SocialLink $socialLink)
    {
        $socialLink->delete();
        return back()->with('success', 'Link removed.');
    }

    public static function presets(): array
    {
        return [
            'facebook'  => ['label' => 'Facebook',   'icon' => 'fab fa-facebook'],
            'instagram' => ['label' => 'Instagram',  'icon' => 'fab fa-instagram'],
            'x'         => ['label' => 'X / Twitter','icon' => 'fab fa-x-twitter'],
            'youtube'   => ['label' => 'YouTube',    'icon' => 'fab fa-youtube'],
            'tiktok'    => ['label' => 'TikTok',     'icon' => 'fab fa-tiktok'],
            'whatsapp'  => ['label' => 'WhatsApp',   'icon' => 'fab fa-whatsapp'],
            'telegram'  => ['label' => 'Telegram',   'icon' => 'fab fa-telegram'],
            'pinterest' => ['label' => 'Pinterest',  'icon' => 'fab fa-pinterest'],
            'linkedin'  => ['label' => 'LinkedIn',   'icon' => 'fab fa-linkedin'],
        ];
    }
}
