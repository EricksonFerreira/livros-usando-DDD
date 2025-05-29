<?php

$useCaseFiles = [
    // Autor
    'app/Domain/UseCases/Autor/CriarAutorUseCase.php',
    'app/Domain/UseCases/Autor/AtualizarAutorUseCase.php',
    'app/Domain/UseCases/Autor/ExcluirAutorUseCase.php',
    'app/Domain/UseCases/Autor/ListarAutoresUseCase.php',
    'app/Domain/UseCases/Autor/VisualizarAutorUseCase.php',
    // Assunto
    'app/Domain/UseCases/Assunto/CriarAssuntoUseCase.php',
    'app/Domain/UseCases/Assunto/AtualizarAssuntoUseCase.php',
    'app/Domain/UseCases/Assunto/ExcluirAssuntoUseCase.php',
    'app/Domain/UseCases/Assunto/ListarAssuntosUseCase.php',
    'app/Domain/UseCases/Assunto/VisualizarAssuntoUseCase.php',
    // Livro
    'app/Domain/UseCases/Livro/CriarLivroUseCase.php',
    'app/Domain/UseCases/Livro/AtualizarLivroUseCase.php',
    'app/Domain/UseCases/Livro/ExcluirLivroUseCase.php',
    'app/Domain/UseCases/Livro/ListarLivrosUseCase.php',
    'app/Domain/UseCases/Livro/VisualizarLivroUseCase.php',
];

$basePath = __DIR__ . '/';

foreach ($useCaseFiles as $file) {
    $fullPath = $basePath . $file;
    
    if (!file_exists($fullPath)) {
        echo "Arquivo não encontrado: $fullPath\n";
        continue;
    }
    
    $content = file_get_contents($fullPath);
    
    // Pular se já estiver com a assinatura correta
    if (preg_match('/public function handle\(\?.*InputDTOInterface \$.* = null\): \w+OutputDTO/', $content)) {
        echo "Pulando $file - já está com a assinatura correta\n";
        continue;
    }
    
    // Atualizar a documentação do template
    $content = preg_replace(
        '/@implements UseCaseInterface<([^,>]+),\s*([^>]+)>/',
        '@implements UseCaseInterface<$2>',
        $content
    );
    
    // Atualizar a assinatura do método handle
    $content = preg_replace_callback(
        '/public function handle\(([^)]*)\)(\s*:\s*([^{;\n]+))?/m',
        function($matches) {
            $params = $matches[1];
            $returnType = $matches[3] ?? '';
            
            // Se não tiver tipo de retorno, tentar adivinhar a partir do nome da classe
            if (empty($returnType)) {
                if (preg_match('/class (\w+)UseCase/', $matches[0], $classMatches)) {
                    $className = $classMatches[1];
                    $returnType = $className . 'OutputDTO';
                }
            }
            
            // Se ainda não tiver tipo de retorno, usar mixed
            if (empty($returnType)) {
                $returnType = 'mixed';
            }
            
            return "public function handle(?\\App\\Domain\\UseCases\\DTOs\\InputDTOInterface \$data = null): $returnType";
        },
        $content
    );
    
    // Adicionar verificação de tipo no início do método
    $content = preg_replace_callback(
        '/(public function handle\([^{]+\{)([^}]*)/s',
        function($matches) use ($file) {
            $methodStart = $matches[1];
            $methodBody = $matches[2];
            
            // Extrair o nome da classe do DTO de entrada a partir do caminho do arquivo
            $dtoClass = '';
            if (preg_match('/([A-Za-z]+)UseCase\.php$/', $file, $fileMatches)) {
                $className = $fileMatches[1] . 'InputDTO';
                $namespace = '';
                
                if (strpos($file, '/Autor/') !== false) {
                    $namespace = 'App\\Domain\\UseCases\\Autor\\DTOs\\';
                } elseif (strpos($file, '/Assunto/') !== false) {
                    $namespace = 'App\\Domain\\UseCases\\Assunto\\DTOs\\';
                } elseif (strpos($file, '/Livro/') !== false) {
                    $namespace = 'App\\Domain\\UseCases\\Livro\\DTOs\\';
                }
                
                $dtoClass = $namespace . $className;
            }
            
            // Se não conseguiu determinar a classe DTO, pular
            if (empty($dtoClass)) {
                echo "Não foi possível determinar a classe DTO para $file\n";
                return $matches[0];
            }
            
            $typeCheck = "\n        if (\$data !== null && !\$data instanceof $dtoClass) {\n            throw new \\InvalidArgumentException('Tipo de DTO inválido');\n        }\n\n";
            
            return $methodStart . $typeCheck . $methodBody;
        },
        $content
    );
    
    file_put_contents($fullPath, $content);
    echo "Atualizado: $file\n";
}

echo "Atualização concluída!\n";
