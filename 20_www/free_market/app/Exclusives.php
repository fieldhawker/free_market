<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Exclusives extends Model
{
    /**
     * @var string
     */
    protected $table = "exclusives";


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'screen_number',
      'target_id',
      'operator',
      'expired_at',
      'comment'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * @param $data
     *
     * @return bool
     */
    public function isExpiredByOtherAdmin($data)
    {
        $count_exclusives = DB::table($this->table)
          ->where('screen_number', '=', $data["screen_number"])
          ->where('target_id', '=', $data["target_id"])
          ->where('operator', '<>', $data["operator"])
          ->where('expired_at', '>', date('Y/m/d H:i:s'))
          ->count();

        $is_exclusives = ($count_exclusives > 0) ? true : false;

        return $is_exclusives;
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function deleteExpiredByMine($data)
    {
        // 削除対象レコードを検索
        $result = DB::table($this->table)
          ->where('screen_number', '=', $data["screen_number"])
          ->where('target_id', '=', $data["target_id"])
          ->where('operator', '=', $data["operator"])
          ->delete();

        return $result;
    }
}
