<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'category_id' => 1,
                'isbn' => '978-979-22-3896-4',
                'pages' => 529,
                'description' => 'Novel tentang perjuangan anak-anak di Belitung untuk mendapatkan pendidikan.',
                'cover_image' => 'https://images.tokopedia.net/img/cache/700/VqbcmM/2022/10/20/ae0b3d8d-8f9d-4a7e-ae29-891af4d0c6d5.jpg',
                'stock' => 5,
                'available' => 5,
                'rating' => 4.8
            ],
            [
                'title' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer',
                'publisher' => 'Hasta Mitra',
                'category_id' => 2,
                'isbn' => '978-979-461-228-4',
                'pages' => 535,
                'description' => 'Novel sejarah tentang kehidupan di masa kolonial Belanda.',
                'cover_image' => 'https://cdn.gramedia.com/uploads/items/9789794284520.jpg',
                'stock' => 4,
                'available' => 4,
                'rating' => 4.9
            ],
            [
                'title' => 'Negeri 5 Menara',
                'author' => 'Ahmad Fuadi',
                'publisher' => 'Gramedia Pustaka Utama',
                'category_id' => 3,
                'isbn' => '978-602-8811-04-4',
                'pages' => 423,
                'description' => 'Kisah inspiratif tentang perjuangan santri meraih mimpi.',
                'cover_image' => 'https://cdn.gramedia.com/uploads/items/9786028811040.jpg',
                'stock' => 3,
                'available' => 3,
                'rating' => 4.7
            ],
            [
                'title' => 'Sang Pemimpi',
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'category_id' => 1,
                'isbn' => '978-979-22-4280-0',
                'pages' => 292,
                'description' => 'Sekuel dari Laskar Pelangi tentang mimpi dan perjuangan.',
                'cover_image' => 'https://cdn.gramedia.com/uploads/items/9789792242805.jpg',
                'stock' => 4,
                'available' => 4,
                'rating' => 4.6
            ],
            [
                'title' => 'Perahu Kertas',
                'author' => 'Dee Lestari',
                'publisher' => 'Bentang Pustaka',
                'category_id' => 4,
                'isbn' => '978-979-22-5901-3',
                'pages' => 456,
                'description' => 'Novel roman tentang cinta dan seni.',
                'cover_image' => 'https://cdn.gramedia.com/uploads/items/9789792259018.jpg',
                'stock' => 6,
                'available' => 6,
                'rating' => 4.5
            ],
            [
                'title' => 'Supernova',
                'author' => 'Dee Lestari',
                'publisher' => 'Bentang Pustaka',
                'category_id' => 5,
                'isbn' => '978-979-22-1901-7',
                'pages' => 326,
                'description' => 'Novel fiksi sains tentang cinta, filsafat, dan sains.',
                'cover_image' => 'https://cdn.gramedia.com/uploads/items/9789792219012.jpg',
                'stock' => 3,
                'available' => 3,
                'rating' => 4.8
            ],
            [
                'title' => 'Ronggeng Dukuh Paruk',
                'author' => 'Ahmad Tohari',
                'publisher' => 'Gramedia Pustaka Utama',
                'category_id' => 1,
                'isbn' => '978-979-22-2345-6',
                'pages' => 198,
                'description' => 'Novel tentang kehidupan seorang ronggeng di desa.',
                'cover_image' => 'https://cdn.gramedia.com/uploads/items/9789792223453.jpg',
                'stock' => 4,
                'available' => 4,
                'rating' => 4.7
            ],
            [
                'title' => 'Cantik Itu Luka',
                'author' => 'Eka Kurniawan',
                'publisher' => 'Gramedia Pustaka Utama',
                'category_id' => 1,
                'isbn' => '978-979-22-6789-0',
                'pages' => 520,
                'description' => 'Novel epik tentang keluarga dan sejarah Indonesia.',
                'cover_image' => 'https://cdn.gramedia.com/uploads/items/9789792267884.jpg',
                'stock' => 2,
                'available' => 2,
                'rating' => 4.6
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
