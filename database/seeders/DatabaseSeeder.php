<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'client',
            'email' => 'client@mail.com',
            'password' => bcrypt('password')
        ]);

        $category = Category::create([
            'name' => 'category1',
            'description' => 'desc1',
            'price' => 50,
            'picture' => '/storage/categories/home.png',
            'needs_certification' => true
        ]);

        $supplier = User::create([
            'name' => 'supplier',
            'email' => 'supplier@mail.com',
            'password' => bcrypt('password')
        ]);
        $supplier->role = 'supplier';
        $supplier->category_id = $category->id;
        $supplier->save();

        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'password' => bcrypt('password')
        ]);
        $admin->role = 'admin';
        $admin->save();
    }
}
