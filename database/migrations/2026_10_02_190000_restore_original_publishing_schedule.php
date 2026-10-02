<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $schedule = [
            'kot-bengalski-a-dzieci'                  => '2026-07-26 10:00:00',
            'ile-kosztuje-kot-bengalski'              => '2026-07-19 10:00:00',
            'hodowla-kotow-bengalskich-mazowieckie'   => '2026-08-02 10:00:00',
            'kocieta-bengalskie-rezerwacja-i-odbior'  => '2026-08-09 10:00:00',
            'pierwsze-dni-kociecia-w-nowym-domu'      => '2026-08-16 10:00:00',
            'co-zawiera-rodowod-kota'                 => '2026-08-23 10:00:00',
            'badania-genetyczne-hcm-pkd-koty'         => '2026-08-30 10:00:00',
            'zywienie-kota-bengalskiego'              => '2026-09-06 10:00:00',
            'kot-bengalski-a-inne-zwierzeta'          => '2026-09-13 10:00:00',
            'socjalizacja-kociat-w-hodowli'           => '2026-09-20 10:00:00',
            'kot-bengalski-czy-brytyjski'             => '2026-09-27 10:00:00',
            // Scheduled into the future (Sundays at 10:00):
            'szczepienia-kociat-harmonogram'          => '2026-10-04 10:00:00',
            'wyprawka-dla-kociaka-lista'              => '2026-10-11 10:00:00',
            'kot-bengalski-charakter-i-opieka'        => '2026-10-18 10:00:00',
            'ile-zyje-kot-bengalski'                  => '2026-10-25 10:00:00',
            'jak-rozpoznac-legalna-hodowle-kotow'     => '2026-11-01 10:00:00',
            'kastracja-kota-bengalskiego'             => '2026-11-08 10:00:00',
            'kot-bengalski-a-mieszkanie'              => '2026-11-15 10:00:00',
            'nasze-koty-hodowlane'                    => '2026-11-22 10:00:00',
        ];

        foreach ($schedule as $slug => $date) {
            DB::table('posts')->where('slug', $slug)->update([
                'published_at' => $date,
                'is_published' => true,
            ]);
        }
    }

    public function down(): void
    {
    }
};
