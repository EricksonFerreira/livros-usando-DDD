<?php

namespace App\Domain\UseCases\Livro;

use App\Domain\Contracts\Repositories\LivroRepositoryInterface;
use App\Domain\UseCases\Livro\DTOs\CriarLivroInputDTO;
use App\Domain\UseCases\Livro\DTOs\CriarLivroOutputDTO;
use App\Domain\UseCases\Livro\Exceptions\LivroAlreadyExistsException;
use App\Domain\UseCases\UseCaseInterface;

/**
 * @implements UseCaseInterface<CriarLivroOutputDTO>
 */

class CriarLivroUseCase implements UseCaseInterface
{
    public function __construct(
        private LivroRepositoryInterface $repository
    ) {}

    public function handle(?\App\Domain\UseCases\DTOs\InputDTOInterface $data = null): CriarLivroOutputDTO
    {
        if ($data !== null && !$data instanceof App\Domain\UseCases\Livro\DTOs\CriarLivroInputDTO) {
            throw new \InvalidArgumentException('Tipo de DTO inválido');
        }


        // Verifica se já existe um livro com o mesmo título
        $livroExistente = $this->repository->findByTitulo($input->titulo);
        
        if ($livroExistente) {
            throw new LivroAlreadyExistsException($input->titulo);
        }

        // Cria o novo livro
        $livro = $this->repository->create([
            'titulo' => $input->titulo,
            'editora' => $input->editora,
            'edicao' => $input->edicao,
            'ano_publicacao' => $input->ano_publicacao,
            'valor' => $input->valor,
        ]);

        // Associa os autores e assuntos
        if (!empty($input->autores_ids)) {
            $this->repository->attachAutores($livro->id, $input->autores_ids);
        }

        if (!empty($input->assuntos_ids)) {
            $this->repository->attachAssuntos($livro->id, $input->assuntos_ids);
        }

        // Carrega os relacionamentos
        $livro->load(['autores', 'assuntos']);

        // Retorna o DTO de saída
        return new CriarLivroOutputDTO(
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
