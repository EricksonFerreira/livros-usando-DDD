<?php

namespace App\Domain\UseCases\Assunto\Exceptions;

use Exception;

class AssuntoAlreadyExistsException extends Exception
{
    public function __construct(string $descricao)
    {
        parent::__construct("O assunto '{$descricao}' já está cadastrado.");
    }
}
