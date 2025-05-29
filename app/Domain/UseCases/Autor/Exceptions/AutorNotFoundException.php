<?php

namespace App\Domain\UseCases\Autor\Exceptions;

use Exception;

class AutorNotFoundException extends Exception
{
    public function __construct(int $id)
    {
        parent::__construct("Autor com ID {$id} não encontrado.", 404);
    }
}
