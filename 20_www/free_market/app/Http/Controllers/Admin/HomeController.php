<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class HomeController
 * @package App\Http\Controllers\Admin
 */
class HomeController extends Controller
{

    private $user;
    
    /**
     * HomeController constructor.
     */
    public function __construct(User $user)
    {
        $this->middleware('auth:admin');
        $this->user = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_count = $this->user->count();

        return view('admin.home')->with('user_count', $user_count);
    }
}
