<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // database/factories/CarFactory.php

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::all()->random()->id ?? \App\Models\User::factory(),
            'car_image' => 'car_photos/'. fake()->randomElement([
                'test-bg-picture.jpg',
                'test-bg.jfif',
                'test-bg(2).jfif',
                'test-bg(3).jfif',
                'test-bg(4).jfif',
                'test-bg(5).jpg',
                'test-bg(6).jfif',
            ]), 
            'date_owned' => fake()->date(),
            'brand' => fake()->randomElement(['Toyota', 'Honda', 'Nissan', 'Mitsubishi', 'Ford']),
            'model' => fake()->word(),
            'price' => fake()->numberBetween(200, 1000), 
            'transmission' => fake()->randomElement(['Automatic', 'Manual']),
            'fuel_type' => fake()->randomElement(['Gasoline', 'Diesel', 'Electric']),
            'description' => fake()->sentence(),
        ];
    }
}
