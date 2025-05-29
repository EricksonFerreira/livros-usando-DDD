<?php

namespace App\Domain\UseCases\Autor;

use App\Domain\Contracts\Repositories\AutorRepositoryInterface;
use App\Domain\UseCases\Autor\DTOs\ExcluirAutorInputDTO;
use App\Domain\UseCases\Autor\DTOs\ExcluirAutorOutputDTO;
use App\Domain\UseCases\Autor\Exceptions\AutorNotFoundException;
use App\Domain\UseCases\UseCaseInterface;

/**
 * @implements UseCaseInterface<ExcluirAutorOutputDTO>
 */
class ExcluirAutorUseCase implements UseCaseInterface
{
    public function __construct(
        private AutorRepositoryInterface $repository
    ) {}

    public function handle(?\App\Domain\UseCases\DTOs\InputDTOInterface $data = null): ExcluirAutorOutputDTO
    {
        if ($data !== null && !$data instanceof App\Domain\UseCases\Autor\DTOs\ExcluirAutorInputDTO) {
            throw new \InvalidArgumentException('Tipo de DTO inválido');
        }


        // Verifica se o autor existe
        $autor = $this->repository->find($input->id);
        
        if (!$autor) {
            throw new AutorNotFoundException($input->id);
        }

        // Exclui o autor
        $this->repository->delete($input->id);

        // Retorna o DTO de saída
        return new ExcluirAutorOutputDTO(
            success: true,
            message: 'Autor excluído com sucesso.'
        );
    }
}
