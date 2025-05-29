<?php

namespace App\Http\Controllers;

use App\Domain\Models\LivroPorAutor;
use App\Infrastructure\Adapters\Reports\PdfReportAdapter;
use App\Infrastructure\Adapters\Reports\ExcelReportAdapter;
use App\Domain\Contracts\Reports\ReportAdapterInterface;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    protected $configRelatorios;
    protected $reportAdapters = [];

    public function __construct(
        PdfReportAdapter $pdfReportAdapter,
        ExcelReportAdapter $excelReportAdapter
    ) {
        $this->configRelatorios = config('relatorios');
        
        // Inicializar os adaptadores de relatório
        $this->reportAdapters = [
            'pdf' => $pdfReportAdapter,
            'excel' => $excelReportAdapter,
        ];
    }

    /**
     * Exibe o relatório de livros por autor
     */
    public function livrosPorAutor(Request $request)
    {
        $config = $this->configRelatorios['livros_por_autor'];
        
        $query = LivroPorAutor::query();
        
        // Aplicar filtros
        $filtrosAplicados = [];
        foreach ($config['filtros'] as $campo => $filtro) {
            if ($request->filled($campo)) {
                $valor = $request->input($campo);
                $query->where($campo, 'like', '%' . $valor . '%');
                $filtrosAplicados[$campo] = $valor;
            }
        }
        
        $livrosPorAutor = $query->paginate(15)
                               ->withQueryString();
        
        // Verificar se é uma requisição de exportação
        if ($request->has('export') && array_key_exists($request->export, $config['export_formats'])) {
            return $this->exportarRelatorio($request, $livrosPorAutor->get(), $config, $request->export);
        }
        
        return view('reports.livros-por-autor', [
            'livrosPorAutor' => $livrosPorAutor,
            'config' => $config,
            'filtrosAplicados' => $filtrosAplicados
        ]);
    }
    
    /**
     * Exporta o relatório para o formato especificado
     */
    /**
     * Exporta o relatório para o formato especificado
     */
    /**
     * Exporta o relatório para o formato especificado
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Collection  $dados
     * @param  array  $config
     * @param  string  $formato
     * @return \Illuminate\Http\Response
     */
    protected function exportarRelatorio($request, $dados, $config, $formato)
    {
        // Verificar se o formato é suportado
        if (!isset($this->reportAdapters[$formato])) {
            return redirect()->back()->with('error', 'Formato de exportação não suportado.');
        }

        // Formatar os dados para exportação
        $dadosFormatados = $dados->map(function($item) use ($config) {
            $linha = [];
            foreach (array_keys($config['colunas']) as $campo) {
                if ($campo === 'valor') {
                    $linha[$campo] = $item->$campo; // Manter o valor numérico para formatação no adaptador
                } else {
                    $linha[$campo] = $item->$campo ?? '-';
                }
            }
            return $linha;
        })->toArray();
        
        // Configurar o adaptador
        $adapter = $this->reportAdapters[$formato];
        $adapter->setTitle($config['titulo'])
                ->setColumns($config['colunas']);
        
        // Gerar e retornar o relatório
        return $adapter->generate($dadosFormatados, [
            'filename' => 'relatorio-livros-por-autor-' . now()->format('Y-m-d-H-i-s'),
            'view' => 'reports.pdf.livros-por-autor',
            'data' => [
                'title' => $config['titulo'],
                'columns' => $config['colunas'],
                'groupedData' => $dados->groupBy('autor'),
            ]
        ]);
    }
    
    /**
     * Exporta um relatório para o formato especificado
     *
     * @param  string  $tipo
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * Exporta um relatório para o formato especificado
     *
     * @param  string  $tipo
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportar($tipo, Request $request)
    {
        $config = $this->configRelatorios['livros_por_autor'];
        
        // Verificar se o formato de exportação é suportado
        if (!array_key_exists($tipo, $config['export_formats'])) {
            return redirect()->back()->with('error', 'Formato de exportação não suportado.');
        }
        
        $query = LivroPorAutor::query();
        
        // Aplicar filtros
        foreach ($config['filtros'] as $campo => $filtro) {
            if ($request->filled($campo)) {
                $query->where($campo, 'like', '%' . $request->input($campo) . '%');
            }
        }
        
        // Obter os dados ordenados
        $dados = $query->orderBy('autor')
                      ->get();
        
        // Exportar o relatório
        return $this->exportarRelatorio($request, $dados, $config, $tipo);
    }
}
