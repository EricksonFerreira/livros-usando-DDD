<?php

namespace App\Domain\UseCases\Assunto\DTOs;

class AtualizarAssuntoOutputDTO
{
    public function __construct(
        public int $id,
        public string $descricao,
        public string $updated_at
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            descricao: $data['descricao'],
            updated_at: $data['updated_at']
        );
    }
}
