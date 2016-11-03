<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UsersControllerTest extends TestCase
{
    use DatabaseMigrations;


    /**
     *
     */
    function setUp()
    {

        parent::setUp();


    }

    /**
     *  リダイレクトの確認
     *
     * @return void
     */
    public function testRedirectPage()
    {
        // 未ログインはログイン画面にリダイレクト
        $this->visit('/admin/')->see('Admin Login');
        $this->visit('/admin/users/create/')->see('Admin Login');

    }

    /**
     * 一覧画面表示
     *
     * @return void
     */
    public function testListPage()
    {

        // ログインしていたら一覧画面を表示
        $admin = factory(App\Models\Admin::class)->create();
        $this->actingAs($admin, 'admin');

        $this->visit('/admin/users')->see('会員一覧');

    }

    /**
     *
     */
    public function test会員登録()
    {

        // ログインする
        $admin = factory(App\Models\Admin::class)->create();
        $this->actingAs($admin, 'admin');

        // 会員を登録する
        $this
          ->visit('/admin/users/create/')
          // 画面表示に成功しているか
          ->see('会員登録')
          // 初期値の確認
          ->seeInField('name', '')
          ->seeInField('kana', '')
          ->seeInField('email', '')
          ->seeInField('password', '')
          // テストデータの入力
          ->type('テスト', 'name')
          ->type('テスト', 'kana')
          ->type('takano@se-project.co.jp', 'email')
          ->type('takanotakano', 'password')
          ->press('登録')
          ->seeStatusCode(200)
          ->dontSee('エラーが発生しました!')
          ->see('登録が完了しました。');

    }

}
