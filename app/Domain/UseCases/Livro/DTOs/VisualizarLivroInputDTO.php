<?php

namespace App\Domain\UseCases\Livro\DTOs;

use App\Domain\UseCases\DTOs\InputDTOInterface;

class VisualizarLivroInputDTO implements InputDTOInterface
{
    public function __construct(
        public int $id
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['id']
        );
    }
}
