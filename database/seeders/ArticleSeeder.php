<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        Article::updateOrCreate(
            ['slug' => 'getting-started-with-turkish-dramas'],
            [
                'title'   => 'Getting Started With Turkish Dramas: A Beginner’s Guide',
                'excerpt' => 'New to dizi? Here is how the seasons work, why episodes run so long, and how to pick a first series you will actually finish.',
                'content' => <<<'HTML'
<p>If you have just discovered Turkish television, the first thing you notice is the scale. Episodes routinely run past the two-hour mark, seasons stretch across dozens of them, and a single story can carry you through an entire year. It is a different rhythm from what most streaming audiences are used to, and it takes a few episodes to settle into.</p>

<h2>Why episodes are so long</h2>

<p>Turkish series are built for weekly primetime broadcast, where a single episode is expected to fill an entire evening of programming. Instead of the tight forty-minute structure common elsewhere, writers get room to let scenes breathe: long silences, extended family arguments, and slow-building tension that would be cut for time in a shorter format.</p>

<p>The practical advice for newcomers is simple — do not try to watch an episode the way you would watch a sitcom. Treat each one as a film. Most viewers split them across two sittings, and nothing about the pacing punishes you for it.</p>

<h2>How seasons actually work</h2>

<p>Season numbering can be confusing at first, because it usually follows the broadcast year rather than a fixed episode count. A season may end after twelve episodes or run past forty, depending entirely on how the show performs.</p>

<ul>
    <li><strong>Ratings decide everything.</strong> A series that does not find an audience can be cancelled within a handful of episodes, sometimes mid-story.</li>
    <li><strong>Summer breaks are normal.</strong> Shows commonly pause for several months and return in the autumn with the same cast and a time jump.</li>
    <li><strong>Finales are often planned late.</strong> Writers frequently learn a show is ending only weeks in advance, which is why some endings feel abrupt.</li>
</ul>

<h2>Picking a first series</h2>

<p>The most common beginner mistake is starting with a show that has two hundred episodes behind it. Momentum matters more than prestige when you are still adjusting to the format, so look for a completed series with a single, self-contained season. You get a full arc, a real ending, and a much better sense of whether the genre suits you.</p>

<blockquote>Start with something finished. A show with a proper ending teaches you more about the format in twenty episodes than an ongoing epic will in a hundred.</blockquote>

<p>Genre is worth thinking about too. Romantic comedies tend to move fastest and are the friendliest entry point. Historical epics are gorgeous but demand patience and a tolerance for large casts. Crime and revenge dramas sit somewhere in between, and often have the tightest plotting of the three.</p>

<h2>A note on subtitles</h2>

<p>Subtitle timing varies by series and by episode. Newer, popular shows are usually translated within a day or two of broadcast, while older or less prominent titles can take longer. If a series matters to you, it is worth checking whether the full back catalogue is already subtitled before you commit to it — there is nothing worse than losing momentum forty episodes in.</p>

<p>Beyond that, the advice is the one that applies to any new format: give it three episodes. The pacing that feels slow in the first hour is usually the reason the show works by the tenth.</p>
HTML,
                'cover_image'     => 'articles/getting-started-with-turkish-dramas.jpg',
                'seo_title'       => 'Getting Started With Turkish Dramas: A Beginner’s Guide',
                'seo_description' => 'How Turkish drama seasons work, why episodes run over two hours, and how to choose a first series you will actually finish.',
                'noindex'         => false,
                'is_published'    => true,
                'published_at'    => now()->subDays(3),
            ]
        );
    }
}
