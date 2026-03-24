<?php

declare(strict_types=1);

namespace App\Mcp\Tools;

use App\Services\PrompterService;
use Exception;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Gets a Random WritingPrompt from the Prompter API and send it to the Client')]
final class RandomWritingPromptTool extends Tool
{
    public function __construct(private readonly PrompterService $prompterService) {}

    /**
     * @throws Exception
     */
    public function handle(Request $request): Response
    {
        $prompt = $this->prompterService->random();

        return Response::text(
            json_encode(
                $prompt->toArray(),
                JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );
    }
}
