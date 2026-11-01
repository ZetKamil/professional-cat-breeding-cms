<?php

use App\Models\Post;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Sanitizes blog post excerpts and meta descriptions on the live production database,
     * removing raw technical prompt text ("Szybkie podsumowanie dla wyszukiwarek i sztucznej inteligencji (GEO Summary):").
     * Also guarantees that Article 016 is scheduled for today (2026-11-01 10:00:00).
     */
    public function up(): void
    {
        $posts = Post::all();

        foreach ($posts as $post) {
            $dirty = false;
            $updates = [];

            // 1. Clean excerpt if it contains GEO summary or starts with blockquote/heading
            $excerpt = (string) ($post->excerpt ?? '');
            if (str_contains($excerpt, 'GEO Summary') || str_contains($excerpt, 'Szybkie podsumowanie dla wyszukiwarek') || str_starts_with(trim($excerpt), '>') || str_starts_with(trim($excerpt), '#')) {
                $metaDesc = (string) ($post->meta_description ?? '');
                if (! empty($metaDesc) && ! str_contains($metaDesc, 'GEO Summary') && ! str_starts_with(trim($metaDesc), '#') && ! str_starts_with(trim($metaDesc), '>')) {
                    $cleanExcerpt = trim($metaDesc);
                } else {
                    $cleanExcerpt = $post->cardExcerpt(160);
                }
                $updates['excerpt'] = $cleanExcerpt;
                $dirty = true;
            }

            // 2. Clean meta_description if it contains GEO summary
            $metaDescription = (string) ($post->meta_description ?? '');
            if (str_contains($metaDescription, 'GEO Summary') || str_contains($metaDescription, 'Szybkie podsumowanie dla wyszukiwarek') || str_starts_with(trim($metaDescription), '>')) {
                $updates['meta_description'] = $post->cardExcerpt(160);
                $dirty = true;
            }

            // 3. For article 16 specifically, guarantee today's schedule and clean body links
            if ($post->slug === 'jak-rozpoznac-legalna-hodowle-kotow') {
                $updates['published_at'] = '2026-11-01 10:00:00';
                $updates['excerpt'] = 'Jak sprawdzić, czy hodowla kotów jest legalna? Poznaj 10 najważniejszych sygnałów ostrzegawczych, sprawdź wymagane certyfikaty i uniknij pseudohodowli.';
                $updates['meta_description'] = 'Jak sprawdzić, czy hodowla kotów jest legalna? Poznaj 10 najważniejszych sygnałów ostrzegawczych, sprawdź wymagane certyfikaty i uniknij pseudohodowli.';

                if (str_contains($post->body, 'zmazowieckiejszwajcarii.pl') || str_contains($post->body, 'GEO Summary') || str_contains($post->body, 'ikoty')) {
                    $cleanedBody = str_replace('https://zmazowieckiejszwajcarii.pl/o-hodowli', '/o-hodowli', $post->body);
                    $cleanedBody = str_replace('https://zmazowieckiejszwajcarii.pl/koty', '/koty', $cleanedBody);
                    $cleanedBody = str_replace('ikoty hodowlane', 'i koty hodowlane', $cleanedBody);
                    $cleanedBody = str_replace('nieprzebadane chorym zwierzętom', 'nieprzebadane, chore zwierzęta', $cleanedBody);
                    $cleanedBody = preg_replace('/(?:>\s*)?(?:\*\*)?Szybkie podsumowanie dla wyszukiwarek i sztucznej inteligencji \(GEO Summary\):?(?:\*\*)?/u', '> **Odpowiedź w pigułce:**', $cleanedBody);
                    $updates['body'] = $cleanedBody;
                    $updates['sections'] = Post::parseBodyToSections($cleanedBody);
                }
                $dirty = true;
            }

            if ($dirty && ! empty($updates)) {
                $post->update($updates);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op: Data cleaning is non-reversible.
    }
};
