<?php

namespace App\Domain\Contracts\Repositories;

use App\Domain\Models\Assunto;
use Illuminate\Pagination\LengthAwarePaginator;

interface AssuntoRepositoryInterface
{
    public function all(int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Assunto;
    public function findByDescricao(string $descricao): ?Assunto;
    public function create(array $data): Assunto;
    public function update(int $id, array $data): Assunto;
    public function delete(int $id): bool;
}
