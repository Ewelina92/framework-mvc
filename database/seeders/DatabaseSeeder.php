<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Book::truncate();

        Book::create([
            'isbn' => "9789178871858",
            'title' => "Städa hållbart med Ekotipset: husmorsknep och ekohacks",
            'author' => "Ellinor Sirén",
            'image' => "https://s2.adlibris.com/images/59920084/stada-hallbart-med-ekotipset-husmorsknep-och-ekohacks.jpg",
        ]);

        Book::create([
            'isbn' => "9789178872121",
            'title' => "Jennys sommar",
            'author' => "Jenny Warsén",
            'image' => "https://s1.adlibris.com/images/60180380/jennys-sommar.jpg",
        ]);

        Book::create([
            'isbn' => "9789178870417",
            'title' => "Wok, ris, nudlar",
            'author' => "Jennie Walldén",
            'image' => "https://s1.adlibris.com/images/57918624/wok-ris-nudlar.jpg",
        ]);
    }
}
