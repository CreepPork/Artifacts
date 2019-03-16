<?php

use Faker\Generator as Faker;

$factory->define(App\Upload::class, function (Faker $faker) {
    $filename = $faker->file('storage/tests', 'public/storage', false);

    return [
        'filename' => $filename,
        'original_filename' => str_random(10) . '.zip',
        'path' => 'storage/' . $filename
    ];
});
