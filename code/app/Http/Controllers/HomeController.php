<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Mail;

use App\Mail\MyTestMail;

use Session;

use Illuminate\Support\Facades\Auth;

use DB;

use Config;

use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $role = Auth::user()->user_role;
        if($role==1){
            return Redirect::to('userList');
        }else{
            return redirect()->route('logout');
        }
        
    }



    public function sendMemberLoginDetailsMemb()
    {
        $user = Auth::user();
        $listOfMember = DB::table('member')
                       ->where('role', 1)
                       ->get();
        foreach($listOfMember as $listOfMembers){
            $data['name']=$listOfMembers->member_name;
            $data['id']=$listOfMembers->member_id;
            $data['email']=$listOfMembers->email;
            $data['password']=$listOfMembers->member_password;

            Mail::send('emails.myTestMail', ['data' => $data], function($message) use ($data)
            {
                $message->from('g4gaganvaid@gmail.com', "APAA");
                $message->subject("Welcome to APAA");
                $message->to($data['email']);
            });
        }
        \Session::flash('emailMemberSucc', 'Email sent successfully. ');
        return Redirect::to('memberList');
    }




    public function sendSpeakerLoginDetails()
    {
        $user = Auth::user();
        $listOfMember = DB::table('member')
                       ->where('role', 2)
                       ->get();
        foreach($listOfMember as $listOfMembers){
            $data['name']=$listOfMembers->member_name;
            $data['id']=$listOfMembers->member_id;
            $data['email']=$listOfMembers->email;
            $data['password']=$listOfMembers->member_password;

            Mail::send('emails.myTestMail', ['data' => $data], function($message) use ($data)
            {
                $message->from('g4gaganvaid@gmail.com', "APAA");
                $message->subject("Welcome to APAA");
                $message->to($data['email']);
            });
        }
        \Session::flash('emailSpeakerSucc', 'Email sent successfully. ');
        return Redirect::to('speakerList');
    }



    public function sendAttendeeLoginDetails()
    {
        $user = Auth::user();
        $listOfMember = DB::table('member')
                       ->where('role', 3)
                       ->get();
        foreach($listOfMember as $listOfMembers){
            $data['name']=$listOfMembers->member_name;
            $data['id']=$listOfMembers->member_id;
            $data['email']=$listOfMembers->email;
            $data['password']=$listOfMembers->member_password;

            Mail::send('emails.myTestMail', ['data' => $data], function($message) use ($data)
            {
                $message->from('g4gaganvaid@gmail.com', "APAA");
                $message->subject("Welcome to APAA");
                $message->to($data['email']);
            });
        }
        \Session::flash('emailAttendSucc', 'Email sent successfully. ');
        return Redirect::to('attendeesList');
    }


}
