<?php

namespace App\Domain\UseCases\Autor\DTOs;

use App\Domain\UseCases\DTOs\InputDTOInterface;

class ListarAutoresInputDTO implements InputDTOInterface
{
    public function __construct(
        public int $perPage = 15,
        public int $page = 1
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            perPage: $data['perPage'] ?? 15,
            page: $data['page'] ?? 1
        );
    }
}
