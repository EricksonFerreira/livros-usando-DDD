<?php

namespace App\Domain\UseCases\Livro\DTOs;

use Illuminate\Pagination\LengthAwarePaginator;

class ListarLivrosOutputDTO
{
    public function __construct(
        public array $data,
        public int $total,
        public int $perPage,
        public int $currentPage,
        public int $lastPage
    ) {}

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        return new self(
            data: $paginator->items(),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
            lastPage: $paginator->lastPage()
        );
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'meta' => [
                'total' => $this->total,
                'per_page' => $this->perPage,
                'current_page' => $this->currentPage,
                'last_page' => $this->lastPage,
            ]
        ];
    }
}
