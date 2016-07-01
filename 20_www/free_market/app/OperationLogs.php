<?php

namespace App;

use DB;
use Validator;
use Illuminate\Database\Eloquent\Model;

class OperationLogs extends Model
{


    /**
     * @var string
     */
    protected $table = "operation_logs";


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'screen_number',
      'target_id',
      'comment',
      'executed_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * @var array
     */
    private $rules = [
      'screen_number' => 'required|numeric|min:1|max:4',
      'target_id'     => 'required|numeric|min:1|max:11',
      'executed_at'   => 'required|date',
    ];

    /**
     * @var array
     */
    private $messages = [
      'required' => ':attributeフィールドは必須です。',
      'numeric'  => ':attributeフィールドは数字で入力してください。',
      'min'      => ':attributeフィールドは:sizeより大きく。',
      'max'      => ':attributeフィールドは:sizeより小さく。',
      'date'     => ':attributeフィールドは日付型で入力してください。',
    ];

    /**
     * @var
     */
    private $errors;

    /**
     * @return mixed
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * @param        $data
     * @param string $id
     *
     * @return bool
     */
    public function validate($data)
    {

        $rules = $this->rules;

        $v = Validator::make($data, $rules, $this->messages);

        if ($v->fails()) {
            // この部分注意
            $this->errors = $v->errors();

            return false;
        }

        return true;
    }

//    /**
//     * @param $data
//     */
//    public function registerGetId($data)
//    {
//        $id = null;
//
//        DB::beginTransaction();
//
//        // インサート処理
//        $id = DB::table('operation_logs')->insertGetId($data);
//
//        DB::commit();
//
//        return $id;
//
//    }

}
