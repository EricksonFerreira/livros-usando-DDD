<?php

$dtoFiles = [
    // Autor
    'app/Domain/UseCases/Autor/DTOs/ExcluirAutorInputDTO.php',
    'app/Domain/UseCases/Autor/DTOs/ListarAutoresInputDTO.php',
    'app/Domain/UseCases/Autor/DTOs/VisualizarAutorInputDTO.php',
    // Assunto
    'app/Domain/UseCases/Assunto/DTOs/AtualizarAssuntoInputDTO.php',
    'app/Domain/UseCases/Assunto/DTOs/CriarAssuntoInputDTO.php',
    'app/Domain/UseCases/Assunto/DTOs/ExcluirAssuntoInputDTO.php',
    'app/Domain/UseCases/Assunto/DTOs/ListarAssuntosInputDTO.php',
    'app/Domain/UseCases/Assunto/DTOs/VisualizarAssuntoInputDTO.php',
    // Livro
    'app/Domain/UseCases/Livro/DTOs/AtualizarLivroInputDTO.php',
    'app/Domain/UseCases/Livro/DTOs/CriarLivroInputDTO.php',
    'app/Domain/UseCases/Livro/DTOs/ExcluirLivroInputDTO.php',
    'app/Domain/UseCases/Livro/DTOs/ListarLivrosInputDTO.php',
    'app/Domain/UseCases/Livro/DTOs/VisualizarLivroInputDTO.php',
];

$basePath = __DIR__ . '/';

foreach ($dtoFiles as $file) {
    $fullPath = $basePath . $file;
    
    if (!file_exists($fullPath)) {
        echo "Arquivo não encontrado: $fullPath\n";
        continue;
    }
    
    $content = file_get_contents($fullPath);
    
    // Verifica se já implementa a interface
    if (strpos($content, 'implements InputDTOInterface') !== false) {
        echo "Pulando $file - já implementa InputDTOInterface\n";
        continue;
    }
    
    // Adiciona o use se não existir
    if (strpos($content, 'use App\\Domain\\UseCases\\DTOs\\InputDTOInterface;') === false) {
        $content = preg_replace(
            '/namespace ([^;]+);/', 
            'namespace $1;' . "\n\nuse App\\Domain\\UseCases\\DTOs\\InputDTOInterface;", 
            $content,
            1
        );
    }
    
    // Adiciona o implements
    $content = preg_replace(
        '/class (\w+)($|\s*\{)/', 
        'class $1 implements InputDTOInterface$2', 
        $content,
        1
    );
    
    file_put_contents($fullPath, $content);
    echo "Atualizado: $file\n";
}

echo "Atualização concluída!\n";
