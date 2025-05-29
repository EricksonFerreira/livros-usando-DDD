<?php

namespace App\Domain\UseCases\Assunto\DTOs;

use App\Domain\UseCases\DTOs\InputDTOInterface;

class ListarAssuntosInputDTO implements InputDTOInterface
{
    public function __construct(
        public int $perPage,
        public int $page
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            perPage: (int) ($data['per_page'] ?? 15),
            page: (int) ($data['page'] ?? 1)
        );
    }
}
