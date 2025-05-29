<?php

namespace App\Domain\UseCases\Livro;

use App\Domain\Contracts\Repositories\LivroRepositoryInterface;
use App\Domain\UseCases\Livro\DTOs\VisualizarLivroInputDTO;
use App\Domain\UseCases\Livro\DTOs\VisualizarLivroOutputDTO;
use App\Domain\UseCases\Livro\Exceptions\LivroNotFoundException;
use App\Domain\UseCases\UseCaseInterface;

/**
 * @implements UseCaseInterface<VisualizarLivroOutputDTO>
 */
class VisualizarLivroUseCase implements UseCaseInterface
{
    public function __construct(
        private LivroRepositoryInterface $repository
    ) {}

    public function handle(?\App\Domain\UseCases\DTOs\InputDTOInterface $data = null): VisualizarLivroOutputDTO
    {
        if ($data !== null && !$data instanceof App\Domain\UseCases\Livro\DTOs\VisualizarLivroInputDTO) {
            throw new \InvalidArgumentException('Tipo de DTO inválido');
        }


        $livro = $this->repository->find($input->id);
        
        if (!$livro) {
            throw new LivroNotFoundException($input->id);
        }

        // Carrega os relacionamentos
        $livro->load(['autores', 'assuntos']);

        return new VisualizarLivroOutputDTO(
            id: $livro->id,
            titulo: $livro->titulo,
            editora: $livro->editora,
            edicao: $livro->edicao,
            ano_publicacao: $livro->ano_publicacao,
            valor: $livro->valor,
            autores: $livro->autores->toArray(),
            assuntos: $livro->assuntos->toArray(),
            created_at: $livro->created_at->toDateTimeString(),
            updated_at: $livro->updated_at->toDateTimeString()
        );
    }
}
