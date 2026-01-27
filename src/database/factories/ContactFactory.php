<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // 日本の名前を生成
        $lastNames = ['山田', '佐藤', '鈴木', '田中', '渡辺', '伊藤', '中村', '小林', '加藤', '吉田'];
        $firstNames = ['太郎', '花子', '次郎', '三郎', '美咲', '健太', 'さくら', '大輔', '愛', '翔太'];
        
        // 性別（1=男性、2=女性、3=その他）
        $gender = $this->faker->randomElement([1, 2, 3]);
        
        // カテゴリーIDを取得（存在するカテゴリーからランダムに選択）
        $categoryIds = Category::pluck('id')->toArray();
        // カテゴリーが存在しない場合は1をデフォルト値として使用
        if (empty($categoryIds)) {
            $categoryIds = [1];
        }
        
        // 電話番号を生成（080-1234-5678形式）
        $tel = '0' . $this->faker->randomElement(['80', '90', '70']) . '-' . 
               $this->faker->numerify('####') . '-' . 
               $this->faker->numerify('####');
        
        // 住所を生成
        $prefectures = ['東京都', '大阪府', '愛知県', '福岡県', '神奈川県', '埼玉県', '千葉県', '兵庫県'];
        $cities = ['渋谷区', '新宿区', '港区', '中央区', '千代田区', '世田谷区'];
        $address = $this->faker->randomElement($prefectures) . 
                   $this->faker->randomElement($cities) . 
                   $this->faker->streetAddress();
        
        return [
            'category_id' => $this->faker->randomElement($categoryIds),
            'first_name' => $this->faker->randomElement($firstNames),
            'last_name' => $this->faker->randomElement($lastNames),
            'gender' => $gender,
            'email' => 'test' . uniqid() . '@example.com',
            'tel' => $tel,
            'address' => $address,
            'building' => $this->faker->optional(0.5)->randomElement([
                'マンション101', 'アパート201', 'ビル3階', 'コーポA-102', 'ハイツ205号室'
            ]),
            'detail' => mb_substr($this->faker->realText(120), 0, 120),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }
}
