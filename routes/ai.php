<?php

declare(strict_types=1);

use App\Mcp\Servers\WritingPromptServer;
use Laravel\Mcp\Facades\Mcp;

Route::middleware('auth:api')->group(function () {

    Mcp::web('/mcp/writing', WritingPromptServer::class)
        ->name('mcp.writing');

});
