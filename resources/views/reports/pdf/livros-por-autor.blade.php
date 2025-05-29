<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
            padding: 0;
        }
        .header p {
            font-size: 12px;
            margin: 5px 0 0 0;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        td {
            border: 1px solid #ddd;
            padding: 6px;
        }
        .autor-group {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .autor-header {
            background-color: #e9ecef;
            padding: 5px 10px;
            font-weight: bold;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #777;
            margin-top: 20px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Gerado em: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    @foreach($groupedData as $autor => $livros)
        <div class="autor-group">
            <div class="autor-header">
                {{ $autor }}
            </div>
            
            <table>
                <thead>
                    <tr>
                        @foreach($columns as $column => $label)
                            <th>{{ $label }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($livros as $livro)
                        <tr>
                            @foreach($columns as $column => $label)
                                <td class="{{ $column === 'valor' ? 'text-right' : '' }}">
                                    @if($column === 'valor')
                                        R$ {{ number_format($livro->$column, 2, ',', '.') }}
                                    @else
                                        {{ $livro->$column ?? '-' }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="footer">
        <p>Sistema de Gerenciamento de Livros - {{ config('app.name') }}</p>
    </div>
</body>
</html>
