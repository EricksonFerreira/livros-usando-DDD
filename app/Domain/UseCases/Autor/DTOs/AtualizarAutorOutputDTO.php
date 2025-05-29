<?php

namespace App\Domain\UseCases\Autor\DTOs;

class AtualizarAutorOutputDTO
{
    public function __construct(
        public int $id,
        public string $nome,
        public string $updated_at
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            nome: $data['nome'],
            updated_at: $data['updated_at']
        );
    }
}
