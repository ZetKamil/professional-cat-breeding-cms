<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Support\ImageGeometry;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageGeometryTest extends TestCase
{
    public function test_analyzes_portrait_image_correctly(): void
    {
        Storage::fake('public');

        // Create 3:4 portrait fake image (300 x 400)
        $file = UploadedFile::fake()->image('cat_portrait.jpg', 300, 400);
        $path = $file->store('animals', 'public');
        $fullPath = Storage::disk('public')->path($path);

        $result = ImageGeometry::analyze($fullPath);

        $this->assertEquals('portrait', $result['orientation']);
        $this->assertTrue($result['is_portrait']);
        $this->assertFalse($result['is_landscape']);
        $this->assertEquals(0.75, $result['aspect_ratio']);
        $this->assertEquals(300, $result['width']);
        $this->assertEquals(400, $result['height']);
    }

    public function test_analyzes_landscape_image_correctly(): void
    {
        Storage::fake('public');

        // Create 16:9 landscape fake image (800 x 450)
        $file = UploadedFile::fake()->image('cat_landscape.jpg', 800, 450);
        $path = $file->store('animals', 'public');
        $fullPath = Storage::disk('public')->path($path);

        $result = ImageGeometry::analyze($fullPath);

        $this->assertEquals('landscape', $result['orientation']);
        $this->assertTrue($result['is_landscape']);
        $this->assertFalse($result['is_portrait']);
        $this->assertEquals(800, $result['width']);
        $this->assertEquals(450, $result['height']);
    }

    public function test_analyzes_square_image_correctly(): void
    {
        Storage::fake('public');

        // Create 1:1 square fake image (500 x 500)
        $file = UploadedFile::fake()->image('cat_square.jpg', 500, 500);
        $path = $file->store('animals', 'public');
        $fullPath = Storage::disk('public')->path($path);

        $result = ImageGeometry::analyze($fullPath);

        $this->assertEquals('square', $result['orientation']);
        $this->assertTrue($result['is_square']);
        $this->assertEquals(1.0, $result['aspect_ratio']);
    }

    public function test_falls_back_gracefully_for_null_or_invalid_path(): void
    {
        $result = ImageGeometry::analyze(null);
        $this->assertEquals('landscape', $result['orientation']);
        $this->assertEquals(1200, $result['width']);

        $result2 = ImageGeometry::analyze('nonexistent/file.jpg');
        $this->assertEquals('landscape', $result2['orientation']);
    }

    public function test_resolves_dimensions_from_url_parameters(): void
    {
        $url = 'https://image.pollinations.ai/prompt/cat?width=900&height=1200';
        $result = ImageGeometry::analyze($url);

        $this->assertEquals('portrait', $result['orientation']);
        $this->assertEquals(900, $result['width']);
        $this->assertEquals(1200, $result['height']);
    }
}
