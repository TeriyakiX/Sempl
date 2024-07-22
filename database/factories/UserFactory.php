<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'login' => $this->faker->userName,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'birthdate' => $this->faker->date,
            'app_name' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail,
            'address' => $this->faker->address,
            'role' => $this->faker->randomElement(['0']),
            'people_living_with' => $this->faker->numberBetween(1, 10),
            'has_children' => $this->faker->boolean,
            'pets' => $this->faker->randomElement(['none', 'cat', 'dog', 'bird', 'other']),
            'average_monthly_income' => $this->faker->numberBetween(1000, 10000),
            'percentage_spent_on_cosmetics' => $this->faker->numberBetween(1, 100),
            'vk_profile' => $this->faker->url,
            'telegram_profile' => $this->faker->userName,
            'profile_photo' => null,
            'delivery_address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'street' => $this->faker->streetName,
            'house_number' => $this->faker->buildingNumber,
            'apartment_number' => $this->faker->optional()->buildingNumber,
            'entrance' => $this->faker->optional()->randomDigitNotNull,
            'postal_code' => $this->faker->postcode,
        ];
    }
}
