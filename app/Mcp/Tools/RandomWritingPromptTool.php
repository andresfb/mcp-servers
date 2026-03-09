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
        //        $validated = $request->validate([
        //            'prompter' => 'nullable|string|max:4',
        //        ]);

        //        $prompt = $this->prompterService->random($validated['prompter'] ?? '');
        $prompt = $this->prompterService->random();

        return Response::json($prompt->toArray());
    }

    //    /**
    //     * Get the tool's input schema.
    //     *
    //     * @return array<string, JsonSchema>
    //     */
    //    public function schema(JsonSchema $schema): array
    //    {
    //        return [
    //            'prompter' => $schema->string()
    //                ->description('The code (optional)')
    //                ->nullable(),
    //        ];
    //    }
}
