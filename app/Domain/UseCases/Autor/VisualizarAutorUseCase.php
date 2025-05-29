<?php

namespace App\Domain\UseCases\Autor;

use App\Domain\Contracts\Repositories\AutorRepositoryInterface;
use App\Domain\UseCases\Autor\DTOs\VisualizarAutorInputDTO;
use App\Domain\UseCases\Autor\DTOs\VisualizarAutorOutputDTO;
use App\Domain\UseCases\Autor\Exceptions\AutorNotFoundException;
use App\Domain\UseCases\UseCaseInterface;

class VisualizarAutorUseCase implements UseCaseInterface
{
    public function __construct(
        private AutorRepositoryInterface $repository
    ) {}

    public function handle(?\App\Domain\UseCases\DTOs\InputDTOInterface $data = null): VisualizarAutorOutputDTO
    {
        if ($data !== null && !$data instanceof App\Domain\UseCases\Autor\DTOs\VisualizarAutorInputDTO) {
            throw new \InvalidArgumentException('Tipo de DTO inválido');
        }


        $autor = $this->repository->find($input->id);

        if (!$autor) {
            throw new AutorNotFoundException($input->id);
        }

        return VisualizarAutorOutputDTO::fromArray([
            'id' => $autor->id,
            'nome' => $autor->nome,
            'created_at' => $autor->created_at->toDateTimeString(),
            'updated_at' => $autor->updated_at->toDateTimeString()
        ]);
    }
}
