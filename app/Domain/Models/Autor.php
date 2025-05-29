<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Autor extends Model
{
    protected $fillable = [
        'nome'
    ];

    protected $table = 'autores';

    /**
     * Relacionamento muitos-para-muitos com livros
     */
    public function livros(): BelongsToMany
    {
        return $this->belongsToMany(Livro::class, 'livro_autor', 'autor_id', 'livro_id');
    }
}
