<?php

namespace App\Domain\UseCases\Autor\DTOs;

use App\Domain\UseCases\DTOs\InputDTOInterface;

class AtualizarAutorInputDTO implements InputDTOInterface
{
    public function __construct(
        public int $id,
        public string $nome
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['id'],
            nome: $data['nome']
        );
    }
}
