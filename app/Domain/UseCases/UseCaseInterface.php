<?php

namespace App\Domain\UseCases;

use App\Domain\UseCases\DTOs\InputDTOInterface;

/**
 * @template TOutput
 */
interface UseCaseInterface
{
    /**
     * Executa o caso de uso
     * 
     * @param InputDTOInterface|null $data Dados de entrada para o caso de uso
     * @return TOutput Resultado do caso de uso
     */
    public function handle(?InputDTOInterface $data = null);
}
