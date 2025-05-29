<?php

namespace App\Domain\UseCases\Autor\DTOs;

class ExcluirAutorOutputDTO
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
