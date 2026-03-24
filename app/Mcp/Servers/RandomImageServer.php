<?php

declare(strict_types=1);

namespace App\Mcp\Servers;

use App\Mcp\Tools\RandomImageTool;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('Random Image Server')]
#[Version('0.0.1')]
#[Instructions('Provides a Random Image from the Imoger API')]
final class RandomImageServer extends Server
{
    protected array $tools = [
        RandomImageTool::class,
    ];

    protected array $resources = [
        //
    ];

    protected array $prompts = [
        //
    ];
}
