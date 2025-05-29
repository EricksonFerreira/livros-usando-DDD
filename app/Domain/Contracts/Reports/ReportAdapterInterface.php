<?php

namespace App\Domain\Contracts\Reports;

interface ReportAdapterInterface
{
    /**
     * Gera o relatório no formato específico
     * 
     * @param array $data Dados para o relatório
     * @param array $options Opções adicionais para formatação
     * @return mixed Retorna o relatório no formato específico
     */
    public function generate(array $data, array $options = []);
    
    /**
     * Define o título do relatório
     * 
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self;
    
    /**
     * Define as colunas do relatório
     * 
     * @param array $columns
     * @return self
     */
    public function setColumns(array $columns): self;
}
