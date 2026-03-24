<?php

declare(strict_types=1);

namespace App\Dtos;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use stdClass;

final class ImogerApiResponse extends Data
{
    /**
     * @param  Collection<ImogerImageItem>  $images
     */
    public function __construct(
        public readonly Collection $images,
        public readonly int $count,
    ) {}

    public static function parseResponse(stdClass|array $data): self
    {
        if ($data instanceof stdClass) {
            return new self(
                images: collect([
                    ImogerImageItem::from($data->attributes)
                ]),
                count: 1
            );
        }

        $items = collect();
        foreach ($data as $item) {
            $items->push(
                ImogerImageItem::from($item->attributes)
            );
        }

        return new self(
            images: $items,
            count: $items->count(),
        );
    }
}
