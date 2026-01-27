<?php

namespace Database\Seeders;

use App\Models\Contact;
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
        // カテゴリーを先に作成
        $this->call([
            CategoriesTableSeeder::class,
        ]);
        
        // お問い合わせのダミーデータを35件作成
        Contact::factory(35)->create();
    }
}
