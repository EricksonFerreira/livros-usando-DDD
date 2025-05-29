<?php

namespace App\Infrastructure\Adapters\Reports;

use App\Domain\Contracts\Reports\ReportAdapterInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class PdfReportAdapter implements ReportAdapterInterface
{
    /**
     * Título do relatório
     *
     * @var string
     */
    protected string $title = 'Relatório';
    
    /**
     * Colunas do relatório
     *
     * @var array
     */
    protected array $columns = [];
    
    /**
     * Define o título do relatório
     * 
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }
    
    /**
     * Define as colunas do relatório
     * 
     * @param array $columns
     * @return self
     */
    public function setColumns(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }
    
    /**
     * Gera o relatório em PDF
     * 
     * @param array $data
     * @param array $options
     * @return \Illuminate\Http\Response
     */
    public function generate(array $data, array $options = [])
    {
        // Se os dados já estiverem agrupados, use-os diretamente
        $groupedData = $options['data']['groupedData'] ?? collect($data)->groupBy('autor');
        
        // Obter a view do relatório
        $view = $options['view'] ?? 'reports.pdf.livros-por-autor';
        
        // Dados para a view
        $viewData = [
            'title' => $options['data']['title'] ?? $this->title,
            'columns' => $options['data']['columns'] ?? $this->columns,
            'groupedData' => $groupedData,
        ];
        
        // Carregar a view e gerar o PDF
        $pdf = Pdf::loadView($view, $viewData);
        
        // Nome do arquivo
        $filename = $options['filename'] ?? 'relatorio-' . now()->format('Y-m-d-H-i-s') . '.pdf';
        if(strpos($filename, '.pdf') === false) {
            $filename .= '.pdf';
        }
        
        // Se for para visualização no navegador
        if (isset($options['stream']) && $options['stream']) {
            return $pdf->stream($filename);
        }
        
        // Por padrão, retorna para download
        return $pdf->download($filename);
    }
}
