<?php

declare(strict_types=1);

use App\Mcp\Servers\WritingPromptServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::oauthRoutes();

Route::middleware(['auth:api', 'throttle'])->group(function () {

    Mcp::web('/mcp/writing', WritingPromptServer::class)
        ->name('mcp.writing');

});
