<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
class ItemFactory extends Factory
{
    protected $model = Item::class;
    public function definition()
    {
        return [
            'user_id'      => User::factory(),
            'condition_id' => $this->faker->numberBetween(1, 4),
            'name'         => $this->faker->randomElement([
                '腕時計', 'HDD', '革靴', 'ノートPC', 'マイク',
                'ショルダーバッグ', 'タンブラー', 'コーヒーミル',
                'メイクセット', 'ヨガマット', 'フライパンセット',
                'スニーカー', 'テーブルランプ', 'ぬいぐるみ',
                'Bluetoothイヤホン', 'カーディガン', '掃除機',
            ]),
            'description'  => $this->faker->realText(50),
            'price'        => $this->faker->numberBetween(300, 50000),
            'image'        => 'items/default.jpg',
            'brand'        => $this->faker->optional(0.7)->randomElement([
                'Nike', 'adidas', 'UNIQLO', 'ZARA', 'Sony',
                'Apple', 'Dyson', 'IKEA', '無印良品', 'COACH',
            ]),
            'status'       => 'on_sale',
        ];
    }
    public function sold()
    {
        return $this->state(fn () => ['status' => 'sold']);
    }
}