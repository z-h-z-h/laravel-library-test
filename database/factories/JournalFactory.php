<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Book;
use App\Journal;
use Faker\Generator as Faker;

$factory->define(Journal::class, function (Faker $faker) {
    return [
        'created_at' => $faker->dateTimeInInterval('-2 years', '+2 years'),
        'book_id' => Book::orderByRaw('rand()')
            ->limit(1)
            ->value('id')
    ];
});