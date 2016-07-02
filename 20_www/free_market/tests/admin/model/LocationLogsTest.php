<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LocationLogsTest extends TestCase
{

    use WithoutMiddleware;

    /**
     *
     */
//    public function testRegisterGetId(){
//
//        $this->withoutMiddleware();
//
//        Factory::create('User', ['age' => 20]);
//        Factory::create('User', ['age' => 30]);
//
//        $faker = Faker\Factory::create('ja_JP');
//        
//        $input["name"]     = 'テスト';
//        $input["kana"]     = 'テスト';
//        $input["email"]    = $faker->unique()->email;
//        $input["password"] = 'password';
//
//        $data = [
//          'screen_number' => 110,
//          'target_id'     => 10,
//          'operator'      => 20,
//          'comment'       => json_encode($input, JSON_UNESCAPED_UNICODE),
//        ];
//
//        $oldest = (new User)->registerGetId($data);
//
//        $this->assertEquals(30, $oldest->age);
//
//        $this->get('/api/users')
//          ->seeJson([
//            'email' => 'dev@se-project.co.jp',
//          ]);
//
//    }
    
    
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->assertTrue(true);
    }
}
