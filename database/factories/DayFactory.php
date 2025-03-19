<?php

namespace Database\Factories;

use App\Models\Day;
use Illuminate\Database\Eloquent\Factories\Factory;

class DayFactory extends Factory
{
    protected $model = Day::class;

    public function definition()
    {
        static $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        static $index = 0;

        return [
            'name' => $days[$index++] ?? 'Día Extra',
        ];
    }
}
