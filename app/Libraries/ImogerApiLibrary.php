<?php

declare(strict_types=1);

namespace App\Libraries;

use App\Dtos\ImogerApiResponse;
use App\Enums\ImogerApiEndpoints;
use App\Models\User;
use App\Models\UserSettings;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use RuntimeException;

final class ImogerApiLibrary
{
    private string $sections = '';

    private ?ImogerApiEndpoints $endpoint = null;

    public function setEndpoint(ImogerApiEndpoints $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function setCount(int $imageCount): self
    {
        $this->setSections([$imageCount]);

        return $this;
    }

    public function setSections(array $sections): self
    {
        $this->sections = implode('/', $sections);

        return $this;
    }

    /**
     * @throws Exception
     */
    public function get(): ImogerApiResponse
    {
        $response = Http::withToken($this->getToken())
            ->connectTimeout(30)
            ->timeout(60)
            ->acceptJson()
            ->contentType('application/json')
            ->get($this->getUrl())
            ->throw()
            ->object();

        return ImogerApiResponse::parseResponse($response->data);
    }

    private function getUrl(): string
    {
        if (blank($this->endpoint)) {
            throw new RuntimeException('Imoger API endpoint not found');
        }

        return sprintf(
            Config::string('imoger-api.base_uri'),
            $this->endpoint->value,
            filled($this->sections) ? "/$this->sections" : ''
        );
    }

    /** @noinspection PhpRedundantVariableDocTypeInspection */
    private function getToken(): string
    {
        $user = User::query()
            ->with('settings')
            ->where('id', auth()->id())
            ->firstOrFail();

        /** @var UserSettings $setting */
        $setting = $user->settings
            ->where(
                'key',
                Config::string('imoger-api.api_key_name')
            )
            ->firstOrFail();

        return $setting->value;
    }
}
