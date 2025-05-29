<?php

namespace App\Domain\UseCases\Livro\DTOs;

class VisualizarLivroOutputDTO
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
        public string $created_at,
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
            created_at: $data['created_at'],
            updated_at: $data['updated_at']
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'editora' => $this->editora,
            'edicao' => $this->edicao,
            'ano_publicacao' => $this->ano_publicacao,
            'valor' => $this->valor,
            'autores' => $this->autores,
            'assuntos' => $this->assuntos,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
