<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Condition;
use App\Models\Item;
use App\Models\Category;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seller = User::where('name', '出品者A')->first();

        if (!$seller) {
            $seller = User::factory()->create(['name' => '出品者A']);
        }

        $sellerId = $seller->id;

        $conditionIds = Condition::pluck('id', 'name');

        $items = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolex',
                'categories' => ['ファッション', 'メンズ'],
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'condition_id' => $conditionIds['良好'],
                'user_id' => $sellerId,
            ],

            [
                'name' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'categories' => ['家電'],
                'description' => '高速で信頼性の高いハードディスク',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'condition_id' => $conditionIds['目立った傷や汚れなし'],
                'user_id' => $sellerId,
            ],

            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand' => null,
                'categories' => ['キッチン'],
                'description' => '新鮮な玉ねぎ3束のセット',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'condition_id' =>  $conditionIds['やや傷や汚れあり'],
                'user_id' => $sellerId,
            ],

            [
                'name' => '革靴',
                'price' => 4000,
                'brand' => null,
                'categories' => ['ファッション'],
                'description' => 'クラシックなデザインの革靴',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'condition_id' =>  $conditionIds['状態が悪い'],
                'user_id' => $sellerId,
            ],

            [
                'name' => 'ノートPC',
                'price' => 45000,
                'brand' => null,
                'categories' => ['家電'],
                'description' => '高性能なノートパソコン',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'condition_id' =>  $conditionIds['良好'],
                'user_id' => $sellerId,
            ],

            [
                'name' => 'マイク',
                'price' => 8000,
                'brand' => null,
                'categories' => ['家電'],
                'description' => '高音質のレコーディング用マイク',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'condition_id' =>   $conditionIds['目立った傷や汚れなし'],
                'user_id' => $sellerId,
            ],

            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => null,
                'categories' => ['ファッション', 'レディース'],
                'description' => 'おしゃれなショルダーバッグ',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'condition_id' =>  $conditionIds['やや傷や汚れあり'],
                'user_id' => $sellerId,
            ],

            [
                'name' => 'タンブラー',
                'price' => 500,
                'brand' => null,
                'categories' => ['キッチン'],
                'description' => '使いやすいタンブラー',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'condition_id' =>  $conditionIds['状態が悪い'],
                'user_id' => $sellerId,
            ],

            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbacks',
                'categories' => ['キッチン'],
                'description' => '手動のコーヒーミル',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'condition_id' =>  $conditionIds['良好'],
                'user_id' => $sellerId,
            ],

            [
                'name' => 'メイクセット',
                'price' => 2500,
                'brand' => null,
                'categories' => ['コスメ'],
                'description' => '便利なメイクアップセット',
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'condition_id' =>  $conditionIds['目立った傷や汚れなし'],
                'user_id' => $sellerId,
            ],
        ];

        foreach ($items as $data) {
            $categoryNames = $data['categories'];
            unset($data['categories']);

            $item = Item::create($data);

            $categoryIds = Category::whereIn('name', $categoryNames)->pluck('id');
            $item->categories()->attach($categoryIds);
        }
    }
}
