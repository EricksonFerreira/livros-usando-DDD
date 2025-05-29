<?php

namespace App\Domain\UseCases\Autor\DTOs;

class CriarAutorOutputDTO
{
    public function __construct(
        public int $id,
        public string $nome,
        public string $created_at,
        public string $updated_at
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            nome: $data['nome'],
            created_at: $data['created_at'],
            updated_at: $data['updated_at']
        );
    }
}
