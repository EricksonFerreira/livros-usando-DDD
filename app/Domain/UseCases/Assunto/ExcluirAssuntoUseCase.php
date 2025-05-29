<?php

namespace App\Domain\UseCases\Assunto;

use App\Domain\Contracts\Repositories\AssuntoRepositoryInterface;
use App\Domain\UseCases\Assunto\DTOs\ExcluirAssuntoInputDTO;
use App\Domain\UseCases\Assunto\DTOs\ExcluirAssuntoOutputDTO;
use App\Domain\UseCases\Assunto\Exceptions\AssuntoNotFoundException;
use App\Domain\UseCases\UseCaseInterface;

class ExcluirAssuntoUseCase implements UseCaseInterface
{
    public function __construct(
        private AssuntoRepositoryInterface $repository
    ) {}

    public function handle(?\App\Domain\UseCases\DTOs\InputDTOInterface $data = null): ExcluirAssuntoOutputDTO
    {
        if ($data !== null && !$data instanceof App\Domain\UseCases\Assunto\DTOs\ExcluirAssuntoInputDTO) {
            throw new \InvalidArgumentException('Tipo de DTO inválido');
        }


        // Verifica se o assunto existe
        $assunto = $this->repository->find($input->id);
        
        if (!$assunto) {
            throw new AssuntoNotFoundException($input->id);
        }

        // Exclui o assunto
        $this->repository->delete($input->id);

        // Retorna o DTO de saída
        return new ExcluirAssuntoOutputDTO(
            success: true,
            message: 'Assunto excluído com sucesso.'
        );
    }
}
