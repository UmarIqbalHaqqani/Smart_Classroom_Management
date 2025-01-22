<?php
namespace Modules\Fees\Database\factories;

use Modules\Fees\Entities\FmFeesGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class FmFeesGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FmFeesGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public $fees_groups=['Library Fee', 'Processing Fee', 'Tuition Fee', 'Development Fee'];
    public $i=0;
    public function definition()
    {
        return [
            'name' => $this->fees_groups[$this->i++] ?? $this->faker->unique()->word,
        ];
    }
}

