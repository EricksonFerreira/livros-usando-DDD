<?php

namespace Tests\Feature;

use App\Domain\Models\LivroPorAutor;
use App\Domain\Models\Autor;
use App\Domain\Models\Assunto;
use App\Domain\Models\Livro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelatorioLivrosPorAutorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_pode_visualizar_relatorio_de_livros_por_autor()
    {
        // Criar dados de teste
        $autor1 = Autor::factory()->create(['nome' => 'Autor 1']);
        $autor2 = Autor::factory()->create(['nome' => 'Autor 2']);
        
        $assunto = Assunto::factory()->create(['descricao' => 'Ficção']);
        
        // Criar livros associados aos autores
        $livro1 = Livro::factory()->create([
            'titulo' => 'Livro 1',
            'editora' => 'Editora A',
            'ano_publicacao' => 2020,
            'valor' => 50.00
        ]);
        
        $livro2 = Livro::factory()->create([
            'titulo' => 'Livro 2',
            'editora' => 'Editora B',
            'ano_publicacao' => 2021,
            'valor' => 75.50
        ]);
        
        // Associar autores e assuntos aos livros
        $livro1->autores()->attach([$autor1->id, $autor2->id]);
        $livro1->assuntos()->attach($assunto->id);
        
        $livro2->autores()->attach($autor1->id);
        $livro2->assuntos()->attach($assunto->id);
        
        // Acessar a rota do relatório
        $response = $this->get(route('relatorios.livros-por-autor'));
        
        // Verificar se a página foi carregada com sucesso
        $response->assertStatus(200);
        $response->assertSee('Relatório de Livros por Autor');
        
        // Verificar se os dados dos livros estão sendo exibidos
        $response->assertSee('Autor 1');
        $response->assertSee('Autor 2');
        $response->assertSee('Livro 1');
        $response->assertSee('Livro 2');
        $response->assertSee('R$ 50,00');
        $response->assertSee('R$ 75,50');
    }
    
    /** @test */
    public function usuario_pode_filtrar_relatorio_por_autor()
    {
        // Criar dados de teste
        $autor1 = Autor::factory()->create(['nome' => 'Autor Teste']);
        $autor2 = Autor::factory()->create(['nome' => 'Outro Autor']);
        
        $assunto = Assunto::factory()->create();
        
        $livro1 = Livro::factory()->create(['titulo' => 'Livro do Autor Teste']);
        $livro2 = Livro::factory()->create(['titulo' => 'Outro Livro']);
        
        $livro1->autores()->attach($autor1->id);
        $livro2->autores()->attach($autor2->id);
        
        $livro1->assuntos()->attach($assunto->id);
        $livro2->assuntos()->attach($assunto->id);
        
        // Acessar a rota do relatório com filtro por autor
        $response = $this->get(route('relatorios.livros-por-autor', ['autor' => 'Teste']));
        
        // Verificar se apenas os livros do autor filtrado são exibidos
        $response->assertStatus(200);
        $response->assertSee('Autor Teste');
        $response->assertSee('Livro do Autor Teste');
        $response->assertDontSee('Outro Livro');
    }
    
    /** @test */
    public function usuario_pode_exportar_relatorio_para_pdf()
    {
        // Criar dados de teste
        $autor = Autor::factory()->create();
        $assunto = Assunto::factory()->create();
        $livro = Livro::factory()->create();
        
        $livro->autores()->attach($autor->id);
        $livro->assuntos()->attach($assunto->id);
        
        // Tentar exportar para PDF
        $response = $this->get(route('relatorios.livros-por-autor', ['export' => 'pdf']));
        
        // Verificar se o cabeçalho de resposta PDF está correto
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', 'inline; filename="relatorio-livros-por-autor-' . now()->format('Y-m-d') . '.pdf"');
    }
    
    /** @test */
    public function usuario_pode_exportar_relatorio_para_excel()
    {
        // Criar dados de teste
        $autor = Autor::factory()->create();
        $assunto = Assunto::factory()->create();
        $livro = Livro::factory()->create();
        
        $livro->autores()->attach($autor->id);
        $livro->assuntos()->attach($assunto->id);
        
        // Tentar exportar para Excel
        $response = $this->get(route('relatorios.livros-por-autor', ['export' => 'excel']));
        
        // Verificar se o cabeçalho de resposta Excel está correto
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->assertHeader('Content-Disposition', 'attachment; filename="relatorio-livros-por-autor-' . now()->format('Y-m-d') . '.xlsx"');
    }
}
