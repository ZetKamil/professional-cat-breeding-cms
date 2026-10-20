<?php

declare(strict_types=1);

namespace Tests\Feature\Frontend;

use App\Models\Animal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BritishCatsFatherAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_british_kittens_have_stas_as_father(): void
    {
        $stas = Animal::where('slug', 'stas')->first();
        $nel = Animal::where('slug', 'nel')->first();

        $this->assertNotNull($stas, 'Staś powinien istnieć w bazie.');
        $this->assertNotNull($nel, 'Nel powinna istnieć w bazie.');

        // Kocięta brytyjskie z miotu po Stasiu i Nel
        foreach (['ada', 'alma', 'atena'] as $slug) {
            $cat = Animal::where('slug', $slug)->first();
            $this->assertNotNull($cat, "Kot {$slug} powinien istnieć.");
            $this->assertSame($stas->id, $cat->father_id, "Kociak {$slug} powinien mieć ojca Stasia.");
            $this->assertSame($nel->id, $cat->mother_id, "Kociak {$slug} powinien mieć matkę Nel.");
        }

        // Kocur Atos (reproduktor) — syn Nel
        $atos = Animal::where('slug', 'atos')->first();
        $this->assertNotNull($atos, 'Atos powinien istnieć.');
        $this->assertSame($nel->id, $atos->mother_id, 'Atos powinien mieć matkę Nel.');
    }
}
