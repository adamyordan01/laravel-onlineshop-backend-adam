<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // call user factory
        $users = User::factory()
            ->count(10)
            ->create();

        Category::factory(10)->create();
        Product::factory(100)->create();

        // $categories = Category::factory()
        //     ->count(5)
        //     ->create();

        // $categories->each(function ($category) {
        //     $category->products()->saveMany(
        //         \App\Models\Product::factory()
        //             ->count(10)
        //             ->make()
        //     );
        // });
        
    }
}
