<?php
namespace Modules\Fees\Database\factories;

use Modules\Fees\Entities\FmFeesInvoiceChield;
use Illuminate\Database\Eloquent\Factories\Factory;

class FmFeesInvoiceChieldFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FmFeesInvoiceChield::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    // public $feesAmount = $this->faker->numberBetween($min = 1000, $max = 2000); 
    public function definition()
    {
        $feesAmount = $this->faker->numberBetween($min = 1000, $max = 2000);
        return [
            'amount' => $feesAmount,
            'sub_total' => $feesAmount,
            'due_amount' => $feesAmount,
            'school_id' => 1,
            'academic_id' => 1,
        ];
    }
}

