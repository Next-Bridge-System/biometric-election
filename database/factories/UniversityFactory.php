<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\University;
use Faker\Generator as Faker;

$factory->define(University::class, function (Faker $faker) {
    return [
        'type' => '1',
        'name' => 'Univesity of '. $this->faker->company,
        'phone' => $this->faker->numerify('+92-3##-#######'),
        'email' => $faker->unique()->safeEmail,
        'country_id' => '166',
        'province_id' => '1',
        'district_id' => '1',
        'city' => $this->faker->name,
        'address' => $this->faker->address,
        'postal_code' => $this->faker->numberBetween(1000,9000),
    ];
});
