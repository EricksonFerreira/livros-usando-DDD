<?php

namespace Database\Seeders;

use App\Domain\Models\Autor;
use App\Domain\Models\Assunto;
use App\Domain\Models\Livro;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Limpar as tabelas
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        DB::table('autor_livro')->truncate();
        DB::table('assunto_livro')->truncate();
        Livro::truncate();
        Autor::truncate();
        Assunto::truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Criar 10 autores
        $autores = Autor::factory(10)->create();
        
        // Criar 5 assuntos
        $assuntos = [
            ['descricao' => 'Ficção Científica'],
            ['descricao' => 'Fantasia'],
            ['descricao' => 'Tecnologia'],
            ['descricao' => 'Negócios'],
            ['descricao' => 'Autoajuda']
        ];
        
        foreach ($assuntos as $assunto) {
            Assunto::create($assunto);
        }
        
        // Criar 20 livros com relacionamentos
        Livro::factory(20)->create()->each(function ($livro) use ($autores) {
            // Relacionar com 1 a 3 autores aleatórios
            $livro->autores()->attach(
                $autores->random(rand(1, 3))->pluck('id')->toArray()
            );
            
            // Relacionar com 1 a 2 assuntos aleatórios
            $livro->assuntos()->attach(
                Assunto::all()->random(rand(1, 2))->pluck('id')->toArray()
            );
        });
        
        $this->command->info('Dados iniciais criados com sucesso!');
    }
}
