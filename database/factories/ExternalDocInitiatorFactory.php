<?php

namespace Database\Factories;

use App\Models\ExternalDocInitiator;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExternalDocInitiatorFactory extends Factory
{
    protected $model = ExternalDocInitiator::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->unique()->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'address' => $this->faker->address,
            'logo_url' => $this->faker->imageUrl(), // Assuming logo_url is a URL for an image
        ];
    }
}
