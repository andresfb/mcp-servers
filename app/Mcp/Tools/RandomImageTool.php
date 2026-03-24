<?php

declare(strict_types=1);

namespace App\Mcp\Tools;

use App\Dtos\ImogerImageItem;
use App\Enums\ImogerApiEndpoints;
use App\Libraries\ImogerApiLibrary;
use Exception;
use Illuminate\Support\Facades\Http;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use RuntimeException;

#[Description('A description of what this tool does.')]
final class RandomImageTool extends Tool
{
    public function __construct(
        private readonly ImogerApiLibrary $apiLibrary
    ) {}

    /**
     * @throws Exception
     */
    public function handle(Request $request): Response
    {
        $result = $this->apiLibrary
            ->setEndpoint(ImogerApiEndpoints::IMAGE)
            ->get();

        /** @var ImogerImageItem $image */
        $image = $result->images->firstOrFail();
        $data = $image->toArray();
        $data['imageInfo'] = $this->loadInfo($image);

        return Response::text(
            json_encode(
                $data,
                JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );
    }

    /**
     * @return array{fileName: string, base64: string, mimeType: string}
     * @throws Exception
     */
    private function loadInfo(ImogerImageItem $image): array
    {
        $response = Http::get($image->altImage);

        if ($response->failed()) {
            throw new RuntimeException("Failed to fetch image from: {$image->altImage}");
        }

        $fileName = basename(parse_url($image->altImage, PHP_URL_PATH));

        return [
            'fileName' => $fileName,
            'base64' => base64_encode($response->body()),
            'mimeType' => 'image/jpeg',
        ];
    }
}
