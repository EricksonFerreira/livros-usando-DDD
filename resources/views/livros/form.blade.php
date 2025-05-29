@extends('layouts.app')

@section('title', $livro->id ? 'Editar Livro' : 'Novo Livro')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ $livro->id ? 'Editar Livro' : 'Novo Livro' }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ $livro->id ? route('livros.update', $livro->id) : route('livros.store') }}" method="POST">
            @csrf
            @if($livro->id)
                @method('PUT')
            @endif
            
            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="titulo" class="form-label">Título *</label>
                    <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                           id="titulo" name="titulo" 
                           value="{{ old('titulo', $livro->titulo) }}" required>
                    @error('titulo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4">
                    <label for="editora" class="form-label">Editora *</label>
                    <input type="text" class="form-control @error('editora') is-invalid @enderror" 
                           id="editora" name="editora" 
                           value="{{ old('editora', $livro->editora) }}" required>
                    @error('editora')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="edicao" class="form-label">Edição</label>
                    <input type="number" min="1" class="form-control @error('edicao') is-invalid @enderror" 
                           id="edicao" name="edicao" 
                           value="{{ old('edicao', $livro->edicao) }}">
                    @error('edicao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3">
                    <label for="ano_publicacao" class="form-label">Ano de Publicação *</label>
                    <input type="number" min="1800" max="{{ date('Y') + 1 }}" 
                           class="form-control @error('ano_publicacao') is-invalid @enderror" 
                           id="ano_publicacao" name="ano_publicacao" 
                           value="{{ old('ano_publicacao', $livro->ano_publicacao) }}" required>
                    @error('ano_publicacao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3">
                    <label for="valor" class="form-label">Valor (R$) *</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="text" class="form-control money @error('valor') is-invalid @enderror" 
                               id="valor" name="valor" 
                               value="{{ old('valor', $livro->id ? number_format($livro->valor, 2, ',', '.') : '') }}" 
                               required>
                        @error('valor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="autores" class="form-label">Autores *</label>
                    <select class="form-select @error('autores') is-invalid @enderror" 
                            id="autores" name="autores[]" multiple required>
                        @foreach($autores as $autor)
                            <option value="{{ $autor->id }}" 
                                {{ in_array($autor->id, old('autores', $livro->autores->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $autor->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('autores')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Segure Ctrl (Windows/Linux) ou Cmd (Mac) para selecionar múltiplos autores.</small>
                </div>
                
                <div class="col-md-6">
                    <label for="assuntos" class="form-label">Assuntos *</label>
                    <select class="form-select @error('assuntos') is-invalid @enderror" 
                            id="assuntos" name="assuntos[]" multiple required>
                        @foreach($assuntos as $assunto)
                            <option value="{{ $assunto->id }}" 
                                {{ in_array($assunto->id, old('assuntos', $livro->assuntos->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $assunto->descricao }}
                            </option>
                        @endforeach
                    </select>
                    @error('assuntos')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Segure Ctrl (Windows/Linux) ou Cmd (Mac) para selecionar múltiplos assuntos.</small>
                </div>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('livros.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ $livro->id ? 'Atualizar' : 'Salvar' }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Inicializa máscara de moeda
        $('.money').mask('#.##0,00', {reverse: true});
        
        // Inicializa select2 para seleção múltipla
        $('#autores, #assuntos').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Selecione...',
            allowClear: true,
            closeOnSelect: false
        });
        
        // Ajusta o tamanho do dropdown para melhor visualização
        $('.select2-container--bootstrap-5 .select2-selection').css('min-height', '38px');
        
        // Validação do formulário
        $('form').on('submit', function() {
            // Remove pontos de milhar e substitui vírgula por ponto no valor
            var valor = $('#valor').val().replace(/\./g, '').replace(',', '.');
            $('#valor').val(valor);
            
            return true;
        });
    });
</script>
@endpush
@endsection
