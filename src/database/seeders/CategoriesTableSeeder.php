<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();

        $categories = [
            ['content' => '商品のお届けについて', 'created_at' => $now, 'updated_at' => $now],
            ['content' => '商品の交換について', 'created_at' => $now, 'updated_at' => $now],
            ['content' => '商品トラブル', 'created_at' => $now, 'updated_at' => $now],
            ['content' => 'ショップへのお問い合わせ', 'created_at' => $now, 'updated_at' => $now],
            ['content' => 'その他', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('categories')->insert($categories);
    }
}
