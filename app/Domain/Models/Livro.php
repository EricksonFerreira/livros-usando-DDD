<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Livro extends Model
{
    protected $fillable = [
        'titulo',
        'editora',
        'edicao',
        'ano_publicacao',
        'valor'
    ];

    protected $table = 'livros';

    /**
     * Relacionamento muitos-para-muitos com autores
     */
    public function autores(): BelongsToMany
    {
        return $this->belongsToMany(Autor::class, 'livro_autor', 'livro_id', 'autor_id');
    }

    /**
     * Relacionamento muitos-para-muitos com assuntos
     */
    public function assuntos(): BelongsToMany
    {
        return $this->belongsToMany(Assunto::class, 'livro_assunto', 'livro_id', 'assunto_id');
    }
}
