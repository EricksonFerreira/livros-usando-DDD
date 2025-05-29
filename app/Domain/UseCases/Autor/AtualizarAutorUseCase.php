<?php

namespace App\Domain\UseCases\Autor;

use App\Domain\Contracts\Repositories\AutorRepositoryInterface;
use App\Domain\UseCases\Autor\DTOs\AtualizarAutorInputDTO;
use App\Domain\UseCases\Autor\DTOs\AtualizarAutorOutputDTO;
use App\Domain\UseCases\Autor\Exceptions\AutorAlreadyExistsException;
use App\Domain\UseCases\Autor\Exceptions\AutorNotFoundException;
use App\Domain\UseCases\UseCaseInterface;

/**
 * @implements UseCaseInterface<AtualizarAutorOutputDTO>
 */
class AtualizarAutorUseCase implements UseCaseInterface
{
    public function __construct(
        private AutorRepositoryInterface $repository
    ) {}

    public function handle(?\App\Domain\UseCases\DTOs\InputDTOInterface $data = null): AtualizarAutorOutputDTO
    {
        if (!$data instanceof AtualizarAutorInputDTO) {
            throw new \InvalidArgumentException('O parâmetro deve ser uma instância de AtualizarAutorInputDTO');
        }

        // Verifica se o autor existe
        $autor = $this->repository->find($data->id);
        
        if (!$autor) {
            throw new AutorNotFoundException($data->id);
        }

        // Verifica se já existe outro autor com o mesmo nome
        $autorExistente = $this->repository->findByNome($data->nome);
        
        if ($autorExistente && $autorExistente->id !== $data->id) {
            throw new AutorAlreadyExistsException($data->nome);
        }

        // Atualiza o autor
        $autorAtualizado = $this->repository->update($data->id, [
            'nome' => $data->nome
        ]);

        // Retorna o DTO de saída
        return new AtualizarAutorOutputDTO(
            id: $autorAtualizado->id,
            nome: $autorAtualizado->nome,
            updated_at: $autorAtualizado->updated_at->toDateTimeString()
        );
    }
}
