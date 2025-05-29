<?php

namespace App\Domain\UseCases\Autor;

use App\Domain\Contracts\Repositories\AutorRepositoryInterface;
use App\Domain\UseCases\Autor\DTOs\ListarAutoresOutputDTO;
use App\Domain\UseCases\UseCaseInterface;
use App\Domain\UseCases\DTOs\InputDTOInterface;

class ListarAutoresUseCase implements UseCaseInterface
{
    public function __construct(
        private AutorRepositoryInterface $repository
    ) {}

    public function handle(?InputDTOInterface $data = null): ListarAutoresOutputDTO
    {
        if ($data === null) {
            $data = new ListarAutoresInputDTO(
                perPage: 10,
                page: 1,
                search: null
            );
        }

        if (!$data instanceof ListarAutoresInputDTO) {
            throw new \InvalidArgumentException('Tipo de DTO inválido');
        }

        $query = $this->repository->query()
            ->with(['livros']);

        $autores = $query->paginate( $data->perPage, ['*'], 'page', $data->page );

        return ListarAutoresOutputDTO::fromPaginator($autores);
    }
}
