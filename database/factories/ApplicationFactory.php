<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Application;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Provider\Lorem;

$factory->define(Application::class, function (Faker $faker) {
    return [
        'application_token_no' => $this->faker->numberBetween(1000,100000000),
        'advocates_name' => $this->faker->name,
        'application_type' => $this->faker->numberBetween(1,2),
        'date_of_birth' => now(),
        'cnic_no' => $this->faker->numberBetween(100000000,900000000),
        'active_mobile_no' => $this->faker->numberBetween(100000000,900000000),
        'application_status' => $this->faker->numberBetween(1,6),
        'card_status' => $this->faker->numberBetween(1,3),
    ];
});
