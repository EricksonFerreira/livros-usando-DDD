<?php

namespace Database\Factories;

use App\Domain\Models\Livro;
use Illuminate\Database\Eloquent\Factories\Factory;

class LivroFactory extends Factory
{
    protected $model = Livro::class;

    public function definition()
    {
        return [
            'titulo' => $this->faker->sentence(3),
            'editora' => $this->faker->company,
            'edicao' => $this->faker->numberBetween(1, 10),
            'ano_publicacao' => $this->faker->year,
            'valor' => $this->faker->randomFloat(2, 20, 200),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
