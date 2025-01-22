<?php

namespace Database\Factories;

use App\SmClass;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;
class SmClassFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmClass::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public $class = ['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5', 'Class 6', 'Class 7', 'Class 8', 'Class 9', 'Class 10'];
    public $i = 0;
    public function definition(): array
    {
        return [
            'class_name' => $this->class[$this->i++] ?? $this->faker->word,
            'school_id' => 1
        ];
    }
}
