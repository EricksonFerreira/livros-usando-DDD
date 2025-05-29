<?php

namespace App\Domain\Contracts\Repositories;

use App\Domain\Models\Livro;
use Illuminate\Pagination\LengthAwarePaginator;

interface LivroRepositoryInterface
{
    public function query();
    
    public function all(int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Livro;
    public function findByTitulo(string $titulo): ?Livro;
    public function create(array $data): Livro;
    public function update(int $id, array $data): Livro;
    public function delete(int $id): bool;
    public function attachAutores(int $livroId, array $autoresIds): void;
    public function attachAssuntos(int $livroId, array $assuntosIds): void;
    public function syncAutores(int $livroId, array $autoresIds): void;
    public function syncAssuntos(int $livroId, array $assuntosIds): void;
}
