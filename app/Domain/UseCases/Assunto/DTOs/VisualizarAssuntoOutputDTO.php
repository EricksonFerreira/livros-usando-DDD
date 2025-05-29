<?php

namespace App\Domain\UseCases\Assunto\DTOs;

class VisualizarAssuntoOutputDTO
{
    public function __construct(
        public int $id,
        public string $descricao,
        public string $created_at,
        public string $updated_at
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            descricao: $data['descricao'],
            created_at: $data['created_at'],
            updated_at: $data['updated_at']
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'descricao' => $this->descricao,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
