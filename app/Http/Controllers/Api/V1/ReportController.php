<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Infrastructure\Adapters\Reports\PdfReportAdapter;
use App\Domain\Services\Reports\LivroReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Gera o relatório de livros por autor em PDF
     * 
     * @param Request $request
     * @param LivroReportService $reportService
     * @return \Illuminate\Http\Response
     */
    public function livrosPorAutorPdf(Request $request, LivroReportService $reportService)
    {
        // Cria o adaptador PDF
        $adapter = new PdfReportAdapter();
        
        // Configura o serviço de relatório com o adaptador
        $reportService = new LivroReportService($adapter);
        
        // Obtém os filtros da requisição
        $filters = $request->only(['ano_publicacao', 'assunto']);
        
        // Gera o PDF
        $pdf = $reportService->generateLivrosPorAutorReport($filters);
        
        // Define o nome do arquivo
        $filename = 'relatorio_livros_por_autor_' . now()->format('Y-m-d_His') . '.pdf';
        
        // Retorna o PDF para download
        return $pdf->download($filename);
    }
}
