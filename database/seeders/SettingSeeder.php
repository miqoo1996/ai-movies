<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // General
            'site_name'               => 'DiziCentral',
            'site_tagline'            => 'Turkish Dramas? We\'ve got the çay!',
            'footer_copyright'        => 'All Rights Reserved.',
            'footer_tagline'          => 'Made with ♥ for Turkish drama fans',

            // SEO
            'seo_title_format'        => '{title} — DiziCentral',
            'seo_default_description' => 'Watch the latest Turkish series and movies with English subtitles in HD quality. Stream romantic dramas, action series, historical shows, and Turkish cinema online anytime.',
            'robots_noindex'          => 'index',
            'meta_keywords'           => 'Turkish series with English subtitles, Turkish dramas online, watch Turkish series online free, Turkish movies with English subtitles, Turkish TV shows English subtitles, Turkish drama series online, Turkish romantic series English subtitles, Turkish historical dramas English subtitles, Turkish action movies subtitles, Turkish entertainment platform, latest Turkish dramas online, Turkish streaming platform, Turkish series 2026, Turkish cinema English subtitles, best Turkish dramas with English subtitles, Turkish thriller series online, Turkish TV streaming website, English subbed Turkish dramas, Turkish subtitle streaming, watch Turkish dramas online',
        ];

        // firstOrCreate for keys that should not overwrite existing admin changes
        $neverOverwrite = ['site_name', 'site_tagline', 'footer_copyright', 'footer_tagline',
                           'signin_url', 'facebook_url', 'instagram_url', 'twitter_url',
                           'appstore_url', 'playstore_url', 'logo', 'favicon', 'og_image',
                           'google_analytics_id', 'google_tag_manager_id', 'search_console_verify'];

        foreach ($defaults as $key => $value) {
            if (in_array($key, $neverOverwrite)) {
                Setting::firstOrCreate(['key' => $key], ['value' => $value]);
            } else {
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
        }
    }
}
