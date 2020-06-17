<?php

namespace App\Http\Controllers;

use Session;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

use DB;

use Config;

use Excel;

use App\Http\Controllers\YourReminder;

use Mail;



class staticController extends Controller

{

    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function __construct()

    {

      //  $this->middleware('auth');

    }



    /**

     * Show the application dashboard.

     *

     * @return \Illuminate\Http\Response

     */


    public function terms(){
        $terms=DB::table('terms')->where('status',1)->first();
        return view('terms', [
          'terms' => $terms
        ]); 
    }



    

}















