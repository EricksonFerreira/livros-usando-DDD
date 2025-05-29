# Sistema de Gerenciamento de Livros

<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

<p align="center">
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
    </a>
</p>

## 📋 Sobre o Projeto

Sistema de gerenciamento de livros desenvolvido com Laravel, seguindo boas práticas de desenvolvimento e arquitetura limpa (Clean Architecture).

### ✨ Funcionalidades

- 📚 CRUD de Livros
- ✍️ Gerenciamento de Autores
- 🏷️ Categorização por Assuntos
- 📊 Relatórios em PDF e Excel
- 🔍 Filtros avançados
- 📱 Design responsivo

## 🚀 Tecnologias

- **Backend:** PHP 8.1+, Laravel 10.x
- **Frontend:** Bootstrap 5, jQuery
- **Banco de Dados:** MySQL 8.0+
- **Ferramentas:** Composer, NPM

## 🛠️ Instalação

1. **Clonar o repositório**
   ```bash
   git clone [URL_DO_REPOSITORIO]
   cd livros
   ```

2. **Instalar dependências**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Configurar ambiente**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configurar banco de dados**
   - Criar banco de dados
   - Configurar arquivo `.env` com as credenciais do banco

5. **Executar migrações e seeders**
   ```bash
   php artisan migrate --seed
   ```

6. **Iniciar servidor**
   ```bash
   php artisan serve
   ```

7. **Acessar o sistema**
   - URL: http://localhost:8000
   - Usuário: admin@example.com
   - Senha: password

## 🏗️ Estrutura do Projeto

```
app/
├── Domain/           # Lógica de domínio
│   ├── Models/       # Modelos de domínio
│   ├── Repositories/ # Interfaces de repositório
│   └── UseCases/     # Casos de uso
├── Infrastructure/   # Implementações concretas
│   ├── Adapters/     # Adaptadores
│   └── Repositories/ # Implementações de repositório
└── Http/             # Camada HTTP
    ├── Controllers/  # Controladores
    └── Requests/     # Validações
```

## 📝 Licença

Este projeto está licenciado sob a [licença MIT](https://opensource.org/licenses/MIT).

---

## 📚 Sobre o Laravel

Laravel é um framework de aplicação web com uma sintaxe expressiva e elegante. Acreditamos que o desenvolvimento deve ser uma experiência agradável e criativa para ser verdadeiramente satisfatório. O Laravel tira a dor do desenvolvimento, facilitando tarefas comuns usadas na maioria dos projetos da web.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
