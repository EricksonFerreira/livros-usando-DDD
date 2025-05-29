<?php

namespace App\Domain\UseCases\Autor\DTOs;

use App\Domain\UseCases\DTOs\InputDTOInterface;

class CriarAutorInputDTO implements InputDTOInterface
{
    public function __construct(
        public string $nome
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nome: $data['nome']
        );
    }
}
