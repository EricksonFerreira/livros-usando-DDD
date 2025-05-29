@extends('layouts.app')

@section('title', 'Lista de Livros')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Lista de Livros</h1>
    <a href="{{ route('livros.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Novo Livro
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($livros->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Título</th>
                            <th>Editora</th>
                            <th>Ano</th>
                            <th>Valor</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($livros as $livro)
                            <tr>
                                <td>{{ $livro->titulo }}</td>
                                <td>{{ $livro->editora }}</td>
                                <td>{{ $livro->ano_publicacao }}</td>
                                <td>R$ {{ number_format($livro->valor, 2, ',', '.') }}</td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('livros.edit', $livro->id) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           data-bs-toggle="tooltip" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('livros.destroy', $livro->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Tem certeza que deseja remover este livro?');">
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
                {{ $livros->links() }}
            </div>
        @else
            <div class="alert alert-info mb-0">
                Nenhum livro cadastrado. <a href="{{ route('livros.create') }}">Clique aqui</a> para adicionar um novo livro.
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
