<?php

namespace App\Domain\UseCases\Assunto;

use App\Domain\Contracts\Repositories\AssuntoRepositoryInterface;
use App\Domain\UseCases\Assunto\DTOs\AtualizarAssuntoInputDTO;
use App\Domain\UseCases\Assunto\DTOs\AtualizarAssuntoOutputDTO;
use App\Domain\UseCases\Assunto\Exceptions\AssuntoAlreadyExistsException;
use App\Domain\UseCases\Assunto\Exceptions\AssuntoNotFoundException;
use App\Domain\UseCases\UseCaseInterface;

/**
 * @implements UseCaseInterface<AtualizarAssuntoOutputDTO>
 */

class AtualizarAssuntoUseCase implements UseCaseInterface
{
    public function __construct(
        private AssuntoRepositoryInterface $repository
    ) {}

    public function handle(?\App\Domain\UseCases\DTOs\InputDTOInterface $data = null): AtualizarAssuntoOutputDTO
    {
        if ($data !== null && !$data instanceof App\Domain\UseCases\Assunto\DTOs\AtualizarAssuntoInputDTO) {
            throw new \InvalidArgumentException('Tipo de DTO inválido');
        }


        // Verifica se o assunto existe
        $assunto = $this->repository->find($input->id);
        
        if (!$assunto) {
            throw new AssuntoNotFoundException($input->id);
        }

        // Verifica se já existe outro assunto com a mesma descrição
        $assuntoExistente = $this->repository->findByDescricao($input->descricao);
        
        if ($assuntoExistente && $assuntoExistente->id !== $input->id) {
            throw new AssuntoAlreadyExistsException($input->descricao);
        }

        // Atualiza o assunto
        $assuntoAtualizado = $this->repository->update($input->id, [
            'descricao' => $input->descricao
        ]);

        // Retorna o DTO de saída
        return new AtualizarAssuntoOutputDTO(
            id: $assuntoAtualizado->id,
            descricao: $assuntoAtualizado->descricao,
            updated_at: $assuntoAtualizado->updated_at->toDateTimeString()
        );
    }
}
