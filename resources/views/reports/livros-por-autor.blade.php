@extends('layouts.app')

@section('title', $config['titulo'] ?? 'Relatório')

@push('styles')
<style>
    .filter-card {
        margin-bottom: 1.5rem;
    }
    .export-buttons .btn {
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .table-responsive {
        margin-top: 1rem;
    }
    .autor-header {
        background-color: #f8f9fa;
        padding: 0.5rem 1rem;
        margin: 1rem 0 0.5rem;
        border-left: 4px solid #0d6efd;
    }
    .no-results {
        padding: 2rem;
        text-align: center;
        color: #6c757d;
    }
    .filter-tag {
        display: inline-block;
        background-color: #e9ecef;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    .filter-tag .close {
        margin-left: 0.5rem;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">{{ $config['titulo'] ?? 'Relatório' }}</h1>
        @if(isset($config['descricao']))
            <p class="text-muted mb-0">{{ $config['descricao'] }}</p>
        @endif
    </div>
    @if(!empty($config['export_formats']))
    <div class="export-buttons">
        @foreach($config['export_formats'] as $format => $label)
            <a href="{{ route('relatorios.exportar', $format) }}?{{ http_build_query(request()->query()) }}" 
               class="btn btn-{{ $format === 'pdf' ? 'danger' : 'success' }}"
               data-bs-toggle="tooltip" 
               title="Exportar para {{ strtoupper($format) }}"
               target="_blank">
                <i class="fas fa-file-{{ $format === 'pdf' ? 'pdf' : 'excel' }} me-1"></i> {{ $label }}
            </a>
        @endforeach
    </div>
    @endif
</div>

<div class="card filter-card">
    <div class="card-body">
        <form method="GET" action="{{ route('relatorios.livros-por-autor') }}" class="row g-3">
            @foreach($config['filtros'] as $campo => $filtro)
                <div class="col-md-{{ 12 / count($config['filtros']) }}">
                    <label for="{{ $campo }}" class="form-label">{{ $filtro['label'] ?? ucfirst($campo) }}</label>
                    <input type="{{ $filtro['type'] ?? 'text' }}" 
                           class="form-control" 
                           id="{{ $campo }}" 
                           name="{{ $campo }}" 
                           value="{{ request($campo) }}" 
                           placeholder="{{ $filtro['placeholder'] ?? '' }}">
                </div>
            @endforeach
            <div class="col-12 d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-1"></i> Filtrar
                </button>
                @if(request()->hasAny(array_keys($config['filtros'])))
                    <a href="{{ route('relatorios.livros-por-autor') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Limpar Filtros
                    </a>
                @endif
            </div>
        </form>
        
        @if(!empty($filtrosAplicados))
        <div class="mt-3">
            <small class="text-muted d-block mb-1">Filtros aplicados:</small>
            @foreach($filtrosAplicados as $campo => $valor)
                <a href="?{{ http_build_query(array_merge(request()->except($campo), ['page' => 1])) }}" class="filter-tag">
                    {{ $config['filtros'][$campo]['label'] ?? $campo }}: {{ $valor }}
                    <span class="close">&times;</span>
                </a>
            @endforeach
        </div>
        @endif
    </div>
</div>

@if($livrosPorAutor->count() > 0)
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            @foreach($config['colunas'] as $campo => $rotulo)
                                @if($campo === 'autor')
                                    <th>{{ $rotulo }}</th>
                                @else
                                    <th class="{{ in_array($campo, ['edicao', 'ano_publicacao', 'valor']) ? 'text-center' : '' }}">
                                        {{ $rotulo }}
                                    </th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $autorAtual = null;
                        @endphp
                        
                        @foreach($livrosPorAutor as $livro)
                            @if($autorAtual !== $livro->autor)
                                @php $autorAtual = $livro->autor; @endphp
                                <tr class="table-active">
                                    <td colspan="{{ count($config['colunas']) }}" class="fw-bold">
                                        <i class="fas fa-user-edit me-2"></i>{{ $livro->autor }}
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                @foreach($config['colunas'] as $campo => $rotulo)
                                    @if($campo === 'valor')
                                        <td class="text-end">R$ {{ number_format($livro->$campo, 2, ',', '.') }}</td>
                                    @elseif($campo === 'autor')
                                        <td></td>
                                    @elseif(in_array($campo, ['edicao', 'ano_publicacao']))
                                        <td class="text-center">{{ $livro->$campo ?: '-' }}</td>
                                    @else
                                        <td>{{ $livro->$campo ?? '-' }}</td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Mostrando {{ $livrosPorAutor->firstItem() }} a {{ $livrosPorAutor->lastItem() }} de {{ $livrosPorAutor->total() }} registros
                </div>
                {{ $livrosPorAutor->withQueryString()->links() }}
            </div>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <div class="mb-3">
                <i class="fas fa-book-open fa-3x text-muted"></i>
            </div>
            <h5 class="text-muted">Nenhum registro encontrado</h5>
            <p class="text-muted mb-0">
                @if(request()->hasAny(array_keys($config['filtros'])))
                    Nenhum resultado para os filtros aplicados. Tente ajustar os critérios de busca.
                @else
                    Nenhum dado disponível para exibição.
                @endif
            </p>
        </div>
    </div>
@endif

@push('scripts')
<script>
    $(document).ready(function() {
        // Inicializa tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Adiciona confirmação antes de limpar filtros
        $('a[href*="limpar-filtros"]').on('click', function(e) {
            if (!confirm('Deseja realmente limpar todos os filtros?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush
@endsection
