<?php

declare(strict_types=1);

namespace App\Services;

use App\Dtos\PrompterApiRandomItem;
use App\Enums\PrompterApiEndpoints;
use App\Libraries\PrompterApiLibrary;
use Exception;

final readonly class PrompterService
{
    public function __construct(
        private PrompterApiLibrary $library,
    ) {}

    /**
     * @throws Exception
     */
    public function random(): PrompterApiRandomItem
    {
        return $this->library->get(PrompterApiEndpoints::RANDOM);
    }
}
