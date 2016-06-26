<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();

        DB::table('users')->insert([
          'name' => 'テスト',
          'email' => 'dev@se-project.co.jp',
          'password' => bcrypt('password'),
        ]);

        DB::table('users')->insert([
          'name' => 'テスト2',
          'email' => 'takano@se-project.co.jp',
          'password' => bcrypt('password'),
        ]);

        for ($i = 1; $i < 100; $i++) {
            DB::table('users')->insert([
              'name' => 'テスト' . $i,
              'email' => 'test' . $i . '@example.com',
              'password' => bcrypt('password'),
            ]);
        }
    }
}
