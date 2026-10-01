<?php

declare(strict_types=1);

namespace App\Services\AiAgent\Tools;

use App\Models\Animal;
use App\Services\AiAgent\Contracts\AgentToolInterface;
use Illuminate\Support\Facades\Log;

/**
 * MCP Tool: Fetches active cats from the CMS database for a specific breed.
 */
class GetCatteryAnimalsTool implements AgentToolInterface
{
    public function name(): string
    {
        return 'get_cattery_animals';
    }

    public function description(): string
    {
        return 'Fetches active cats and kittens from the cattery database (breed, name, color, age, status).';
    }

    public function execute(array $args = []): array
    {
        $breedLabel = $args['breed'] ?? null;

        Log::info('MCP Tool Execution: get_cattery_animals', ['breed' => $breedLabel]);

        $query = Animal::published()->with('media');

        if (! empty($breedLabel)) {
            $query->where('breed', $breedLabel);
        }

        $animals = $query->get();

        return $animals->map(fn (Animal $a) => [
            'id' => $a->id,
            'name' => $a->name,
            'breed' => $a->breed,
            'color' => $a->color,
            'status' => $a->statusLabel(),
            'age' => $a->age(),
        ])->toArray();
    }
}
