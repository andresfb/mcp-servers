<?php

declare(strict_types=1);

namespace App\Mcp\Servers;

use App\Mcp\Tools\RandomGalleryTool;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('Random Gallery Server')]
#[Version('0.0.1')]
#[Instructions('Provides a Gallery of Random Images from the Imoger API')]
final class RandomGalleryServer extends Server
{
    protected array $tools = [
        RandomGalleryTool::class,
    ];

    protected array $resources = [
        //
    ];

    protected array $prompts = [
        //
    ];
}
