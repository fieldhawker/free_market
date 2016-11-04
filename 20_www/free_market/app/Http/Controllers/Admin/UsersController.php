<?php

namespace App\Http\Controllers\Admin;

use App\Services\UsersService;

use Util;


use DB;
use Log;
use Auth;
//use Hash;
use Input;
use Session;
use OperationLogsClass;
use App\Models\User;
use Config;
use App\Models\Exclusives;
//use App\OperationLogs;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class UsersController
 *
 * @package App\Http\Controllers\Admin
 *
 */
class UsersController extends Controller
{

    const MESSAGE_REGISTER_END    = 'register';
    const MESSAGE_UPDATE_END      = 'update';
    const MESSAGE_DELETE_END      = 'delete';
    const MESSAGE_NOT_FOUND_END   = 'not found';
    const MESSAGE_VALID_ERROR_END = 'error';
    const MESSAGE_MODIFIED_END    = 'modified';
//    const SCREEN_NUMBER_REGISTER  = 110;
    const SCREEN_NUMBER_UPDATE    = 120;
    const SCREEN_NUMBER_DELETE    = 130;

    private $user;
    private $ope;
    private $exclusives;


    private $users;

    /**
     * UsersController constructor.
     *
     */
    public function __construct(User $user, OperationLogsClass $ope, Exclusives $exclusives, UsersService $users)
    {
        $this->users = $users;

        $this->middleware('auth:admin');


        $this->user       = $user;
        $this->ope        = $ope;
        $this->exclusives = $exclusives;
    }

