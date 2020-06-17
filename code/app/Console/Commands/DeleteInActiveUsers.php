<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class DeleteInActiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DeleteInActiveUsers:deleteusers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete inactive users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

       if(function_exists('date_default_timezone_set')) {
            date_default_timezone_set("Asia/Kolkata");
        }
        $currentDate = date("m/d/Y");

        $currentTime1 =  date("H:i:s");
        echo $currentTime=date('h:i a', strtotime($currentTime1));
        echo "<br>";

        $todayNewsList = DB::table('news')
                      ->where('dateNews', $currentDate)
                      ->where('statusNews', 1)
                      ->get();
        $abcde=0;
        foreach ($todayNewsList as $todayNewsLists) {
            

            $newsDate=$todayNewsLists->dateNews;
            $newsTime=$todayNewsLists->timeNews;

            if($currentDate==$newsDate){

                $endTime = strtotime("-0 minutes", strtotime($newsTime));
                echo $requiredTime=date('h:i a', $endTime);
                
                if($requiredTime==$currentTime){
                 
                    $userListPush = DB::table('member')->get();
       
                    foreach ($userListPush as $userListPushed) {
                        if(!empty($userListPushed->member_type) && !empty($userListPushed->regId)){

                            $userType = $userListPushed->member_type;
                            $userRegId = $userListPushed->regId;
                            $msgid="NEWS";
                            $message=$todayNewsLists->titleNews;
                            $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
                            
                            $fields = array(
                                'to' => $userRegId,
                                'notification' => array('title' => 'APAA News', 'body' => $message, 'tag'=> 'APAA News'),
                                'data' => array('message' => $message,'msgid' => $msgid)
                            );

                            if ($userType=="AND0000") {
                                $headers = array(
                                    'Authorization:key=' . 'AIzaSyCunP_9q6MhNPcKEgCfZsrUz_RhXdF5rRs',
                                    'Content-Type:application/json'
                                ); 

                            }
                            else if($userType=="IOS1111") {

                                $headers = array(
                                    'Authorization:key=' . 'AIzaSyDYUsZsVTPqn6z6slV8vyZUEIFBF0p2TAE',
                                    'Content-Type:application/json'
                                ); 
                            }
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm); 
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); 
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                            $result = curl_exec($ch);
                            curl_close($ch);
                        }
                   
                    }
                }
            }
        $abcde++;
        }
    }
}