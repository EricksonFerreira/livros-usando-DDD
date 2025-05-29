<?php

namespace App\Domain\UseCases\Livro\DTOs;

class AtualizarLivroOutputDTO
{
    public function __construct(
        public int $id,
        public string $titulo,
        public string $editora,
        public int $edicao,
        public string $ano_publicacao,
        public float $valor,
        public array $autores,
        public array $assuntos,
        public string $updated_at
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            titulo: $data['titulo'],
            editora: $data['editora'],
            edicao: $data['edicao'],
            ano_publicacao: $data['ano_publicacao'],
            valor: $data['valor'],
            autores: $data['autores'] ?? [],
            assuntos: $data['assuntos'] ?? [],
            updated_at: $data['updated_at']
        );
    }
}
