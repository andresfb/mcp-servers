<?php

declare(strict_types=1);

namespace App\Mcp\Servers;

use App\Mcp\Tools\RandomWritingPromptTool;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('Writing Prompt Server')]
#[Version('0.0.1')]
#[Instructions('This servers provides writing prompt information')]
final class WritingPromptServer extends Server
{
    protected array $tools = [
        RandomWritingPromptTool::class,
    ];

    protected array $resources = [
        //
    ];

    protected array $prompts = [
        //
    ];
}
