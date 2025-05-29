@extends('layouts.app')

@section('title', $assunto->id ? 'Editar Assunto' : 'Novo Assunto')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ $assunto->id ? 'Editar Assunto' : 'Novo Assunto' }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ $assunto->id ? route('assuntos.update', $assunto->id) : route('assuntos.store') }}" method="POST">
            @csrf
            @if($assunto->id)
                @method('PUT')
            @endif
            
            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="descricao" class="form-label">Descrição *</label>
                    <input type="text" class="form-control @error('descricao') is-invalid @enderror" 
                           id="descricao" name="descricao" 
                           value="{{ old('descricao', $assunto->descricao) }}" required>
                    @error('descricao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('assuntos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ $assunto->id ? 'Atualizar' : 'Salvar' }}
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
        $('#assuntos').select2({
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
