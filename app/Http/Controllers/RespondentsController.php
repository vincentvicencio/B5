<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RespondentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('respondents.index');
    }

    public function list(Request $request)
    {

    }

    public function show()
    {
        return view('respondents.show');
    }
}
