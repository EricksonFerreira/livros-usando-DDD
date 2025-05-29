<?php

namespace App\Infrastructure\Repositories\Eloquent;

use App\Domain\Contracts\Repositories\AutorRepositoryInterface;
use App\Domain\Models\Autor;
use Illuminate\Pagination\LengthAwarePaginator;

class AutorRepository implements AutorRepositoryInterface
{
    public function all(int $perPage = 15): LengthAwarePaginator
    {
        return Autor::with('livros')->paginate($perPage);
    }

    public function find(int $id): ?Autor
    {
        return Autor::with('livros')->find($id);
    }

    public function findByNome(string $nome): ?Autor
    {
        return Autor::where('nome', $nome)->first();
    }

    public function create(array $data): Autor
    {
        return Autor::create($data);
    }

    public function update(int $id, array $data): Autor
    {
        $autor = Autor::findOrFail($id);
        $autor->update($data);
        return $autor->load('livros');
    }

    public function delete(int $id): bool
    {
        $autor = Autor::findOrFail($id);
        return $autor->delete();
    }
}
