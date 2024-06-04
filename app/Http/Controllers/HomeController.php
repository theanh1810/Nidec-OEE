<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class HomeController extends Controller
{
    private $im;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /**
     * Show the application welcome.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function welcome()
    {
        return view('welcome');
    }
    public function index(Request $request)
    {
        return view('dashboard');
    }

    public function change_language($language)
    {
        // dd($language);  
        Session::put('language', $language);
        return redirect()->back();
    }
}
