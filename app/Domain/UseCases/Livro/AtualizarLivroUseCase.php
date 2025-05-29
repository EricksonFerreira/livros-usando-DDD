<?php

namespace App\Domain\UseCases\Livro;

use App\Domain\Contracts\Repositories\LivroRepositoryInterface;
use App\Domain\UseCases\Livro\DTOs\AtualizarLivroInputDTO;
use App\Domain\UseCases\Livro\DTOs\AtualizarLivroOutputDTO;
use App\Domain\UseCases\Livro\Exceptions\LivroAlreadyExistsException;
use App\Domain\UseCases\Livro\Exceptions\LivroNotFoundException;
use App\Domain\UseCases\UseCaseInterface;

/**
 * @implements UseCaseInterface<AtualizarLivroOutputDTO>
 */

class AtualizarLivroUseCase implements UseCaseInterface
{
    public function __construct(
        private LivroRepositoryInterface $repository
    ) {}

    public function handle(?\App\Domain\UseCases\DTOs\InputDTOInterface $data = null): AtualizarLivroOutputDTO
    {
        if ($data !== null && !$data instanceof App\Domain\UseCases\Livro\DTOs\AtualizarLivroInputDTO) {
            throw new \InvalidArgumentException('Tipo de DTO inválido');
        }


        // Verifica se o livro existe
        $livro = $this->repository->find($input->id);
        
        if (!$livro) {
            throw new LivroNotFoundException($input->id);
        }

        // Verifica se já existe outro livro com o mesmo título
        $livroExistente = $this->repository->findByTitulo($input->titulo);
        
        if ($livroExistente && $livroExistente->id !== $input->id) {
            throw new LivroAlreadyExistsException($input->titulo);
        }

        // Atualiza o livro
        $livroAtualizado = $this->repository->update($input->id, [
            'titulo' => $input->titulo,
            'editora' => $input->editora,
            'edicao' => $input->edicao,
            'ano_publicacao' => $input->ano_publicacao,
            'valor' => $input->valor,
        ]);

        // Sincroniza autores e assuntos
        $this->repository->syncAutores($input->id, $input->autores_ids);
        $this->repository->syncAssuntos($input->id, $input->assuntos_ids);

        // Carrega os relacionamentos
        $livroAtualizado->load(['autores', 'assuntos']);

        // Retorna o DTO de saída
        return new AtualizarLivroOutputDTO(
            id: $livroAtualizado->id,
            titulo: $livroAtualizado->titulo,
            editora: $livroAtualizado->editora,
            edicao: $livroAtualizado->edicao,
            ano_publicacao: $livroAtualizado->ano_publicacao,
            valor: $livroAtualizado->valor,
            autores: $livroAtualizado->autores->toArray(),
            assuntos: $livroAtualizado->assuntos->toArray(),
            updated_at: $livroAtualizado->updated_at->toDateTimeString()
        );
    }
}
