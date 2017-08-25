<?php


$genders = [
    'male', 'female'
];

$countries = [
    "Germany", "Greece", "Greenland", "Hungary", "Iceland", "India", "Indonesia", "Luxembourg", "Mexico", "Romania"
];

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Http\Models\CustomerModel::class, function (Faker\Generator $faker) use ($genders, $countries) {
    return [
        'first_name'        => $faker->firstName,
        'last_name'         => $faker->lastName,
        'email'             => $faker->unique()->safeEmail,
        'country'           => $faker->randomElement($countries),
        'gender'            => $faker->randomElement($genders),
        'bonus'             => rand(5, 20),
    ];
});

$factory->define(App\Http\Models\DepositModel::class, function (Faker\Generator $faker) {
    $amount = rand(1, 999);
    $bonus  = $amount / 10;
    return [
        'real_amount'          => $amount,
        'bonus_amount'         => $bonus,
        'created_at'        => \Carbon\Carbon::now()->subDays(rand(0, 7))
    ];
});

$factory->define(App\Http\Models\WithdrawModel::class, function (Faker\Generator $faker) {
    $amount = rand(1, 999);

    return [
        'amount'            => $amount,
        'created_at'        => \Carbon\Carbon::now()->subDays(rand(0, 7))
    ];
});
