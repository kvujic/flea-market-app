<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call([
            CategoriesTableSeeder::class,
            ConditionsTableSeeder::class,
            UsersTableSeeder::class,
            ItemsTableSeeder::class,
            CategoryItemTableSeeder::class,
            LikesTableSeeder::class,
            PurchasesTableSeeder::class,
            ProfilesTableSeeder::class,
            CommentsTableSeeder::class,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
