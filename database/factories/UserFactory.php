<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    static $password;
    if (empty($password)) {
        $password = Hash::make(Str::random(8));
    }
    $dates = $faker->dateTimeBetween('-30 days');
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->freeEmail,
        'password' => $password,
        'enabled' => $faker->boolean(75),
        'bio' => $faker->boolean ? $faker->sentence : null,
        'verified' => $faker->boolean(25),
        'username' => $faker->userName,
        'created_at' => $dates,
        'updated_at' => $dates,
    ];
});
