<?php

namespace App\Domain\UseCases\Assunto;

use App\Domain\Contracts\Repositories\AssuntoRepositoryInterface;
use App\Domain\UseCases\Assunto\DTOs\ListarAssuntosInputDTO;
use App\Domain\UseCases\Assunto\DTOs\ListarAssuntosOutputDTO;
use App\Domain\UseCases\UseCaseInterface;

class ListarAssuntosUseCase implements UseCaseInterface
{
    public function __construct(
        private AssuntoRepositoryInterface $repository
    ) {}

    public function handle(?\App\Domain\UseCases\DTOs\InputDTOInterface $data = null): ListarAssuntosOutputDTO
    {
        if ($data !== null && !$data instanceof App\Domain\UseCases\Assunto\DTOs\ListarAssuntosInputDTO) {
            throw new \InvalidArgumentException('Tipo de DTO inválido');
        }


        $assuntos = $this->repository->all($input->perPage);
        
        return ListarAssuntosOutputDTO::fromPaginator($assuntos);
    }
}
