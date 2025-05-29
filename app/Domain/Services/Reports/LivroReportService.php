<?php

namespace App\Domain\Services\Reports;

use App\Domain\Contracts\Reports\ReportAdapterInterface;
use App\Domain\Models\LivroPorAutor;

class LivroReportService
{
    /**
     * Adaptador de relatório
     *
     * @var ReportAdapterInterface
     */
    protected $adapter;
    
    /**
     * Construtor do serviço de relatório
     * 
     * @param ReportAdapterInterface $adapter
     */
    public function __construct(ReportAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }
    
    /**
     * Gera o relatório de livros por autor
     * 
     * @param array $filters Filtros opcionais para o relatório
     * @return mixed
     */
    public function generateLivrosPorAutorReport(array $filters = [])
    {
        // Consulta os dados da view
        $query = LivroPorAutor::query();
        
        // Aplica filtros, se fornecidos
        if (!empty($filters['ano_publicacao'])) {
            $query->where('livro_ano', $filters['ano_publicacao']);
        }
        
        if (!empty($filters['assunto'])) {
            $query->where('assuntos', 'like', '%' . $filters['assunto'] . '%');
        }
        
        // Ordena por autor e título do livro
        $livros = $query->orderBy('autor_nome')
                       ->orderBy('livro_titulo')
                       ->get()
                       ->toArray();
        
        // Configura o relatório
        $this->adapter
            ->setTitle('Relatório de Livros por Autor')
            ->setColumns([
                'livro_titulo' => 'Título',
                'livro_editora' => 'Editora',
                'livro_edicao' => 'Edição',
                'livro_ano' => 'Ano',
                'livro_valor' => 'Valor (R$)',
                'assuntos' => 'Assuntos',
            ]);
        
        // Gera e retorna o relatório
        return $this->adapter->generate($livros, [
            'filters' => $filters,
            'generated_at' => now()->format('d/m/Y H:i:s'),
        ]);
    }
}
