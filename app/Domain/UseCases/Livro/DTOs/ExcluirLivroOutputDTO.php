<?php

namespace App\Domain\UseCases\Livro\DTOs;

class ExcluirLivroOutputDTO
{
    public function __construct(
        public bool $success,
        public string $message
    ) {}

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'message' => $this->message
        ];
    }
}
