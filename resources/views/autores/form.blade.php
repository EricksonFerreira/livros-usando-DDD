@extends('layouts.app')

@section('title', $autor->id ? 'Editar Autor' : 'Novo Autor')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ $autor->id ? 'Editar Autor' : 'Novo Autor' }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ $autor->id ? route('autores.update', $autor->id) : route('autores.store') }}" method="POST">
            @csrf
            @if($autor->id)
                @method('PUT')
            @endif
            
            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="nome" class="form-label">Nome *</label>
                    <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                           id="nome" name="nome" 
                           value="{{ old('nome', $autor->nome) }}" required>
                    @error('nome')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('autores.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ $autor->id ? 'Atualizar' : 'Salvar' }}
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
        // Inicializa select2 para seleção múltipla
        $('#autores').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Selecione...',
            allowClear: true,
            closeOnSelect: false
        });
        
    });
</script>
@endpush
@endsection
