<?php

declare(strict_types=1);

use App\Mcp\Servers\RandomGalleryServer;
use App\Mcp\Servers\RandomImageServer;
use App\Mcp\Servers\WritingPromptServer;
use Laravel\Mcp\Facades\Mcp;

Route::middleware('auth:sanctum')->group(function () {

    Mcp::web('/mcp/writing', WritingPromptServer::class)
        ->name('mcp.writing');

    Mcp::web('/mcp/image', RandomImageServer::class)
        ->name('mcp.image');

    Mcp::web('/mcp/gallery', RandomGalleryServer::class)
        ->name('mcp.gallery');

});
