<?php

namespace App\Domain\UseCases\Assunto;

use App\Domain\Contracts\Repositories\AssuntoRepositoryInterface;
use App\Domain\UseCases\Assunto\DTOs\VisualizarAssuntoInputDTO;
use App\Domain\UseCases\Assunto\DTOs\VisualizarAssuntoOutputDTO;
use App\Domain\UseCases\Assunto\Exceptions\AssuntoNotFoundException;
use App\Domain\UseCases\UseCaseInterface;

/**
 * @implements UseCaseInterface<VisualizarAssuntoOutputDTO>
 */
class VisualizarAssuntoUseCase implements UseCaseInterface
{
    public function __construct(
        private AssuntoRepositoryInterface $repository
    ) {}

    public function handle(?\App\Domain\UseCases\DTOs\InputDTOInterface $data = null): VisualizarAssuntoOutputDTO
    {
        if ($data !== null && !$data instanceof App\Domain\UseCases\Assunto\DTOs\VisualizarAssuntoInputDTO) {
            throw new \InvalidArgumentException('Tipo de DTO inválido');
        }


        $assunto = $this->repository->find($input->id);
        
        if (!$assunto) {
            throw new AssuntoNotFoundException($input->id);
        }

        return new VisualizarAssuntoOutputDTO(
            id: $assunto->id,
            descricao: $assunto->descricao,
            created_at: $assunto->created_at->toDateTimeString(),
            updated_at: $assunto->updated_at->toDateTimeString()
        );
    }
}
