<?php

namespace App\Infrastructure\Repositories\Eloquent;

use App\Domain\Contracts\Repositories\LivroRepositoryInterface;
use App\Domain\Models\Livro;
use Illuminate\Pagination\LengthAwarePaginator;

class LivroRepository implements LivroRepositoryInterface
{
    public function query()
    {
        return Livro::query();
    }

    public function all(int $perPage = 15): LengthAwarePaginator
    {
        $livros = $this->query()->with(['autores', 'assuntos'])->paginate($perPage);
        return $livros;
    }

    public function findWithRelations(int $id, array $relations = []): ?Livro
    {
        return $this->query()->with($relations)->find($id);
    }

    public function find(int $id): ?Livro
    {
        return $this->query()->with(['autores', 'assuntos'])->find($id);
    }

    public function findByTitulo(string $titulo): ?Livro
    {
        return $this->query()->where('titulo', $titulo)->first();
    }

    public function create(array $data): Livro
    {
        return $this->query()->create($data);
    }

    public function update(int $id, array $data): Livro
    {
        $livro = $this->query()->findOrFail($id);
        $livro->update($data);
        return $livro->load(['autores', 'assuntos']);
    }

    public function delete(int $id): bool
    {
        $livro = $this->query()->findOrFail($id);
        return $livro->delete();
    }

    public function attachAutores(int $livroId, array $autoresIds): void
    {
        $livro = $this->query()->findOrFail($livroId);
        $livro->autores()->attach($autoresIds);
    }

    public function attachAssuntos(int $livroId, array $assuntosIds): void
    {
        $livro = $this->query()->findOrFail($livroId);
        $livro->assuntos()->attach($assuntosIds);
    }

    public function syncAutores(int $livroId, array $autoresIds): void
    {
        $livro = $this->query()->findOrFail($livroId);
        $livro->autores()->sync($autoresIds);
    }

    public function syncAssuntos(int $livroId, array $assuntosIds): void
    {
        $livro = $this->query()->findOrFail($livroId);
        $livro->assuntos()->sync($assuntosIds);
    }
}
