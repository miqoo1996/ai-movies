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
            'site_name'               => 'DiziBul',
            'site_tagline'            => 'Turkish Dramas? We\'ve got the çay!',
            'footer_copyright'        => 'All Rights Reserved.',
            'footer_tagline'          => 'Made with ♥ for Turkish drama fans',

            // SEO
            'seo_title_format'        => '{title} — DiziBul',
            'seo_default_description' => 'Discover the best Turkish TV series and dramas on DiziBul. Browse 500+ dizi with episode guides, cast info and English subtitles.',
            'robots_noindex'          => 'index',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
