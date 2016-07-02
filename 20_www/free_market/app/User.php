<?php

namespace App;

use DB;
use Hash;
use Validator;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class User
 * @package App
 */
class User extends Authenticatable
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = "users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'uid',
      'name',
      'kana',
      'email',
      'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      'password',
      'remember_token',
    ];

    /**
     * @var array
     */
    private $rules = [
      'name'     => 'required|string|min:2|max:256',
      'kana'     => 'required|string|min:2|max:256',
      'email'    => 'required|email|unique:users,email,%s,id,deleted_at,NULL',
      'password' => 'sometimes|required|min:6',
    ];

    /**
     * @var array
     */
    private $messages = [
//      'required' => ':attributeフィールドは必須です。',
//      'string' => ':attributeフィールドは文字列で入力してください。',
//      'min' => ':attributeフィールドは:sizeより大きく。',
//      'max' => ':attributeフィールドは:sizeより小さく。',
//      'email' => ':attributeフィールドはメールで。',
//      'unique' => ':attributeフィールドはユニークで。',
    ];

    /**
     * 日付により変更を起こすべき属性
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var
     */
    private $errors;

//    /**
//     *
//     */
//    protected static function boot()
//    {
//        parent::boot();
//
//        self::creating(function ($user) {
//            $user->onCreatingHandler();
//        });
//        self::updating(function ($user) {
//            return $user->onUpdatingHandler();
//        });
//    }
//
//
//    /**
//     * @return bool
//     */
//    private function onCreatingHandler()
//    {
//        //update時刻を記録したり何かする
//        return true; //キャンセルしたいときはfalseを返す
//    }
//
//
//    /**
//     * @return bool
//     */
//    private function onUpdatingHandler()
//    {
//        //update時刻を記録したり何かする
//        return true; //キャンセルしたいときはfalseを返す
//    }

    /**
     * @param        $data
     * @param string $id
     *
     * @return bool
     */
    public function validate($data, $id = 'NULL')
    {

        $rules          = $this->rules;
        $rules['email'] = sprintf($rules['email'], $id);

        $v = Validator::make($data, $rules, $this->messages);

        if ($v->fails()) {
            // この部分注意
            $this->errors = $v->errors();

            return false;
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function errors()
    {
        return $this->errors;
    }


    /**
     * @param $data
     *
     * @return null
     */
    public function registerGetId($data)
    {

        if ($this->validate($data)) {

            $data["password"] = Hash::make($data["password"]);
            $data["uid"]      = hash("sha256", uniqid(mt_rand(), 1));
            $id               = DB::table('users')->insertGetId($data);

            return $id;

        } else {

            return false;

        }
    }


    /**
     * @param $data
     * @param $id
     *
     * @return bool
     */
    public function updateUsers($data, $id)
    {

        if ($this->validate($data, $id)) {

            // アップデート処理
            User::where('id', '=', $id)->update(
              ['name' => $data["name"]],
              ['kana' => $data["kana"]],
              ['email' => $data["email"]]
            );

            return true;

        } else {

            return false;

        }

    }
}
