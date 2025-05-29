<?php

namespace App\Domain\Contracts\Repositories;

use App\Domain\Models\Autor;
use Illuminate\Pagination\LengthAwarePaginator;

interface AutorRepositoryInterface
{
    public function all(int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Autor;
    public function findByNome(string $nome): ?Autor;
    public function create(array $data): Autor;
    public function update(int $id, array $data): Autor;
    public function delete(int $id): bool;
}
