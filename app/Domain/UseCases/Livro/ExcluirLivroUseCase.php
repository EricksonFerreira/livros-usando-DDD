<?php

namespace App\Domain\UseCases\Livro;

use App\Domain\Contracts\Repositories\LivroRepositoryInterface;
use App\Domain\UseCases\Livro\DTOs\ExcluirLivroInputDTO;
use App\Domain\UseCases\Livro\DTOs\ExcluirLivroOutputDTO;
use App\Domain\UseCases\Livro\Exceptions\LivroNotFoundException;
use App\Domain\UseCases\UseCaseInterface;

class ExcluirLivroUseCase implements UseCaseInterface
{
    public function __construct(
        private LivroRepositoryInterface $repository
    ) {}

    public function handle(?\App\Domain\UseCases\DTOs\InputDTOInterface $data = null): ExcluirLivroOutputDTO
    {
        if ($data !== null && !$data instanceof App\Domain\UseCases\Livro\DTOs\ExcluirLivroInputDTO) {
            throw new \InvalidArgumentException('Tipo de DTO inválido');
        }


        // Verifica se o livro existe
        $livro = $this->repository->find($input->id);
        
        if (!$livro) {
            throw new LivroNotFoundException($input->id);
        }

        // Exclui o livro (os relacionamentos serão excluídos em cascata)
        $this->repository->delete($input->id);

        // Retorna o DTO de saída
        return new ExcluirLivroOutputDTO(
            success: true,
            message: 'Livro excluído com sucesso.'
        );
    }
}
