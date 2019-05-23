<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Author;
use App\Book;
use Faker\Generator as Faker;

$factory->define(Book::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
    ];
});

$factory->afterCreating(Book::class, function (Book $book, $faker) {
    $authors = Author::select('id')
        ->orderByRaw('rand()')
        ->limit($faker->randomDigitNotNull)
        ->get();

    $book->authors()
        ->attach($authors);
});