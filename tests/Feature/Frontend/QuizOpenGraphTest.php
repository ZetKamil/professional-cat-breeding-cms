<?php

declare(strict_types=1);

namespace Tests\Feature\Frontend;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizOpenGraphTest extends TestCase
{
    use RefreshDatabase;

    public function test_quiz_page_renders_luxury_square_open_graph_image(): void
    {
        $response = $this->get('/dobierz-rase');

        $response->assertOk();

        // Check Open Graph image tag with 1:1 square dimensions
        $response->assertSee('images/quiz/og-quiz-1200.jpg', false);
        $response->assertSee('<meta property="og:image:width" content="1200">', false);
        $response->assertSee('<meta property="og:image:height" content="1200">', false);
        $response->assertSee('<meta property="og:image:type" content="image/jpeg">', false);
        $response->assertSee('<link rel="image_src"', false);

        // Verify physical asset exists on disk in public directory
        $this->assertFileExists(public_path('images/quiz/og-quiz-1200.jpg'));
        $this->assertFileExists(public_path('images/quiz/1.png'));
        $this->assertFileExists(public_path('og-quiz.jpg'));
    }
}
