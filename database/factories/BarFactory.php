<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Bar;
use Faker\Generator as Faker;

$factory->define(Bar::class, function (Faker $faker) {
    return [
        'name' => 'Bar Association '. $this->faker->company,
        'district_id' => '1',
        'tehsil_id' => '1',
    ];
});
