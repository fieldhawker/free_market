<?php

namespace App\Http\Controllers\Admin;

use DB;
use Hash;
use Input;
use Session;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class UsersController
 * @package App\Http\Controllers\Admin
 */
class UsersController extends Controller
{

    /**
     *
     */
    const MESSAGE_REGISTER_END    = 'register';
    /**
     *
     */
    const MESSAGE_UPDATE_END      = 'update';
    /**
     *
     */
    const MESSAGE_DELETE_END      = 'delete';
    /**
     *
     */
    const MESSAGE_NOT_FOUND_END   = 'not found';
    /**
     *
     */
    const MESSAGE_VALID_ERROR_END = 'error';

    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = User::query();
        $users = $query->orderBy('id', 'asc')->get();

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

        $user = new USER();

        $input["name"]     = $request->name;
        $input["email"]    = $request->email;
        $input["password"] = $request->password;

        if ($user->validate($input)) {

            $id = $user->insertGetId($input);

            Session::flash('message', self::MESSAGE_REGISTER_END);

            return redirect('/admin/users');

        } else {

            $errors = $user->errors();

            Session::flash('message', self::MESSAGE_VALID_ERROR_END);

            return redirect('/admin/users/create/')
              ->with('user', $user)
              ->with('errors', $errors);

        }

    }

    /**
     * @param $id
     */
    public function show($id)
    {
        //
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
        $user = User::findOrFail($id);

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
        $user = new User();

        $input["name"]  = $request->name;
        $input["email"] = $request->email;

        if ($user->validate($input, $id)) {

            $user->update_users($input, $id);

            Session::flash('message', self::MESSAGE_UPDATE_END);

            return redirect('/admin/users');

        } else {

            $errors = $user->errors();

            Session::flash('message', self::MESSAGE_VALID_ERROR_END);

            return redirect('/admin/users/' . $id . '/edit/')
              ->with('user', $user)
              ->with('errors', $errors);

        }

    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // 削除対象レコードを検索
        $user = User::find($id);
        // 論理削除
        $user->delete();

        return redirect()->to('/admin/users')->with('message', self::MESSAGE_DELETE_END);
    }
}
