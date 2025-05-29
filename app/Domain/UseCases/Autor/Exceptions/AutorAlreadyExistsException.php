<?php

namespace App\Domain\UseCases\Autor\Exceptions;

use Exception;

class AutorAlreadyExistsException extends Exception
{
    public function __construct(string $nome)
    {
        parent::__construct("O autor '{$nome}' já está cadastrado.");
    }
}
