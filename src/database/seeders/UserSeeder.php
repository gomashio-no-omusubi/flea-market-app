<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //1.テストユーザー
        $me = User::create([
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $me->profile()->create([
            'postcode' => '123-4567',
            'address' => '東京都渋谷区...',
            'building' => 'テストビル101',
        ]);

        //2.出品者
        $seller = User::create([
            'name'     => '出品者A',
            'email'    => 'seller@example.com',
            'password' => Hash::make('password'),
        ]);

        $seller->profile()->create([
            'postcode' => '000-0000',
            'address'  => '大阪府大阪市西区...',
        ]);

        //3.その他の一般ユーザー
        User::factory()->count(10)->create();
    }
}
