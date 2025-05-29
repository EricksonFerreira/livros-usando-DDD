<?php

return [
    'livros_por_autor' => [
        'titulo' => 'Relatório de Livros por Autor',
        'descricao' => 'Lista todos os livros agrupados por autor',
        'colunas' => [
            'autor' => 'Autor',
            'titulo' => 'Título do Livro',
            'editora' => 'Editora',
            'edicao' => 'Edição',
            'ano_publicacao' => 'Ano de Publicação',
            'valor' => 'Valor (R$)'
        ],
        'filtros' => [
            'autor' => [
                'label' => 'Autor',
                'type' => 'text',
                'placeholder' => 'Digite o nome do autor',
            ],
            'titulo' => [
                'label' => 'Título',
                'type' => 'text',
                'placeholder' => 'Digite o título do livro',
            ],
        ],
        'export_formats' => [
            'pdf' => 'PDF',
            'excel' => 'Excel',
        ],
    ],
];
