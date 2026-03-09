<?php

declare(strict_types=1);

use App\Mcp\Servers\WritingPromptServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::oauthRoutes();

Mcp::web('/mcp/writing', WritingPromptServer::class)
    ->name('mcp.writing');
