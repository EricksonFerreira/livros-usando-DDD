<?php

namespace App\Domain\UseCases\Assunto\DTOs;

class ExcluirAssuntoOutputDTO
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
