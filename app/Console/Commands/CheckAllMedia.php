<?php

namespace App\Console\Commands;

use App\Models\Animal;
use App\Models\Media;
use Illuminate\Console\Command;

class CheckAllMedia extends Command
{
    protected $signature = 'media:inspect';
    protected $description = 'Inspect all animals and media';

    public function handle(): int
    {
        $animals = Animal::with(['media', 'gallery'])->get();
        $this->info("Total animals: " . $animals->count());

        foreach ($animals as $animal) {
            $this->line("--------------------------------------------------");
            $this->info("Animal: {$animal->name} (slug: {$animal->slug}, id: {$animal->id})");
            if ($animal->media) {
                $this->line("  Main Media: dir='{$animal->media->directory}' filename='{$animal->media->filename}' url='{$animal->media->url()}'");
            } else {
                $this->warn("  Main Media: NONE");
            }

            foreach ($animal->gallery as $g) {
                $this->line("  Gallery item #{$g->id}: dir='{$g->directory}' filename='{$g->filename}' url='{$g->url()}'");
            }
        }

        $this->line("==================================================");
        $this->info("All Media rows in DB:");
        foreach (Media::all() as $m) {
            $this->line("ID: {$m->id} | mediable_type: {$m->mediable_type} | mediable_id: {$m->mediable_id} | dir: {$m->directory} | file: {$m->filename}");
        }

        return 0;
    }
}
