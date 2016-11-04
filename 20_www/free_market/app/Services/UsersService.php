<?php
namespace App\Services;

use DB;
use Log;
use Auth;

use App\Repositories\UsersRepositoryInterface;
use App\Repositories\OperationLogsRepositoryInterface;

/**
 * Class UsersService
 */
class UsersService
{
    const SCREEN_NUMBER_REGISTER = 110;
    const SCREEN_NUMBER_UPDATE   = 120;
    const SCREEN_NUMBER_DELETE   = 130;

    protected $users;
    protected $ope;
    
    /**
     * UsersService constructor.
     *
     * @param UsersRepositoryInterface         $users
     * @param OperationLogsRepositoryInterface $ope
     */
    public function __construct(UsersRepositoryInterface $users, OperationLogsRepositoryInterface $ope)
    {
        $this->users = $users;
        $this->ope   = $ope;
    }

    /**
     * 会員情報をすべて取得する
     *
     * @return mixed
     */
    public function findAll()
    {

        return $this->users->findAll();

    }

    /**
     * リクエストパラメータから必要な情報を抽出する
     *
     * @param $request
     *
     * @return mixed
     */
    public function getRequest($request)
    {

        $input["name"]     = $request->name;
        $input["kana"]     = $request->kana;
        $input["email"]    = $request->email;
        $input["password"] = $request->password;

        Log::info('入力されたパラメータ', $input);

        return $input;

    }

    /**
     * 配列に対してバリデーション判定を行う
     * 
     * @param $params
     *
     * @return mixed
     */
    public function validate($params)
    {

        return $this->users->validate($params);

    }

    /**
     * 発生したエラー情報を取得する
     * 
     * @return mixed
     */
    public function getErrors()
    {
        return $this->users->getErrors();
    }

    /**
     * 会員を登録する
     * 
     * @param $input
     *
     * @return mixed
     */
    public function registerUser($input)
    {

        $exception = DB::transaction(function () use ($input) {

            // ----------------------------
            // 会員登録
            // ----------------------------

            $id          = $this->users->insertGetId($input);
            $input["id"] = $id;

            Log::info('会員が登録されました。', $input);

            // ----------------------------
            // 操作ログ登録
            // ----------------------------

            $data = [
              'screen_number' => self::SCREEN_NUMBER_REGISTER,
              'target_id'     => $id,
              'operator'      => Auth::guard("admin")->user()->id,
              'comment'       => json_encode($input, JSON_UNESCAPED_UNICODE),
            ];

            $id         = $this->ope->registerGetId($data);
            $data["id"] = $id;

            Log::info('操作ログが登録されました。', $data);

        });

        return $exception;

    }

}