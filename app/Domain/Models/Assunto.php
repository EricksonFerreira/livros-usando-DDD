<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Assunto extends Model
{
    protected $fillable = [
        'descricao'
    ];

    protected $table = 'assuntos';

    /**
     * Relacionamento muitos-para-muitos com livros
     */
    public function livros(): BelongsToMany
    {
        return $this->belongsToMany(Livro::class, 'livro_assunto', 'assunto_id', 'livro_id');
    }
}
