<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class LivroPorAutor extends Model
{
    /**
     * O nome da view associada ao modelo.
     *
     * @var string
     */
    protected $table = 'livros_por_autor';

    /**
     * Indica se o modelo deve ser marcado como não tendo timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Os atributos que podem ser preenchidos em massa.
     *
     * @var array
     */
    protected $fillable = [
        'autor_id',
        'autor_nome',
        'livro_id',
        'livro_titulo',
        'livro_editora',
        'livro_edicao',
        'livro_ano',
        'livro_valor',
        'assuntos',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'livro_edicao' => 'integer',
        'livro_valor' => 'decimal:2',
    ];
}
