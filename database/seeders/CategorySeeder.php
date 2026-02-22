<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Fiction', 'Novel', 'Mystery', 'Thriller', 'Fantasy', 'Science Fiction', 'Horror', 'Romance', 'Historical Fiction', 'Adventure', 'Drama', 'Poetry', 'Biography', 'Autobiography', 'Self-Help', 'Psychology', 'Philosophy', 'Religion', 'Business', 'Economics', 'Education', 'Children\'s', 'Young Adult', 'Comics', 'Manga', 'Science', 'Technology', 'Health', 'Cooking', 'Travel'];
    
            foreach ($categories as $category) {
                Category::create([
                    'name' => $category,
                ]);
            }
    }
}
