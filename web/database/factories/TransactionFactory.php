<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sender' =>  \App\Models\Client::factory()->create()->id,
            'receiver' => \App\Models\Client::factory()->create()->id,
            'amount' => $this->faker->randomFloat(2,100, 1000),
            'currency' => $this->faker->currencyCode,
        ];
    }
}
