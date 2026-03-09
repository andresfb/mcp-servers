<?php

declare(strict_types=1);

use App\Mcp\Servers\WritingPromptServer;
use Laravel\Mcp\Facades\Mcp;

Route::middleware(['throttle:mcp'])->group(function () {

    Mcp::web('/mcp/writing/prompt', WritingPromptServer::class);

});
