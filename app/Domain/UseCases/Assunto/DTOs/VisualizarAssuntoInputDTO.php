<?php

namespace App\Domain\UseCases\Assunto\DTOs;

use App\Domain\UseCases\DTOs\InputDTOInterface;

class VisualizarAssuntoInputDTO implements InputDTOInterface
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
