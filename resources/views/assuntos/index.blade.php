@extends('layouts.app')

@section('title', 'Lista de Assuntos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Lista de Assuntos</h1>
    <a href="{{ route('assuntos.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Novo Assunto
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($assuntos->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Descrição</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assuntos as $assunto)
                            <tr>
                                <td>{{ $assunto->descricao }}</td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('assuntos.edit', $assunto->id) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           data-bs-toggle="tooltip" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('assuntos.destroy', $assunto->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Tem certeza que deseja remover este assunto?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="tooltip" 
                                                    title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $assuntos->links() }}
            </div>
        @else
            <div class="alert alert-info mb-0">
                Nenhum assunto cadastrado. <a href="{{ route('assuntos.create') }}">Clique aqui</a> para adicionar um novo assunto.
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    $(function () {
        // Inicializa tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endsection
