<?php

namespace App\Infrastructure\Adapters\Reports;

use App\Domain\Contracts\Reports\ReportAdapterInterface;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Facades\Excel;

class ExcelReportAdapter implements ReportAdapterInterface, FromArray, WithHeadings, WithTitle
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
     * Dados do relatório
     *
     * @var array
     */
    protected array $data = [];
    
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
     * Retorna o título da planilha
     * 
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }
    
    /**
     * Retorna os cabeçalhos da planilha
     * 
     * @return array
     */
    public function headings(): array
    {
        return array_values($this->columns);
    }
    
    /**
     * Retorna os dados da planilha
     * 
     * @return array
     */
    public function array(): array
    {
        return $this->data;
    }
    
    /**
     * Gera o relatório em Excel
     * 
     * @param array $data
     * @param array $options
     * @return mixed
     */
    public function generate(array $data, array $options = [])
    {
        $this->data = $data;
        
        // Se houver dados agrupados, achata a estrutura
        if (isset($options['data']['groupedData'])) {
            $this->data = [];
            foreach ($options['data']['groupedData'] as $autor => $livros) {
                foreach ($livros as $livro) {
                    $this->data[] = (array) $livro;
                }
            }
        }
        
        $filename = $options['filename'] ?? 'relatorio-' . now()->format('Y-m-d-H-i-s');
        
        // Adiciona a data de geração como metadado
        $export = $this;
        
        return Excel::download($export, $filename . '.xlsx');
    }
}
