<?php

namespace App\Domain\UseCases\Livro\DTOs;

use App\Domain\UseCases\DTOs\InputDTOInterface;

class ListarLivrosInputDTO implements InputDTOInterface
{
    public function __construct(
        public int $perPage,
        public int $page,
        public ?string $search = null,
        public ?int $autor_id = null,
        public ?int $assunto_id = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            perPage: (int) ($data['per_page'] ?? 15),
            page: (int) ($data['page'] ?? 1),
            search: $data['search'] ?? null,
            autor_id: isset($data['autor_id']) ? (int) $data['autor_id'] : null,
            assunto_id: isset($data['assunto_id']) ? (int) $data['assunto_id'] : null
        );
    }
}
