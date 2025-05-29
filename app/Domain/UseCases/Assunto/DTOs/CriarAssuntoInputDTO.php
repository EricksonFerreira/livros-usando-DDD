<?php

namespace App\Domain\UseCases\Assunto\DTOs;

use App\Domain\UseCases\DTOs\InputDTOInterface;

class CriarAssuntoInputDTO implements InputDTOInterface
{
    public function __construct(
        public string $descricao
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            descricao: $data['descricao']
        );
    }
}
