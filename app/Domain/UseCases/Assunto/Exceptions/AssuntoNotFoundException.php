<?php

namespace App\Domain\UseCases\Assunto\Exceptions;

use Exception;

class AssuntoNotFoundException extends Exception
{
    public function __construct(int $id)
    {
        parent::__construct("Assunto com ID {$id} não encontrado.", 404);
    }
}