    /**
     * 会員一覧画面を表示する
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::info(Util::generateLogMessage('START'));

        // ----------------------------
        // 会員情報をすべて取得する
        // ----------------------------

        $users = $this->users->findAll();

        Log::info(Util::generateLogMessage('END'));

        return view('admin.users.index')->with('users', $users);
    }

    /**
     * 会員登録画面を表示する
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Log::info(Util::generateLogMessage('START'));

        // ----------------------------
        // 
        // ----------------------------

        Log::info(Util::generateLogMessage('END'));

        return view('admin.users.create');
    }

    /**
     * 会員を登録する
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        Log::info(Util::generateLogMessage('START'));

        // ----------------------------
        // リクエストパラメータを取得
        // ----------------------------
        
        $input = $this->users->getRequest($request);

        // ----------------------------
        // バリデーション
        // ----------------------------

        if (!$this->users->validate($input)) {

            $errors = $this->users->getErrors();

            Session::flash('message', self::MESSAGE_VALID_ERROR_END);

            Log::info(Util::generateLogMessage('END 入力内容に不備がありました'));

            return redirect('/admin/users/create/')
              ->with('user', $input)
              ->with('errors', $errors)
              ->withInput();

        }

        // ----------------------------
        // 会員を登録する
        // ----------------------------
        
        $exception = $this->users->registerUser($input);

        if ($exception) {

            Log::info(Util::generateLogMessage('END 会員の登録に失敗しました'));

            return $exception;

        }

        Session::flash('message', self::MESSAGE_REGISTER_END);

        Log::info(Util::generateLogMessage('END'));

        return redirect('/admin/users');

    }


    /**
     * @param $id
     *
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function show($id)
    {

        // ----------------------------
        // レコードを検索
        // ----------------------------

        $user = $this->user->findOrFail($id);

        if (!$user) {
            return redirect('/admin/users');
        }

        //検索結果をビューに渡す
        return view('admin.users.show')
          ->with('user', $user);
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return $this
     */
    public function edit(Request $request, $id)
    {

        // ----------------------------
        // レコードを検索
        // ----------------------------

        $user = $this->user->findOrFail($id);

        if (!$user) {
            return redirect('/admin/users');
        }

        // ----------------------------
        // 他の管理者が編集中か
        // ----------------------------

        $data = [
          'screen_number' => self::SCREEN_NUMBER_UPDATE,
          'target_id'     => $id,
          'operator'      => Auth::guard("admin")->user()->id,
        ];

        $is_exclusives = $this->exclusives->isExpiredByOtherAdmin($data);

        if (!$is_exclusives) {

            // ----------------------------
            // 編集中にする
            // ----------------------------

            $data["expired_at"] = date("Y/m/d H:i:s", strtotime(Config::get('const.exclusives_time')));
            $exclusives_id      = $this->exclusives->insertGetId($data);

        }

        // ----------------------------
        //検索結果をビューに渡す
        // ----------------------------

        return view('admin.users.update')
          ->with('user', $user)
          ->with('is_exclusives', $is_exclusives);

    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        Log::info('START', ['file' => __FILE__, 'class' => __CLASS__]);

        $input["name"]  = $request->name;
        $input["kana"]  = $request->kana;
        $input["email"] = $request->email;

        Log::info('Input parameter', ['id' => $id]);
        Log::info('Input parameter', ['name' => $input["name"]]);
        Log::info('Input parameter', ['kana' => $input["kana"]]);
        Log::info('Input parameter', ['email' => $input["email"]]);

        $exception = DB::transaction(function () use ($input, $id) {

            // ----------------------------
            // 他の管理者が編集中か
            // ----------------------------

            $exclusives = [
              'screen_number' => self::SCREEN_NUMBER_UPDATE,
              'target_id'     => $id,
              'operator'      => Auth::guard("admin")->user()->id,
            ];

            $is_exclusives = $this->exclusives->isExpiredByOtherAdmin($exclusives);

            if ($is_exclusives) {

                $url = sprintf('/admin/users/%s/edit/', $id);

                Session::flash('message', self::MESSAGE_VALID_ERROR_END);

                return redirect($url)
                  ->with('user', $this->user)
                  ->withInput();

            }

            // ----------------------------
            // 存在チェック
            // ----------------------------

            $user = $this->user->find($id);

            if (!$user) {

                Session::flash('message', self::MESSAGE_NOT_FOUND_END);

                return redirect('/admin/users/');

            }

            // ----------------------------
            // 更新を開始
            // ----------------------------

            $result = $this->user->updateUsers($input, $id);

            if ($result == false) {

                $errors = $this->user->errors();
                $url    = sprintf('/admin/users/%s/edit/', $id);

                Session::flash('message', self::MESSAGE_VALID_ERROR_END);

                return redirect($url)
                  ->with('user', $this->user)
                  ->with('errors', $errors)
                  ->withInput();

            }

            Log::info('会員が更新されました。', ['id' => $id]);

            // ----------------------------
            // 排他制御を削除
            // ----------------------------

            $result = $this->exclusives->deleteExpiredByMine($exclusives);

            // ----------------------------
            // 操作ログを記録
            // ----------------------------

            $data = [
              'screen_number' => self::SCREEN_NUMBER_UPDATE,
              'target_id'     => $id,
              'operator'      => Auth::guard("admin")->user()->id,
              'comment'       => json_encode($input, JSON_UNESCAPED_UNICODE),
            ];

            $id = $this->ope->registerGetId($data);

            Log::info('操作ログが登録されました。', ['id' => $id]);

            Session::flash('message', self::MESSAGE_UPDATE_END);

        });

        if ($exception) {

            Log::info('EXECUTE FAILURE!', ['file' => __FILE__, 'class' => __CLASS__]);

            return $exception;

        }

        Log::info('END', ['file' => __FILE__, 'class' => __CLASS__]);

        return redirect('/admin/users');

    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Log::info('START', ['file' => __FILE__, 'class' => __CLASS__]);

        Log::info('Input parameter', ['$id' => $id]);

        $exception = DB::transaction(function () use ($id) {

            // ----------------------------
            // 他の管理者が編集中か
            // ----------------------------

            $exclusives = [
              'screen_number' => self::SCREEN_NUMBER_UPDATE,
              'target_id'     => $id,
              'operator'      => Auth::guard("admin")->user()->id,
            ];

            $is_exclusives = $this->exclusives->isExpiredByOtherAdmin($exclusives);

            if ($is_exclusives) {

                Session::flash('message', self::MESSAGE_MODIFIED_END);

                return redirect('/admin/users/');

            }

            // ----------------------------
            // 削除対象レコードを検索
            // ----------------------------

            $user = $this->user->find($id);

            // ----------------------------
            // 論理削除
            // ----------------------------

            $user->delete();

            Log::info('会員が削除されました。', ['id' => $id]);

            // ----------------------------
            // 操作ログを記録
            // ----------------------------

            $data = [
              'screen_number' => self::SCREEN_NUMBER_DELETE,
              'target_id'     => $id,
              'operator'      => Auth::guard("admin")->user()->id,
              'comment'       => json_encode($user, JSON_UNESCAPED_UNICODE),
            ];

            $id = $this->ope->registerGetId($data);

            Log::info('操作ログが登録されました。', ['id' => $id]);

        });

        if ($exception) {

            Log::info('EXECUTE FAILURE!', ['file' => __FILE__, 'class' => __CLASS__]);

            return $exception;

        }

        Log::info('END', ['file' => __FILE__, 'class' => __CLASS__]);

        return redirect()->to('/admin/users')->with('message', self::MESSAGE_DELETE_END);

    }
}
