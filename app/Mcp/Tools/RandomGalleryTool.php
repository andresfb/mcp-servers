<?php

declare(strict_types=1);

namespace App\Mcp\Tools;

use App\Enums\ImogerApiEndpoints;
use App\Libraries\ImogerApiLibrary;
use Exception;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use Override;

#[Description('A description of what this tool does.')]
final class RandomGalleryTool extends Tool
{
    public function __construct(
        private readonly ImogerApiLibrary $apiLibrary
    ) {}

    /**
     * @throws Exception
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'count' => 'nullable|integer|min:1|max:48',
        ]);

        $gallery = $this->apiLibrary
            ->setCount($validated['count'] ?? 6)
            ->setEndpoint(ImogerApiEndpoints::GALLERY)
            ->get();

        return Response::text(
            json_encode(
                $gallery->toArray(),
                JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function schema(JsonSchema $schema): array
    {
        return [
            'count' => $schema->integer()
                ->min(1)
                ->max(48)
                ->description('Number of images to request')
                ->nullable(),
        ];
    }
}
