<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Student;
use Faker\Generator as Faker;

$factory->define(Student::class, function (Faker $faker) {
    $birth_date = \Carbon\Carbon::now()
        ->subDays(random_int(1, 30))
        ->subMonths(random_int(1, 12))
        ->subYears(random_int(18, 30))
        ->toDateString();

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'birth_date' => $birth_date
    ];
});
