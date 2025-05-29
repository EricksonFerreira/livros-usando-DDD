<?php

namespace App\Domain\UseCases\Livro\Exceptions;

use Exception;

class LivroNotFoundException extends Exception
{
    public function __construct(int $id)
    {
        parent::__construct("Livro com ID {$id} não encontrado.", 404);
    }
}
