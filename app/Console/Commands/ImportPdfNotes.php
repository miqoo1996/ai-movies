<?php

namespace App\Console\Commands;

use App\Models\Show;
use App\Models\Person;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportPdfNotes extends Command
{
    protected $signature = 'shows:import-notes
                            {--dry-run : Preview changes without saving}';

    protected $description = 'Update show synopses and missing cast from notes.pdf data';

    /**
     * Show data extracted from notes.pdf.
     * Only updates synopsis if the PDF version is longer than what's stored.
     * Cast entries are added only if the person+show_person link is missing.
     */
    private function getShowData(): array
    {
        return [
            [
                'id'       => 479,
                'title'    => 'Eşref Rüya',
                'synopsis' => 'One of the most talked-about Turkish television productions in recent years, Eşref Rüya combines romance, drama, action, and suspense in a fascinating journey through themes of love, loyalty, ambition, and personal transformation. The story revolves around Eşref, a powerful and influential man whose life has been defined by struggle, sacrifice, and survival. Behind his confident exterior lies a man who has spent years searching for meaning, trust, and genuine connection. Everything begins to change when he meets Nisan, an intelligent, independent, and compassionate woman who refuses to be intimidated by his power or status. Coming from very different worlds, Eşref and Nisan initially struggle to understand each other, but as circumstances continue to bring them together, a deeper connection begins to develop. Eşref\'s position places him in constant conflict with rivals, hidden enemies, and individuals who seek to undermine his influence. As secrets emerge and tensions rise, he must decide whether he can leave parts of his past behind or whether old conflicts will continue to define his future.',
                'cast'     => [
                    ['name' => 'Çağatay Ulusoy',    'character' => 'Eşref'],
                    ['name' => 'Demet Özdemir',      'character' => 'Nisan'],
                    ['name' => 'Necip Memili',       'character' => 'Gürdal'],
                    ['name' => 'Büşra Develi',       'character' => 'Çiğdem'],
                    ['name' => 'Ahmet Rıfat Şungar', 'character' => 'Faruk'],
                ],
            ],
            [
                'id'       => 22,
                'title'    => 'Karadayı',
                'synopsis' => 'Set against the backdrop of 1970s Turkey, Karadayı tells the story of Mahir Kara, a hardworking and honorable young man whose life is turned upside down when his father Nazif is wrongfully accused of a murder he did not commit. Determined to prove his father\'s innocence, Mahir abandons his plans for the future and dedicates himself to uncovering the truth, entering a dangerous world of corruption, hidden agendas, and powerful individuals determined to keep the truth buried. During his search for justice, Mahir encounters Feride Şadoğlu, a talented and idealistic judge who believes strongly in fairness and the rule of law. As they spend more time together, a powerful bond develops between them. However, their growing feelings create significant complications as Mahir hides important truths about who he really is. Through themes of justice, honor, loyalty, and love, Karadayı delivers an emotional journey filled with difficult choices and unexpected challenges that continues to captivate audiences around the world.',
                'cast'     => [
                    ['name' => 'Kenan İmirzalıoğlu', 'character' => 'Mahir Kara'],
                    ['name' => 'Bergüzar Korel',      'character' => 'Feride Şadoğlu'],
                    ['name' => 'Çetin Tekindor',      'character' => 'Nazif Kara'],
                    ['name' => 'Erkan Avcı',          'character' => 'Necdet Güney'],
                    ['name' => 'Yurdaer Okur',        'character' => 'Yasin'],
                ],
            ],
            [
                'id'       => 1,
                'title'    => 'Kiralık Aşk (Love For Rent)',
                'synopsis' => 'Kiralık Aşk, known internationally as Love for Rent, is one of the most popular Turkish romantic comedies ever produced. The story revolves around Defne Topal, a hardworking and caring young woman doing everything she can to support her family. Her life changes when she becomes involved in a plan created by Neriman İplikçi, a wealthy woman determined to influence the romantic life of her nephew Ömer İplikçi, a successful fashion designer known for his intelligence, independence, and reluctance to commit. What begins as a complicated arrangement soon becomes far more difficult than anyone expected. As Defne spends time with Ömer, she discovers that he is very different from the cold and distant person others describe. Likewise, Ömer becomes increasingly drawn to Defne\'s sincerity, kindness, and determination. Their relationship develops gradually, creating both heartwarming and humorous moments. However, hidden secrets and misunderstandings threaten to destroy the trust they are building.',
                'cast'     => [
                    ['name' => 'Elçin Sangu',         'character' => 'Defne Topal'],
                    ['name' => 'Barış Arduç',         'character' => 'Ömer İplikçi'],
                    ['name' => 'Salih Bademci',       'character' => 'Sinan Karakaya'],
                    ['name' => 'Nergis Kumbasar',     'character' => 'Neriman İplikçi'],
                    ['name' => 'Onur Büyüktopçu',     'character' => 'Koray Sargın'],
                ],
            ],
            [
                'id'       => 2,
                'title'    => 'Erkenci Kuş (Daydreamer)',
                'synopsis' => 'Erkenci Kuş, which translates to "Early Bird," is among the most beloved Turkish romantic comedies of recent years. The story follows Sanem Aydın, a cheerful and imaginative young woman who dreams of becoming a successful writer. Encouraged by her family to find stable employment, she begins working at a prestigious advertising agency where her sister is already employed. At the agency, Sanem meets Can Divit, a talented photographer and world traveler who has recently returned to help manage the family business. Independent, adventurous, and charismatic, Can represents everything Sanem finds exciting and inspiring. Their first encounters are filled with misunderstandings, humorous situations, and unexpected surprises. As they spend more time together, their friendship gradually develops into something much deeper. However, workplace rivalries, family expectations, hidden agendas, and personal insecurities create obstacles that challenge their growing relationship. Throughout the series, viewers follow Sanem\'s journey as she gains confidence, pursues her ambitions, and learns important lessons about love and self-worth.',
                'cast'     => [
                    ['name' => 'Demet Özdemir',   'character' => 'Sanem Aydın'],
                    ['name' => 'Can Yaman',        'character' => 'Can Divit'],
                    ['name' => 'Öznur Serçeler',   'character' => 'Leyla Aydın'],
                    ['name' => 'Birand Tunca',     'character' => 'Emre Divit'],
                    ['name' => 'Cihan Ercan',      'character' => 'Muzaffer'],
                ],
            ],
            [
                'id'       => 30,
                'title'    => 'Kara Sevda (Endless Love)',
                'synopsis' => 'Few Turkish dramas have achieved the global success of Kara Sevda. The story follows Kemal Soydere, a hardworking young engineer from a modest family, whose life changes when he meets Nihan Sezin, a talented and wealthy young woman. Despite coming from different social backgrounds, the two fall deeply in love. Their happiness is challenged by Emir Kozcuoğlu, an influential businessman obsessed with Nihan. As family pressure, betrayal, and personal sacrifices separate Kemal and Nihan, they find themselves trapped in a complicated struggle between love and destiny. Throughout the series, viewers witness dramatic twists, emotional reunions, and powerful confrontations that test the strength of true love. Kara Sevda remains one of the most successful Turkish dramas ever produced, with its unforgettable love story and emotional storytelling continuing to attract new viewers years after its original broadcast.',
                'cast'     => [
                    ['name' => 'Burak Özçivit',      'character' => 'Kemal Soydere'],
                    ['name' => 'Neslihan Atagül',    'character' => 'Nihan Sezin'],
                    ['name' => 'Kaan Urgancıoğlu',   'character' => 'Emir Kozcuoğlu'],
                    ['name' => 'Zerrin Tekindor',    'character' => 'Leyla Acemzade'],
                    ['name' => 'Melisa Aslı Pamuk',  'character' => 'Asu Alacahan'],
                ],
            ],
            [
                'id'       => 23,
                'title'    => 'Cesur ve Güzel (Brave and Beautiful)',
                'synopsis' => 'Cesur ve Güzel blends romance, mystery, family conflict, and suspense into one of the most talked-about Turkish productions of its time. The story begins when Cesur Alemdaroğlu arrives in the peaceful town of Korludağ on a secret mission to uncover the truth about events from his past that affected his family. Determined to find answers, he begins investigating the powerful Korludağ family. Everything becomes more complicated when Cesur meets Sühan Korludağ, the intelligent and independent daughter of the family patriarch Tahsin Korludağ. Despite their very different backgrounds and hidden agendas, Cesur and Sühan are drawn to one another. Their relationship develops gradually, transforming from curiosity into genuine affection. However, the secrets connecting their families threaten to destroy everything they are building together. As Cesur continues searching for the truth, he uncovers layers of deception, betrayal, and long-buried family secrets. The conflict between love and revenge becomes one of the central themes of the series.',
                'cast'     => [
                    ['name' => 'Kıvanç Tatlıtuğ',   'character' => 'Cesur Alemdaroğlu'],
                    ['name' => 'Tuba Büyüküstün',   'character' => 'Sühan Korludağ'],
                    ['name' => 'Tamer Levent',       'character' => 'Tahsin Korludağ'],
                    ['name' => 'Erkan Avcı',         'character' => 'Korhan Korludağ'],
                    ['name' => 'Devrim Yakut',       'character' => 'Mihriban'],
                ],
            ],
            [
                'id'       => 18,
                'title'    => 'Siyah Beyaz Aşk (Price of Passion)',
                'synopsis' => 'Siyah Beyaz Aşk, translating to "Black and White Love," explores how two people from completely different worlds can find themselves connected by fate. The story centers on Aslı Çınar, a dedicated and compassionate doctor who has devoted her life to helping others. Her peaceful world changes dramatically when she unexpectedly crosses paths with Ferhat Aslan, a cold and intimidating man connected to a dangerous criminal network. Ferhat has spent most of his life surrounded by violence, power struggles, and criminal activities, learning to survive by suppressing his emotions and trusting very few people. When Aslı becomes an unwilling witness to a dangerous incident, Ferhat becomes responsible for keeping her under his watch. What begins as a frightening situation gradually develops into something far more complicated. As Aslı spends more time around Ferhat, she begins to see the humanity hidden beneath his harsh exterior. Likewise, Ferhat discovers emotions he believed had disappeared long ago. Their relationship evolves slowly as both characters struggle to overcome fear, mistrust, and the emotional barriers created by their vastly different lives.',
                'cast'     => [
                    ['name' => 'İbrahim Çelikkol',     'character' => 'Ferhat Aslan'],
                    ['name' => 'Birce Akalay',         'character' => 'Aslı Çınar'],
                    ['name' => 'Muhammet Uzuner',      'character' => 'Namık Emirhan'],
                    ['name' => 'Arzu Gamze Kılınç',    'character' => 'Yeter Aslan'],
                    ['name' => 'Ece Dizdar',           'character' => 'İdil Yaman'],
                ],
            ],
            [
                'id'       => 70,
                'title'    => 'İstanbullu Gelin (Evermore)',
                'synopsis' => 'İstanbullu Gelin is a beloved Turkish drama that explores relationships, family traditions, and personal growth through an emotional and realistic story. The series follows Süreyya, an independent and talented young singer living in Istanbul. Her life changes when she meets Faruk Boran, a successful businessman from Bursa. After their marriage, Süreyya enters the influential Boran family, where she faces challenges from Faruk\'s strong-willed mother and traditional family expectations. As she adjusts to her new life, she discovers that maintaining love and family harmony is often more difficult than she imagined. The drama balances romance, family conflict, and heartfelt moments, creating a relatable story for viewers of all ages. The series is praised for its realistic family dynamics, strong character development, and emotional storytelling.',
                'cast'     => [
                    ['name' => 'Aslı Enver',       'character' => 'Süreyya Boran'],
                    ['name' => 'Özcan Deniz',       'character' => 'Faruk Boran'],
                    ['name' => 'İpek Bilgin',       'character' => 'Esma Boran'],
                    ['name' => 'Salih Bademci',     'character' => 'Fikret Boran'],
                    ['name' => 'Dilara Aksüyek',    'character' => 'İpek Boran'],
                ],
            ],
            [
                'id'       => 310,
                'title'    => 'Yalı Çapkını (Golden Boy)',
                'synopsis' => 'Yalı Çapkını quickly became one of the most talked-about Turkish dramas thanks to its compelling characters and emotional storyline. The story revolves around Ferit Korhan, a wealthy young man known for his carefree lifestyle. Concerned about his behavior, his grandfather decides that marriage will help him become more responsible. Ferit\'s life changes when he is introduced to Seyran, a young woman from a traditional family. What begins as an arranged marriage gradually develops into a complicated relationship filled with love, misunderstandings, and personal growth. As family expectations and hidden secrets emerge, both characters must learn how to navigate their new reality. Fans appreciate the chemistry between the lead actors and the show\'s ability to blend romance, drama, and family relationships.',
                'cast'     => [
                    ['name' => 'Afra Saraçoğlu',        'character' => 'Seyran Şanlı'],
                    ['name' => 'Mert Ramazan Demir',    'character' => 'Ferit Korhan'],
                    ['name' => 'Çetin Tekindor',        'character' => 'Halis Korhan'],
                    ['name' => 'Gülçin Santırcıoğlu',   'character' => 'İfakat Korhan'],
                    ['name' => 'Beril Pozam',           'character' => 'Suna Şanlı'],
                ],
            ],
            [
                'id'       => 143,
                'title'    => 'Medcezir',
                'synopsis' => 'Medcezir remains one of the most popular Turkish youth dramas thanks to its memorable characters and emotional storytelling. The story follows Yaman Koper, who grows up in a difficult environment where opportunities are limited. His life changes when he meets Selim Serez, a successful lawyer who recognizes his potential. As Yaman enters a completely different social world, he faces challenges involving friendship, love, and personal identity. Along the way, he develops a strong connection with Mira, leading to a romance that becomes one of the show\'s central storylines. The series explores themes of social class, family, ambition, and personal growth, combining youthful energy with meaningful life lessons that make it appealing to both younger and older audiences.',
                'cast'     => [
                    ['name' => 'Çağatay Ulusoy',    'character' => 'Yaman Koper'],
                    ['name' => 'Serenay Sarıkaya',  'character' => 'Mira Beylice'],
                    ['name' => 'Barış Falay',       'character' => 'Selim Serez'],
                    ['name' => 'Mine Tugay',        'character' => 'Ender Serez'],
                    ['name' => 'Hazar Ergüçlü',     'character' => 'Eylül Buluter'],
                ],
            ],
            [
                'id'       => 125,
                'title'    => 'Muhteşem Yüzyıl (Magnificent Century)',
                'synopsis' => 'Among the most successful and internationally recognized Turkish television productions ever created, Muhteşem Yüzyıl stands as a landmark achievement in historical drama. Known internationally as Magnificent Century, the series captivated audiences across dozens of countries with its grand storytelling, lavish production, and unforgettable characters. Set during one of the most influential periods of Ottoman history, Muhteşem Yüzyıl tells the story of Sultan Suleiman the Magnificent, his rise as one of the empire\'s most powerful rulers, and the complex relationships that shaped his reign. One of the most significant developments occurs when Alexandra, a young woman captured during a raid, arrives at the imperial palace. Renamed Hürrem, she quickly distinguishes herself through her intelligence, determination, and ambition. As Hürrem\'s relationship with the Sultan deepens, she rises from a palace concubine to become his legal wife. Her growing influence creates tension among members of the royal family, palace officials, and rival factions. Combining political intrigue, romance, family conflict, and historical events, the series offers viewers an immersive journey into a fascinating era.',
                'cast'     => [
                    ['name' => 'Halit Ergenç',      'character' => 'Sultan Suleiman'],
                    ['name' => 'Meryem Uzerli',     'character' => 'Hürrem Sultan'],
                    ['name' => 'Nebahat Çehre',     'character' => 'Valide Sultan'],
                    ['name' => 'Okan Yalabık',      'character' => 'Pargalı Ibrahim Pasha'],
                    ['name' => 'Selma Ergeç',       'character' => 'Hatice Sultan'],
                ],
            ],
        ];
    }

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('DRY RUN — no changes will be saved.');
        }

        $synopsisUpdated = 0;
        $castAdded       = 0;
        $peopleCreated   = 0;

        foreach ($this->getShowData() as $data) {
            $show = Show::find($data['id']);

            if (! $show) {
                $this->warn("  Show ID {$data['id']} ({$data['title']}) not found, skipping.");
                continue;
            }

            // Update synopsis only if PDF version is richer (longer).
            $newSynopsis = $data['synopsis'];
            if (strlen($newSynopsis) > strlen((string) $show->synopsis)) {
                $this->line("  <info>Synopsis update:</info> [{$show->id}] {$show->title}");
                $this->line("    Current: " . strlen((string) $show->synopsis) . " chars → New: " . strlen($newSynopsis) . " chars");

                if (! $dryRun) {
                    $show->synopsis    = $newSynopsis;
                    $show->ai_synopsis = $newSynopsis;
                    $show->save();
                }
                $synopsisUpdated++;
            } else {
                $this->line("  <comment>Synopsis OK:</comment> [{$show->id}] {$show->title} (" . strlen((string) $show->synopsis) . " chars)");
            }

            // Ensure cast members exist.
            foreach ($data['cast'] as $sortOrder => $member) {
                $person = Person::where('name', $member['name'])->first();

                if (! $person) {
                    $this->line("    <info>Creating person:</info> {$member['name']}");
                    if (! $dryRun) {
                        $person = Person::create(['name' => $member['name']]);
                    }
                    $peopleCreated++;
                }

                if (! $person) {
                    continue; // dry-run, person not created
                }

                $exists = DB::table('show_person')
                    ->where('show_id', $show->id)
                    ->where('person_id', $person->id)
                    ->exists();

                if (! $exists) {
                    $this->line("    <info>Adding cast:</info> {$member['name']} as {$member['character']} → {$show->title}");
                    if (! $dryRun) {
                        DB::table('show_person')->insert([
                            'show_id'        => $show->id,
                            'person_id'      => $person->id,
                            'department'     => 'cast',
                            'role'           => 'actor',
                            'character_name' => $member['character'],
                            'sort_order'     => $sortOrder,
                            'created_at'     => now(),
                            'updated_at'     => now(),
                        ]);
                    }
                    $castAdded++;
                }
            }
        }

        $this->newLine();
        $this->info("Done. Synopsis updated: {$synopsisUpdated} | People created: {$peopleCreated} | Cast links added: {$castAdded}");

        return self::SUCCESS;
    }
}
