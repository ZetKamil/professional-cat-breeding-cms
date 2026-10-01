<?php

declare(strict_types=1);

namespace App\Services\AiAgent\Contracts;

/**
 * Interface for Agent Tools inspired by Model Context Protocol (MCP).
 *
 * Allows the AI Agent to dynamically discover and execute tools
 * to fetch context (e.g. DB models) or execute side-effects (e.g. Save Draft).
 */
interface AgentToolInterface
{
    /**
     * Unique identifier of the tool.
     */
    public function name(): string;

    /**
     * Description of what the tool does and what input parameters it accepts.
     */
    public function description(): string;

    /**
     * Execute the tool logic.
     *
     * @param array<string, mixed> $args
     * @return mixed
     */
    public function execute(array $args = []): mixed;
}
