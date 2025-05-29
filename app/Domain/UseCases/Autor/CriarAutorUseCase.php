<?php

namespace App\Domain\UseCases\Autor;

use App\Domain\Contracts\Repositories\AutorRepositoryInterface;
use App\Domain\UseCases\Autor\DTOs\CriarAutorInputDTO;
use App\Domain\UseCases\Autor\DTOs\CriarAutorOutputDTO;
use App\Domain\UseCases\Autor\Exceptions\AutorAlreadyExistsException;
use App\Domain\UseCases\UseCaseInterface;

/**
 * @implements UseCaseInterface<CriarAutorOutputDTO>
 */

class CriarAutorUseCase implements UseCaseInterface
{
    public function __construct(
        private AutorRepositoryInterface $repository
    ) {}

    /**
     * @implements UseCaseInterface<CriarAutorOutputDTO>
     */
    public function handle(?\App\Domain\UseCases\DTOs\InputDTOInterface $input = null): CriarAutorOutputDTO
    {
        if (!$input instanceof CriarAutorInputDTO) {
            throw new \InvalidArgumentException('Tipo de DTO inválido');
        }

        // Verifica se já existe um autor com o mesmo nome
        $autorExistente = $this->repository->findByNome($input->nome);
        
        if ($autorExistente) {
            throw new AutorAlreadyExistsException($input->nome);
        }

        // Cria o autor
        $autor = $this->repository->create([
            'nome' => $input->nome
        ]);

        // Retorna o DTO de saída
        return new CriarAutorOutputDTO(
            id: $autor->id,
            nome: $autor->nome,
            created_at: $autor->created_at->toDateTimeString(),
            updated_at: $autor->updated_at->toDateTimeString()
        );
    }
}
