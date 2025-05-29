<?php

namespace App\Domain\UseCases\Assunto;

use App\Domain\Contracts\Repositories\AssuntoRepositoryInterface;
use App\Domain\UseCases\Assunto\DTOs\CriarAssuntoInputDTO;
use App\Domain\UseCases\Assunto\DTOs\CriarAssuntoOutputDTO;
use App\Domain\UseCases\Assunto\Exceptions\AssuntoAlreadyExistsException;
use App\Domain\UseCases\UseCaseInterface;

/**
 * @implements UseCaseInterface<CriarAssuntoOutputDTO>
 */

class CriarAssuntoUseCase implements UseCaseInterface
{
    public function __construct(
        private AssuntoRepositoryInterface $repository
    ) {}

    public function handle(?\App\Domain\UseCases\DTOs\InputDTOInterface $data = null): CriarAssuntoOutputDTO
    {
        if ($data !== null && !$data instanceof App\Domain\UseCases\Assunto\DTOs\CriarAssuntoInputDTO) {
            throw new \InvalidArgumentException('Tipo de DTO inválido');
        }


        // Verifica se já existe um assunto com a mesma descrição
        $assuntoExistente = $this->repository->findByDescricao($input->descricao);
        
        if ($assuntoExistente) {
            throw new AssuntoAlreadyExistsException($input->descricao);
        }

        // Cria o novo assunto
        $assunto = $this->repository->create([
            'descricao' => $input->descricao
        ]);

        // Retorna o DTO de saída
        return new CriarAssuntoOutputDTO(
            id: $assunto->id,
            descricao: $assunto->descricao,
            created_at: $assunto->created_at->toDateTimeString(),
            updated_at: $assunto->updated_at->toDateTimeString()
        );
    }
}
