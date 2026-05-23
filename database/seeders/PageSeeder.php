<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug'            => 'contact',
                'title'           => 'Contact Us',
                'content'         => null,
                'seo_title'       => 'Contact Us',
                'seo_description' => 'Have a question about DiziBul, a subtitle issue, or a series request? Contact our team — we read every message and aim to reply within 24 hours.',
                'noindex'         => false,
            ],
            [
                'slug'            => 'terms',
                'title'           => 'Terms of Use',
                'content'         => null,
                'seo_title'       => 'Terms & Conditions',
                'seo_description' => 'Review the DiziBul terms and conditions. By using our Turkish drama platform you agree to these terms — please read carefully before subscribing.',
                'noindex'         => false,
            ],
            [
                'slug'            => 'privacy',
                'title'           => 'Privacy Policy',
                'content'         => null,
                'seo_title'       => 'Privacy Policy',
                'seo_description' => 'Read the DiziBul privacy policy — how we collect, store and protect your personal data when you use our Turkish drama platform.',
                'noindex'         => false,
            ],
            [
                'slug'            => 'home',
                'title'           => 'Home',
                'content'         => null,
                'seo_title'       => 'Watch Turkish Dramas & Series Online',
                'seo_description' => 'Discover 500+ Turkish TV series and dramas on DiziBul. Find episode guides, cast info, ratings, and where to watch your favourite dizi with English subtitles.',
                'noindex'         => false,
            ],
            [
                'slug'            => 'shows',
                'title'           => 'Shows Listing',
                'content'         => null,
                'seo_title'       => 'All Turkish TV Series & Dramas',
                'seo_description' => 'Browse 500+ Turkish TV series and dramas on DiziBul. Filter by genre, network, year and status to find your next favourite dizi to watch with English subtitles.',
                'noindex'         => false,
            ],
            [
                'slug'            => 'faq',
                'title'           => 'FAQ',
                'content'         => null,
                'seo_title'       => 'Frequently Asked Questions',
                'seo_description' => 'Get answers about DiziBul — how to watch Turkish dramas, English subtitle availability, translation timing, subscription plans, and login help.',
                'noindex'         => false,
            ],
            [
                'slug'            => 'calendar',
                'title'           => 'TV Calendar',
                'content'         => null,
                'seo_title'       => 'Turkish TV Airing Schedule',
                'seo_description' => 'Track new Turkish drama episode releases with the DiziBul TV calendar. See what dizi are airing this week and never miss a new episode.',
                'noindex'         => false,
            ],
        ];

        foreach ($pages as $data) {
            Page::firstOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
