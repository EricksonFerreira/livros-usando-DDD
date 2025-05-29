<?php

namespace App\Domain\UseCases\Livro;

use App\Domain\Contracts\Repositories\LivroRepositoryInterface;
use App\Domain\UseCases\Livro\DTOs\ListarLivrosInputDTO;
use App\Domain\UseCases\Livro\DTOs\ListarLivrosOutputDTO;
use App\Domain\UseCases\DTOs\InputDTOInterface;
use App\Domain\UseCases\UseCaseInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ListarLivrosUseCase implements UseCaseInterface
{
    public function __construct(
        private LivroRepositoryInterface $repository
    ) {}

    public function handle(?InputDTOInterface $data = null): ListarLivrosOutputDTO
    {
        if ($data === null) {
            $data = new ListarLivrosInputDTO(
                perPage: 10,
                page: 1,
                search: null,
                autor_id: null,
                assunto_id: null
            );
        }

        if (!$data instanceof ListarLivrosInputDTO) {
            throw new \InvalidArgumentException('Tipo de DTO inválido');
        }

        $query = $this->repository->query()
            ->with(['autores', 'assuntos']);

        // Aplica filtros
        if (!empty($data->search)) {
            $query->where(function($q) use ($data) {
                $q->where('titulo', 'like', "%{$data->search}%")
                  ->orWhere('editora', 'like', "%{$data->search}%");
            });
        }

        if (!empty($data->autor_id)) {
            $query->whereHas('autores', function($q) use ($data) {
                $q->where('autores.id', $data->autor_id);
            });
        }

        if (!empty($data->assunto_id)) {
            $query->whereHas('assuntos', function($q) use ($data) {
                $q->where('assuntos.id', $data->assunto_id);
            });
        }

        // Executa a consulta paginada
        $livros = $query->paginate($data->perPage, ['*'], 'page', $data->page);

        return ListarLivrosOutputDTO::fromPaginator($livros);
    }
}
