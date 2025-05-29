<?php

namespace App\Domain\UseCases\Livro\Exceptions;

use Exception;

class LivroAlreadyExistsException extends Exception
{
    public function __construct(string $titulo)
    {
        parent::__construct("Já existe um livro cadastrado com o título '{$titulo}'.");
    }
}
