<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Account::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => \App\Models\Client::factory()->create()->id,
            'currency' => $this->faker->currencyCode,
            'balance' => $this->faker->randomFloat(2,10000,100000)
        ];
    }
}
