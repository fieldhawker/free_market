<?php

namespace App\Http\Controllers\Admin;

use DB;
use Log;
use Auth;
//use Hash;
use Input;
use Session;
use OperationLogsClass;
use App\User;
//use App\OperationLogs;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class UsersController
 * @package App\Http\Controllers\Admin
 */
class UsersController extends Controller
{

    const MESSAGE_REGISTER_END    = 'register';
    const MESSAGE_UPDATE_END      = 'update';
    const MESSAGE_DELETE_END      = 'delete';
    const MESSAGE_NOT_FOUND_END   = 'not found';
    const MESSAGE_VALID_ERROR_END = 'error';
    const SCREEN_NUMBER_REGISTER  = 110;
    const SCREEN_NUMBER_UPDATE    = 120;
    const SCREEN_NUMBER_DELETE    = 130;

    private $user;
    private $ope;

    /**
     * UsersController constructor.
     */
    public function __construct(User $user, OperationLogsClass $ope)
    {
        $this->middleware('auth:admin');

        $this->user = $user;
        $this->ope  = $ope;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = $this->user->query();
        $users = $query->orderBy('id', 'desc')->get();

//        $users = $query->orderBy('id','desc')->paginate(10);

        return view('admin.users.index')->with('users', $users);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        $input["name"]     = $request->name;
        $input["kana"]     = $request->kana;
        $input["email"]    = $request->email;
        $input["password"] = $request->password;

        $exception = DB::transaction(function () use ($input) {

            $id = $this->user->registerGetId($input);

            if ($id == false) {

                $errors = $this->user->errors();

                Session::flash('message', self::MESSAGE_VALID_ERROR_END);

                return redirect('/admin/users/create/')
                  ->with('user', $this->user)
                  ->with('errors', $errors)
                  ->withInput();

            }

            Log::info('会員が登録されました。', ['id' => $id]);

            $data = [
              'screen_number' => self::SCREEN_NUMBER_REGISTER,
              'target_id'     => $id,
              'operator'      => Auth::guard("admin")->user()->id,
              'comment'       => json_encode($input, JSON_UNESCAPED_UNICODE),
            ];

            $id = $this->ope->registerGetId($data);

            Log::info('操作ログが登録されました。', ['id' => $id]);

            Session::flash('message', self::MESSAGE_REGISTER_END);

            return redirect('/admin/users');

        });

        return $exception;

    }


    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function show($id)
    {
        return redirect('/admin/users');
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return $this
     */
    public function edit(Request $request, $id)
    {

        //レコードを検索
        $user = $this->user->findOrFail($id);

        //検索結果をビューに渡す
        return view('admin.users.update')
          ->with('user', $user);

    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {

        $input["name"]  = $request->name;
        $input["kana"]  = $request->kana;
        $input["email"] = $request->email;

        $exception = DB::transaction(function () use ($input, $id) {

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

            $data = [
              'screen_number' => self::SCREEN_NUMBER_UPDATE,
              'target_id'     => $id,
              'operator'      => Auth::guard("admin")->user()->id,
              'comment'       => json_encode($input, JSON_UNESCAPED_UNICODE),
            ];

            $id = $this->ope->registerGetId($data);

            Log::info('操作ログが登録されました。', ['id' => $id]);

            Session::flash('message', self::MESSAGE_UPDATE_END);

            return redirect('/admin/users');

        });

        return $exception;

    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {

        $exception = DB::transaction(function () use ($id) {

            // 削除対象レコードを検索
            $user = $this->user->find($id);

            // 論理削除
            $user->delete();

            Log::info('会員が削除されました。', ['id' => $id]);

            $data = [
              'screen_number' => self::SCREEN_NUMBER_DELETE,
              'target_id'     => $id,
              'operator'      => Auth::guard("admin")->user()->id,
              'comment'       => json_encode($user, JSON_UNESCAPED_UNICODE),
            ];

            $id = $this->ope->registerGetId($data);

            Log::info('操作ログが登録されました。', ['id' => $id]);

            return redirect()->to('/admin/users')->with('message', self::MESSAGE_DELETE_END);

        });

        return $exception;

    }
}
