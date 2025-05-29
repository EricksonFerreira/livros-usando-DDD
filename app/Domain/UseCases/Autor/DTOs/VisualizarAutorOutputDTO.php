<?php

namespace App\Domain\UseCases\Autor\DTOs;

class VisualizarAutorOutputDTO
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

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
