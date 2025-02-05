<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Recipient;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition()
    {
        return [
            'recipient_id' => Recipient::factory(),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->phoneNumber,
            'entity' => $this->faker->word,
        ];
    }
}
