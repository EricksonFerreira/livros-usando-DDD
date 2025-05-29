<?php

namespace App\Domain\UseCases\Livro\DTOs;

use App\Domain\UseCases\DTOs\InputDTOInterface;

class AtualizarLivroInputDTO implements InputDTOInterface
{
    public function __construct(
        public int $id,
        public string $titulo,
        public string $editora,
        public int $edicao,
        public string $ano_publicacao,
        public float $valor,
        public array $autores_ids = [],
        public array $assuntos_ids = []
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['id'],
            titulo: $data['titulo'],
            editora: $data['editora'],
            edicao: (int) $data['edicao'],
            ano_publicacao: $data['ano_publicacao'],
            valor: (float) $data['valor'],
            autores_ids: $data['autores_ids'] ?? [],
            assuntos_ids: $data['assuntos_ids'] ?? []
        );
    }
}
