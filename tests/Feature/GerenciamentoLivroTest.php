<?php

namespace Tests\Feature;

use App\Domain\Models\Livro;
use App\Domain\Models\Autor;
use App\Domain\Models\Assunto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GerenciamentoLivroTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function um_livro_pode_ser_cadastrado()
    {
        $autor = Autor::factory()->create();
        $assunto = Assunto::factory()->create();

        $resposta = $this->post('/livros', [
            'titulo' => 'Livro de Teste',
            'editora' => 'Editora Teste',
            'edicao' => 1,
            'ano_publicacao' => 2023,
            'valor' => '99,90',
            'autores' => [$autor->id],
            'assuntos' => [$assunto->id]
        ]);

        $livro = Livro::first();
        
        $this->assertCount(1, Livro::all());
        $this->assertEquals('Livro de Teste', $livro->titulo);
        $resposta->assertRedirect('/livros');
    }

    /** @test */
    public function titulo_e_obrigatorio()
    {
        $resposta = $this->post('/livros', [
            'titulo' => '',
            'editora' => 'Editora Teste'
        ]);

        $resposta->assertSessionHasErrors('titulo');
    }

    /** @test */
    public function um_livro_pode_ser_atualizado()
    {
        $livro = Livro::factory()->create();
        
        $this->patch("/livros/{$livro->id}", [
            'titulo' => 'Título Atualizado',
            'editora' => $livro->editora,
            'edicao' => $livro->edicao,
            'ano_publicacao' => $livro->ano_publicacao,
            'valor' => number_format($livro->valor, 2, ',', '.')
        ]);

        $this->assertEquals('Título Atualizado', Livro::first()->titulo);
    }

    /** @test */
    public function um_livro_pode_ser_removido()
    {
        $livro = Livro::factory()->create();
        
        $this->delete("/livros/{$livro->id}");
        
        $this->assertCount(0, Livro::all());
    }
}
