<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sql = <<<SQL
        CREATE VIEW livros_por_autor AS
        SELECT 
            a.id as autor_id,
            a.nome as autor,
            l.id as livro_id,
            l.titulo as livro,
            l.editora as editora,
            l.edicao as edicao,
            l.ano_publicacao as ano,
            l.valor as valor,
            GROUP_CONCAT(DISTINCT ass.descricao ORDER BY ass.descricao SEPARATOR ', ') as assuntos
        FROM autores a
        JOIN livro_autor la ON a.id = la.autor_id
        JOIN livros l ON la.livro_id = l.id
        LEFT JOIN livro_assunto las ON l.id = las.livro_id
        LEFT JOIN assuntos ass ON las.assunto_id = ass.id
        GROUP BY a.id, a.nome, l.id, l.titulo, l.editora, l.edicao, l.ano_publicacao, l.valor
        ORDER BY a.nome, l.titulo;
        SQL;

        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS livros_por_autor');
    }
};
