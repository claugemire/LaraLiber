<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "title" => Str::random(10),
            "author" => "Author: " . Str::random(4),
            "page_count" => mt_rand(10, 9999),
            "isbn" => mt_rand(1000000000, 9999999999),
            "description" => Str::random(75),
            "thumbnail" => "http://books.google.com/books/content?id=JXmWBQAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api",
            "user_id" => 'overiden',
            "order" => 0,
            "read" => FALSE
        ];

    }
}
