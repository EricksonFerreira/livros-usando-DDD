<?php

namespace App\Providers;

use App\Domain\Contracts\Repositories\AssuntoRepositoryInterface;
use App\Domain\Contracts\Repositories\AutorRepositoryInterface;
use App\Domain\UseCases\Assunto\AtualizarAssuntoUseCase;
use App\Domain\UseCases\Assunto\CriarAssuntoUseCase;
use App\Domain\UseCases\Assunto\ExcluirAssuntoUseCase;
use App\Domain\UseCases\Assunto\ListarAssuntosUseCase;
use App\Domain\UseCases\Assunto\VisualizarAssuntoUseCase;
use App\Domain\UseCases\Autor\AtualizarAutorUseCase;
use App\Domain\UseCases\Livro\AtualizarLivroUseCase;
use App\Domain\UseCases\Livro\CriarLivroUseCase;
use App\Domain\UseCases\Livro\ExcluirLivroUseCase;
use App\Domain\UseCases\Livro\ListarLivrosUseCase;
use App\Domain\UseCases\Livro\VisualizarLivroUseCase;
use App\Domain\Contracts\Repositories\LivroRepositoryInterface;
use App\Domain\UseCases\Autor\CriarAutorUseCase;
use App\Domain\UseCases\Autor\ExcluirAutorUseCase;
use App\Domain\UseCases\Autor\ListarAutoresUseCase;
use App\Domain\UseCases\Autor\VisualizarAutorUseCase;
use App\Domain\Contracts\Reports\ReportAdapterInterface;
use App\Domain\Services\Reports\LivroReportService;
use App\Infrastructure\Adapters\Reports\PdfReportAdapter;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registrar as implementações dos repositórios
        $this->app->bind(
            \App\Domain\Contracts\Repositories\LivroRepositoryInterface::class,
            \App\Infrastructure\Repositories\Eloquent\LivroRepository::class
        );

        // Registrar os casos de uso
        $this->registerAutorUseCases();
        $this->registerAssuntoUseCases();
        $this->registerLivroUseCases();
        
        // Registrar o serviço de relatório
        $this->registerReportServices();
    }

    /**
     * Registrar os casos de uso de Autor
     */
    private function registerAutorUseCases(): void
    {
        $this->app->when(CriarAutorUseCase::class)
            ->needs(AutorRepositoryInterface::class)
            ->give(function ($app) {
                return $app->make(AutorRepositoryInterface::class);
            });

        $this->app->when(ListarAutoresUseCase::class)
            ->needs(AutorRepositoryInterface::class)
            ->give(function ($app) {
                return $app->make(AutorRepositoryInterface::class);
            });

        $this->app->when(VisualizarAutorUseCase::class)
            ->needs(AutorRepositoryInterface::class)
            ->give(function ($app) {
                return $app->make(AutorRepositoryInterface::class);
            });

        $this->app->when(AtualizarAutorUseCase::class)
            ->needs(AutorRepositoryInterface::class)
            ->give(function ($app) {
                return $app->make(AutorRepositoryInterface::class);
            });

        $this->app->when(ExcluirAutorUseCase::class)
            ->needs(AutorRepositoryInterface::class)
            ->give(function ($app) {
                return $app->make(AutorRepositoryInterface::class);
            });
    }

    /**
     * Registrar os casos de uso de Assunto
     */
    private function registerAssuntoUseCases(): void
    {
        $this->app->when(CriarAssuntoUseCase::class)
            ->needs(AssuntoRepositoryInterface::class)
            ->give(function ($app) {
                return $app->make(AssuntoRepositoryInterface::class);
            });

        $this->app->when(ListarAssuntosUseCase::class)
            ->needs(AssuntoRepositoryInterface::class)
            ->give(function ($app) {
                return $app->make(AssuntoRepositoryInterface::class);
            });

        $this->app->when(VisualizarAssuntoUseCase::class)
            ->needs(AssuntoRepositoryInterface::class)
            ->give(function ($app) {
                return $app->make(AssuntoRepositoryInterface::class);
            });

        $this->app->when(AtualizarAssuntoUseCase::class)
            ->needs(AssuntoRepositoryInterface::class)
            ->give(function ($app) {
                return $app->make(AssuntoRepositoryInterface::class);
            });

        $this->app->when(ExcluirAssuntoUseCase::class)
            ->needs(AssuntoRepositoryInterface::class)
            ->give(function ($app) {
                return $app->make(AssuntoRepositoryInterface::class);
            });
    }

    /**
     * Registrar os casos de uso de Livro
     */
    private function registerLivroUseCases(): void
    {
        $this->app->when(CriarLivroUseCase::class)
            ->needs(LivroRepositoryInterface::class)
            ->give(function ($app) {
                return $app->make(LivroRepositoryInterface::class);
            });

        $this->app->when(ListarLivrosUseCase::class)
            ->needs(LivroRepositoryInterface::class)
            ->give(function ($app) {
                return $app->make(LivroRepositoryInterface::class);
            });

        $this->app->when(VisualizarLivroUseCase::class)
            ->needs(LivroRepositoryInterface::class)
            ->give(function ($app) {
                return $app->make(LivroRepositoryInterface::class);
            });

        $this->app->when(AtualizarLivroUseCase::class)
            ->needs(LivroRepositoryInterface::class)
            ->give(function ($app) {
                return $app->make(LivroRepositoryInterface::class);
            });

        $this->app->when(ExcluirLivroUseCase::class)
            ->needs(LivroRepositoryInterface::class)
            ->give(function ($app) {
                return $app->make(LivroRepositoryInterface::class);
            });
    }
    
    /**
     * Registrar os serviços de relatório
     */
    private function registerReportServices(): void
    {
        // Registrar o adaptador de PDF
        $this->app->singleton(PdfReportAdapter::class, function ($app) {
            return new PdfReportAdapter();
        });
        
        // Registrar o adaptador de Excel
        $this->app->singleton(ExcelReportAdapter::class, function ($app) {
            return new ExcelReportAdapter();
        });
        
        // Configurar o serviço de relatório para usar o adaptador de PDF por padrão
        $this->app->when(LivroReportService::class)
            ->needs(ReportAdapterInterface::class)
            ->give(function ($app) {
                return $app->make(PdfReportAdapter::class);
            });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar o arquivo de configuração de relatórios
        $this->mergeConfigFrom(
            __DIR__.'/../../config/relatorios.php', 'relatorios'
        );
    }
}
