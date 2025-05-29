<style>
    .sidebar {
        height: 100vh;
        overflow-y: auto;
    }
    .sidebar-content {
        display: flex;
        flex-direction: column;
        min-height: 100%;
    }
    .sidebar-nav {
        flex: 1;
        overflow-y: auto;
    }
    .sidebar-help {
        padding: 1rem;
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }
</style>

<div class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="d-flex flex-column h-100">
        <div class="text-center mb-4 pt-3">
            <h4 class="text-primary">Sistema de Livros</h4>
            <hr class="mt-2 mb-0">
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('livros.*') ? 'active' : '' }}" 
                   href="{{ route('livros.index') }}">
                    <i class="fas fa-book me-2"></i>
                    Livros
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('autores.index') }}">
                    <i class="fas fa-user-edit me-2"></i>
                    Autores
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('assuntos.index') }}">
                    <i class="fas fa-tags me-2"></i>
                    Assuntos
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Relatórios</span>
            <i class="fas fa-chart-line"></i>
        </h6>
        <div class="sidebar-nav">
            <ul class="nav flex-column mb-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('relatorios.livros-por-autor') ? 'active' : '' }}" 
                       href="{{ route('relatorios.livros-por-autor') }}">
                        <i class="fas fa-book-reader me-2"></i>
                        Livros por Autor
                        <span class="badge bg-primary rounded-pill float-end">Novo</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="sidebar-help">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-primary"></i>
                </div>
                <div class="ms-2 small">
                    <div class="fw-bold">Ajuda?</div>
                    <div class="text-muted">Entre em contato com o suporte</div>
                </div>
            </div>
        </div>
    </div>
</div>
