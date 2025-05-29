<?php

namespace App\Infrastructure\Repositories\Eloquent;

use App\Domain\Contracts\Repositories\AssuntoRepositoryInterface;
use App\Domain\Models\Assunto;
use Illuminate\Pagination\LengthAwarePaginator;

class AssuntoRepository implements AssuntoRepositoryInterface
{
    public function query()
    {
        return Assunto::query();
    }
    
    public function all(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()->paginate($perPage);
    }

    public function find(int $id): ?Assunto
    {
        return $this->query()->with('livros')->find($id);
    }

    public function findByDescricao(string $descricao): ?Assunto
    {
        return $this->query()->where('descricao', $descricao)->first();
    }

    public function create(array $data): Assunto
    {
        return $this->query()->create($data);
    }

    public function update(int $id, array $data): Assunto
    {
        $assunto = $this->query()->findOrFail($id);
        $assunto->update($data);
        return $assunto->load('livros');
    }

    public function delete(int $id): bool
    {
        $assunto = $this->query()->findOrFail($id);
        return $assunto->delete();
    }
}
