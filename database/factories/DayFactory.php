<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Day;

class DayFactory extends Factory
{
    protected $model = Day::class;

    public function definition()
    {
        static $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        static $index = 0;

        return [
            'name' => $days[$index++] ?? 'Día Extra'
        ];
    }
}
