<?php
namespace App\Http\Controllers;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Config;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Session;
use Redirect;
class apiController extends Controller
{   
    protected function getToken()
    {
        return hash_hmac('sha256', str_random(40), config('app.key'));
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function registration(Request $request)
    {
        $school_id= $request->school_id;
        $social_id= $request->social_id;
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $email = $request->email;
        $password = $request->password;
        $mobile = $request->mobile;
        $country = $request->country;
        $city = $request->city;
        $state = $request->state;
        $address = $request->address;
        $chat_password = $request->chat_password;
        $mydate = Carbon::now();
        $mydate->toDateString();
        $mytime = Carbon::now();
        $mytime->toDateTimeString();

        if(!isset($firstname) || $firstname == "")            
        {               
            $return = array('result' => 'Please enter the firstname','status_code'=>204);
            exit(json_encode($return));       
        
        }
        if(!isset($school_id) || $school_id == "")            
        {               
            $return = array('result' => 'Please select school','status_code'=>204);
            exit(json_encode($return));       
        
        }
        if(!isset($email) || $email == "")            
        {               
            $return = array('result' => 'Please enter the email','status_code'=>204);
            exit(json_encode($return));         
        
        }
     
        if(!isset($password) || $password == "")            
        {               
            $return = array('result' => 'Please enter the password','status_code'=>204);
            exit(json_encode($return));         
        
        }

        if(@$social_id){
        	$user_password = $request->password;
        	$checkSocial = DB::table('users')->where('social_id',$social_id)->first();
        	if(@$checkSocial){
	        	$return = array('result' => 'Account already exist','status_code'=>204);
	            exit(json_encode($return));
	        }
    	}else{
    		$user_password = "";
    	}

    	$checkemail = DB::table('users')->where('email',$email)->first();
        if(@$checkemail){
            $return = array('result' => 'Email address already exist','status_code'=>204);
            exit(json_encode($return)); 
        }else{
            if(@$request->profile_pic){
                $files = "profile_pic";
                $file = Input::file($files);
                $destination_path=base_path()."/public/users";
                $filename = time().$file->getClientOriginalName();
                $file->move($destination_path,$filename);
            }else{
                $filename = "";
            }

            $datas = array(
              'fname' => $firstname,
              'social_id' => $social_id,
              'lname' => $lastname,
              'email' => $email,
              'password' => bcrypt($password),
              'spassword' => $user_password,
              'cpassword' => $chat_password,
              'country' => $country,
              'state' => $state,
              'city' => $city,
              'school' => $school_id,
              'mobile' => $mobile,
              'imagesUser' => $filename,
              'address' => $address,
              'enabled' => 0,
              'user_role' => 2,
              'updated_at' => $mydate,
            );
            $insert = DB::table('users')->insert($datas);
            $last_insert_id = DB::getPdo()->lastInsertId();
            if($insert>0){
	            $token = $this->getToken();
	            $tokenData = array(
	              'user_id' => $last_insert_id,
	              'token' => $token,
	            );
	            $inserttoken = DB::table('user_activations')->insert($tokenData);
	            $link = route('user.activate', $token);
	            $message_content = sprintf('Activate account <a href="%s">%s</a>', $link, $link);

	            $data = array( 'email' => $email, 'message_content' => $link, 'firstname' => $firstname, 'from' => 'Bookaway27@hotmail.com', 'from_name' => 'Bookaway' );
	            Mail::send( 'email.send', $data, function( $message ) use ($data)
	            {
	                $message->to( $data['email'] )->from( $data['from'], $data['from_name'] )->subject( 'Welcome!' );
	            });
	            
	            $return = array('result' => 'Check your email for account verification','status_code'=>200);
	            exit(json_encode($return));
            }
        }
    }


    public function activateUser($token) 
    {
        $result = DB::table('user_activations')->where('token',$token)->first();
        $count=count($result);
        if($count>0)
        {
            $datas = array(
              'enabled' => '1',
            );

            $i = DB::table('users')->where('id', $result->user_id)->update($datas);
            return view('email.success');
        }
        else {
            return view('email.failed');
        }
    }

    /* Login for mobile app */
    public function login(Request $request) 
    {
        $email=$request->email;
        $password = $request->password;
        //$chat_password = $request->chat_password;
        if (Auth::attempt(array('email' => $email, 'password' => $password))) {
            $data = DB::table('users')->where('email',$email)->where('enabled',1)->where('user_role',2)->first();
            $count=count($data);
            if($count>0)
            {
                $userId=$data->id;
                $userDetails = DB::table('users')
                             ->select('users.*','schools.name_school as schoolName','countries.name as contName','states.name as stateName','cities.name as cityName')
                             ->leftjoin('schools', 'schools.id_school', '=', 'users.school')
                             ->leftjoin('countries', 'countries.id', '=', 'users.country')
                             ->leftjoin('states', 'states.id', '=', 'users.state')
                             ->leftjoin('cities', 'cities.id', '=', 'users.city')
                             ->where('users.id', $userId)
                             ->where('users.enabled', 1)
                             ->where('users.user_role', 2)
                             //->where('schools.status_school', 1)
                             ->first();
                $publicUrl=str_replace("index.php","", url('/'));
                $prefix = $publicUrl."users/";
                if((@$userDetails)&&(count($userDetails)>0)){
                    $return_array["id"]=$userDetails->id;
                    $return_array["social_id"]=$userDetails->social_id;
                    $return_array["firstname"]=$userDetails->fname;
                    $return_array["lastname"]=$userDetails->lname;
                    $return_array["countryId"]=$userDetails->country;
                    $return_array["countryName"]=$userDetails->contName;
                    $return_array["stateId"]=$userDetails->state;
                    $return_array["stateName"]=$userDetails->stateName;
                    $return_array["cityId"]=$userDetails->city;
                    $return_array["cityName"]=$userDetails->cityName;
                    $return_array["schoolId"]=$userDetails->school;
                    $return_array["schoolName"]=$userDetails->schoolName;
                    $return_array["address"]=$userDetails->address;
                    $return_array["email"]=$userDetails->email;
                    $return_array["mobile"]=$userDetails->mobile;
                    $return_array["password"]=$request->password;
                    $return_array["chat_password"]=$userDetails->cpassword;
                    if(@$userDetails->imagesUser){
                        $return_array["imagesUser"] = $prefix.$userDetails->imagesUser;
                    }else{
                        $return_array["imagesUser"] = "";
                    }
                    $return_array["enabled"]=$userDetails->enabled;
                    $return_array["role"]=$userDetails->user_role;
                    $return = array('result' => $return_array,'status_code'=>200);
                    exit(json_encode($return));
                }else{
                    $return = array('result' => 'Your school is disabled','status_code'=>204);
                    exit(json_encode($return));
                }
            }
            else{
                $return = array('result' => 'Account is not verified','status_code'=>204);
                exit(json_encode($return));
            }   
        }
        else{
            $return = array('result' => 'Incorrect Credential!','status_code'=>204);
            exit(json_encode($return)); 
        }
    }



    /* Social Login */
    public function socialLogin(Request $request) 
    {
        $socialId=$request->socialId;
        //$chat_password = $request->chat_password;
        //if (Auth::attempt(array('social_id' => $socialId, 'password' => $password))) {
            //$data = DB::table('users')->where('social_id',$socialId)->where('enabled',1)->where('user_role',2)->first();
            $data = DB::table('users')->where('social_id',$socialId)->where('user_role',2)->first();
            $count=count($data);
            if($count>0)
            {
            	if($data->enabled==1){
	                $userId=$data->id;
	                $userDetails = DB::table('users')
	                             ->select('users.*','schools.name_school as schoolName','countries.name as contName','states.name as stateName','cities.name as cityName')
	                             ->leftjoin('schools', 'schools.id_school', '=', 'users.school')
	                             ->leftjoin('countries', 'countries.id', '=', 'users.country')
	                             ->leftjoin('states', 'states.id', '=', 'users.state')
	                             ->leftjoin('cities', 'cities.id', '=', 'users.city')
	                             ->where('users.id', $userId)
	                             ->where('users.enabled', 1)
	                             ->where('users.user_role', 2)
	                             ->first();
	                $publicUrl=str_replace("index.php","", url('/'));
	                $prefix = $publicUrl."users/";
	                if((@$userDetails)&&(count($userDetails)>0)){
	                    $return_array["id"]=$userDetails->id;
	                    $return_array["social_id"]=$userDetails->social_id;
	                    $return_array["firstname"]=$userDetails->fname;
	                    $return_array["lastname"]=$userDetails->lname;
	                    $return_array["countryId"]=$userDetails->country;
	                    $return_array["countryName"]=$userDetails->contName;
	                    $return_array["stateId"]=$userDetails->state;
	                    $return_array["stateName"]=$userDetails->stateName;
	                    $return_array["cityId"]=$userDetails->city;
	                    $return_array["cityName"]=$userDetails->cityName;
	                    $return_array["schoolId"]=$userDetails->school;
	                    $return_array["schoolName"]=$userDetails->schoolName;
	                    $return_array["address"]=$userDetails->address;
	                    $return_array["email"]=$userDetails->email;
	                    $return_array["mobile"]=$userDetails->mobile;
	                    $return_array["password"]=$userDetails->spassword;
                        $return_array["chat_password"]=$userDetails->cpassword;
	                    if(@$userDetails->imagesUser){
	                        $return_array["imagesUser"] = $prefix.$userDetails->imagesUser;
	                    }else{
	                        $return_array["imagesUser"] = "";
	                    }
	                    $return_array["enabled"]=$userDetails->enabled;
	                    $return_array["role"]=$userDetails->user_role;
	                    $return = array('result' => $return_array,'status_code'=>200);
	                    exit(json_encode($return));
	                }else{
	                    $return = array('result' => 'Your school is disabled','status_code'=>204);
	                    exit(json_encode($return));
	                }
	            }else{
	            	$return = array('result' => 'Your account is not activated','status_code'=>204);
                	exit(json_encode($return));
	            }
            }
            else{
                $return = array('result' => 'Please try again','status_code'=>204);
                exit(json_encode($return));
            }   
        // }
        // else{
        //     $return = array('result' => 'Incorrect Credential!','status_code'=>204);
        //     exit(json_encode($return));
        // }

        // else{
        //     $firstname = $request->name;
        //     $email = $request->email;
        //     $datas = array(
        //       'social_id' => $socialId,
        //       'fname' => $firstname,
        //       'email' => $email,
        //       'password' => bcrypt($password),
        //       'enabled' => 1,
        //       'user_role' => 2,
        //     );
        //     $insert = DB::table('users')->insert($datas);
        //     if($insert>0){
        //         $userId= DB::getPdo()->lastInsertId();
        //         $return_array["id"]=$userId;
        //         $return_array["social_id"]=$socialId;
        //         $return_array["firstname"]=$firstname;
        //         $return_array["lastname"]="";
        //         $return_array["countryId"]="";
        //         $return_array["countryName"]="";
        //         $return_array["stateId"]="";
        //         $return_array["stateName"]="";
        //         $return_array["cityId"]="";
        //         $return_array["cityName"]="";
        //         $return_array["schoolId"]="";
        //         $return_array["schoolName"]="";
        //         $return_array["address"]="";
        //         $return_array["email"]=$email;
        //         $return_array["mobile"]="";
        //         $return_array["imagesUser"] = "";
        //         $return_array["password"]=$password;
        //         $return = array('result' => $return_array,'status_code'=>200);
        //         exit(json_encode($return));
        //     }
        //     else{
        //         $return = array('result' => 'Incorrect Credential!','status_code'=>204);
        //         exit(json_encode($return));
        //     }
        // }
    }

    //-scls
    public function schools()
    {   
        $schools = DB::table('schools')
                     ->select('schools.*','countries.name as contName','states.name as stateName','cities.name as cityName')
                     ->join('countries', 'countries.id', '=', 'schools.country_school')
                     ->join('states', 'states.id', '=', 'schools.state_school')
                     ->join('cities', 'cities.id', '=', 'schools.city_school')
                     ->where('status_school', 1)
                     ->get();
        if((@$schools)&&(count($schools)>0)){
        	$xx=0;
            foreach($schools as $data)
            {
            	$chkSubject = DB::table('school_subjects')
							->select('id_school')
							->where('id_school', $data->id_school)
							->where('status', 1)
							->first();

				$chkBooks = DB::table('books')
							->select('id_book')
							->where('id_school', $data->id_school)
							->where('book_status', 1)
							->first();


                if(((@$chkSubject)&&(count($chkSubject)>0))&&((@$chkBooks)&&(count($chkBooks)>0))){
	                $res['id_school'] = $data->id_school;
	                $res['name_school'] = $data->name_school;
	                $res['country_id'] = $data->country_school;
	                $res['city_id'] = $data->city_school;
	                $res['state_id'] = $data->state_school;
	                $res['country_name'] = $data->contName;
	                $res['city_name'] = $data->stateName;
	                $res['state_name'] = $data->cityName;
	                $res['address'] = $data->address_school;
	                $return_array[] = $res;
	            $xx++;
	            }
            }
            if($xx>0){
	            $return = array('result' => $return_array,'status_code'=>200);
	            exit(json_encode($return));
	        }else{
	        	$return = array('result' => 'No data found','status_code'=>204);
            	exit(json_encode($return));
	        }
        }else{
            $return = array('result' => 'No data found','status_code'=>204);
            exit(json_encode($return));
        }
    }



    //-scls
    public function all_schools()
    {   
        $schools = DB::table('schools')
                     ->select('schools.*','countries.name as contName','states.name as stateName','cities.name as cityName')
                     ->join('countries', 'countries.id', '=', 'schools.country_school')
                     ->join('states', 'states.id', '=', 'schools.state_school')
                     ->join('cities', 'cities.id', '=', 'schools.city_school')
                     ->where('status_school', 1)
                     ->get();
        if((@$schools)&&(count($schools)>0)){
            foreach($schools as $data)
            {
                $res['id_school'] = $data->id_school;
                $res['name_school'] = $data->name_school;
                $res['country_id'] = $data->country_school;
                $res['city_id'] = $data->city_school;
                $res['state_id'] = $data->state_school;
                $res['country_name'] = $data->contName;
                $res['city_name'] = $data->stateName;
                $res['state_name'] = $data->cityName;
                $res['address'] = $data->address_school;
                $return_array[] = $res;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }else{
            $return = array('result' => 'No data found','status_code'=>204);
            exit(json_encode($return));
        }
    }


    //-subs
    public function subjects(Request $request)
    {
        if(@$request->schoolId){
            $schoolId=$request->schoolId;   
            $subjects = DB::table('school_subjects')
                      ->join('subjects', 'subjects.id_subject', '=', 'school_subjects.id_subject')
                      ->where('school_subjects.id_school', $schoolId)
                      ->where('school_subjects.status', 1)
                      ->where('subjects.status_subject', 1)
                      ->orderBy('subjects.title_subject', 'asc')
                      ->get();
        }else{
            $subjects = DB::table('subjects')
                     ->where('status_subject', 1)
                     ->orderBy('subjects.title_subject', 'asc')
                     ->get();
        }

        if((@$subjects)&&(count($subjects)>0)){
        	$yy=0;
            foreach($subjects as $data)
            {
            	$chkBooks1 = DB::table('books')
							->select('books.id_book')
							->where('id_subject', $data->id_subject)
							->where('book_status', 1);
							if(@$schoolId){
								$chkBooks1->where('id_school', $schoolId);
							}
				$chkBooks=$chkBooks1->first();
                if((@$chkBooks)&&(count($chkBooks)>0)){
	                $res['subject_id'] = $data->id_subject;
	                $res['subject_name'] = $data->title_subject;
	                $return_array[] = $res;
	            $yy++;
	            }
            }
            if($yy>0){
	            $return = array('result' => $return_array,'status_code'=>200);
	            exit(json_encode($return));
	        }else{
	        	$return = array('result' => 'No data found','status_code'=>204);
            	exit(json_encode($return));
	        }
        }else{
            $return = array('result' => 'No data found','status_code'=>204);
            exit(json_encode($return));
        }
    }


    public function all_subjects(Request $request)
    {
        if(@$request->schoolId){
            $schoolId=$request->schoolId;   
            $subjects = DB::table('school_subjects')
                      ->join('subjects', 'subjects.id_subject', '=', 'school_subjects.id_subject')
                      ->where('school_subjects.id_school', $schoolId)
                      ->where('school_subjects.status', 1)
                      ->where('subjects.status_subject', 1)
                      ->orderBy('subjects.title_subject', 'asc')
                      ->get();
        }else{
            $subjects = DB::table('subjects')
                     ->where('status_subject', 1)
                     ->orderBy('subjects.title_subject', 'asc')
                     ->get();
        }

        if((@$subjects)&&(count($subjects)>0)){
            foreach($subjects as $data)
            {
                $res['subject_id'] = $data->id_subject;
                $res['subject_name'] = $data->title_subject;
                $return_array[] = $res;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }else{
            $return = array('result' => 'No data found','status_code'=>204);
            exit(json_encode($return));
        }
    }


    //-bks
    public function books(Request $request)
    {
        $schoolId=$request->schoolId;
        $subjectId=$request->subjectId;
        $userId=$request->userId;
        if($request->price==1){
        	$column='book_price';
        	$direction='Asc';
        }
        elseif($request->price==2){
        	$column='book_price';
        	$direction='Desc';
        }
        elseif($request->date==1){
        	$column='book_date';
        	$direction='Desc';
        }else{
        	$column='id_book';
        	$direction='Asc';	
        }
        $books = DB::table('books')
                     ->select('books.*','schools.name_school as schoolName','subjects.title_subject as subjectName','users.fname as userFname','users.lname as userLname', 'users.email as userEmail', 'book_conditions.title_condition')
                     ->join('schools', 'schools.id_school', '=', 'books.id_school')
                     ->join('subjects', 'subjects.id_subject', '=', 'books.id_subject')
                     ->join('users', 'users.id', '=', 'books.id_user')
                     ->join('book_conditions', 'book_conditions.id_condition', '=', 'books.book_condition')
                     ->where('books.id_school', $schoolId)
                     ->where('books.id_subject', $subjectId)
                     ->where('books.book_status', 1)
                     ->where('schools.status_school', 1)
                     ->where('subjects.status_subject', 1)
                     ->where('users.enabled', 1)
                     ->orderBy($column, $direction)
                     ->get();
 
        $publicUrl=str_replace("index.php","", url('/'));
        $prefix = $publicUrl."books/";
        if((@$books)&&(count($books)>0)){

        	$abc=0;
            foreach($books as $data)
            {
            	if($data->id_user==$userId){}else{
                    if(($data->userEmail=="dummyUser1@bookaway.com") || ($data->userEmail=="dummyUser2@bookaway.com") || ($data->userEmail=="dummyUser3@bookaway.com") || ($data->userEmail=="dummyUser4@bookaway.com") || ($data->userEmail=="dummyUser5@bookaway.com") || ($data->userEmail=="dummyUser6@bookaway.com") || ($data->userEmail=="dummyUser7@bookaway.com") || ($data->userEmail=="dummyUser8@bookaway.com") || ($data->userEmail=="dummyUser9@bookaway.com") || ($data->userEmail=="dummyUser10@bookaway.com") || ($data->userEmail=="dummyUser11@bookaway.com") || ($data->userEmail=="dummyUser12@bookaway.com") || ($data->userEmail=="dummyUser13@bookaway.com") || ($data->userEmail=="dummyUser14@bookaway.com"))
                    {
                        $userEmailId='bookaway27@hotmail.com';

                    }else{
                        $userEmailId=$data->userEmail;
                    }
	                $res["id_book"] = $data->id_book;
	                $res["id_school"] = $data->id_school;
	                $res["schoolName"] = $data->schoolName;
	                $res["id_subject"] = $data->id_subject;
	                $res["subjectName"] = $data->subjectName;
	                $res["id_user"] = $data->id_user;
	                $res["userFname"] = $data->userFname;
	                $res["userLname"] = $data->userLname;
	                $res["userEmail"] = $userEmailId;
	                $res["book_name"] = $data->book_name;
	                $res["book_condition"] = $data->title_condition;
	                if(@$data->book_image){
	                    $res["book_image"] = $prefix.$data->book_image;
	                }else{
	                    $res["book_image"] = "";
	                }
	                $res["book_author"] = $data->book_author;
	                $res["book_price"] = $data->book_price;
	                $res["book_description"] = $data->book_description;
	                $res["book_date"] = $data->book_date;
	                $return_array[] = $res;
	            $abc++;
	            }
            }
            if($abc>0){
            	$return = array('result' => $return_array,'status_code'=>200);
            	exit(json_encode($return));
            }else{
            	$return = array('result' => 'No data found','status_code'=>204);
            	exit(json_encode($return));
            }
        }else{
            $return = array('result' => 'No data found','status_code'=>204);
            exit(json_encode($return));
        }
    }


    //-mp
    public function myProfile(Request $request)
    {   
        $userId=$request->userId;
        $password = $request->password;
        $userDetails = DB::table('users')
                     ->select('users.*','schools.name_school as schoolName','countries.name as contName','states.name as stateName','cities.name as cityName')
                     ->join('schools', 'schools.id_school', '=', 'users.school')
                     ->join('countries', 'countries.id', '=', 'users.country')
                     ->join('states', 'states.id', '=', 'users.state')
                     ->join('cities', 'cities.id', '=', 'users.city')
                     ->where('users.id', $userId)
                     ->where('users.enabled', 1)
                     ->where('users.user_role', 2)
                     ->where('schools.status_school', 1)
                     ->first();
        $publicUrl=str_replace("index.php","", url('/'));
        $prefix = $publicUrl."users/";
        if((@$userDetails)&&(count($userDetails)>0)){
            $return_array["id"]=$userDetails->id;
            $return_array["social_id"]=$userDetails->social_id;
            $return_array["firstname"]=$userDetails->fname;
            $return_array["lastname"]=$userDetails->lname;
            $return_array["countryId"]=$userDetails->country;
            $return_array["countryName"]=$userDetails->contName;
            $return_array["stateId"]=$userDetails->state;
            $return_array["stateName"]=$userDetails->stateName;
            $return_array["cityId"]=$userDetails->city;
            $return_array["cityName"]=$userDetails->cityName;
            $return_array["schoolId"]=$userDetails->school;
            $return_array["schoolName"]=$userDetails->schoolName;
            $return_array["address"]=$userDetails->address;
            $return_array["email"]=$userDetails->email;
            $return_array["mobile"]=$userDetails->mobile;
            $return_array["password"]=$password;
            $return_array["chat_password"]=$userDetails->cpassword;
            if(@$userDetails->imagesUser){
                $return_array["imagesUser"] = $prefix.$userDetails->imagesUser;
            }else{
                $return_array["imagesUser"] = "";
            }
            $return_array["enabled"]=$userDetails->enabled;
            $return_array["role"]=$userDetails->user_role;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }else{
            $return = array('result' => 'No data found','status_code'=>204);
            exit(json_encode($return));
        }
    }


    //-add Bk
    public function addBook(Request $request)
    {   
        $getfees = DB::table('add_book_fees')->where('status_fees', 1)->first();
        $fees=$getfees->amount_fees;
        $id_school=$request->id_school;
        $id_subject=$request->id_subject;
        $id_user=$request->id_user;
        $book_name=$request->book_name;
        $book_image=$request->book_image;
        $book_author=$request->book_author;
        $book_price=$request->book_price;
        $book_description=$request->book_description;
        $book_condition=$request->book_condition;
        $book_status=1;
        $ios_flag=$request->ios_flag;
        if(!isset($id_school) || $id_school == "")            
        {               
            $return = array('result' => 'Please select school','status_code'=>204);
            exit(json_encode($return));       
        }
        if(!isset($id_subject) || $id_subject == "")            
        {               
            $return = array('result' => 'Please select subject','status_code'=>204);
            exit(json_encode($return));       
        }
        if(!isset($id_user) || $id_user == "")            
        {               
            $return = array('result' => 'Please select user id','status_code'=>204);
            exit(json_encode($return));       
        }
        if(!isset($book_name) || $book_name == "")            
        {               
            $return = array('result' => 'Please add book name','status_code'=>204);
            exit(json_encode($return));       
        }
        if(!isset($book_image) || $book_image == "")            
        {               
            $return = array('result' => 'Please add picture of book','status_code'=>204);
            exit(json_encode($return));       
        }
        if(!isset($book_author) || $book_author == "")            
        {               
            $return = array('result' => 'Please add author name','status_code'=>204);
            exit(json_encode($return));       
        }
        if(!isset($book_condition) || $book_condition == "")            
        {               
            $return = array('result' => 'Please add condition of book','status_code'=>204);
            exit(json_encode($return));       
        }
        
        $files = "book_image";
        $file = Input::file($files);
        $destination_path=base_path()."/public/books";
        $filename = time().$file->getClientOriginalName();
        $file->move($destination_path,$filename);
        if(@$filename){
        	if((@$ios_flag)&&($ios_flag==1)){

        		$datas = array(
		            'id_school' => $id_school,
		            'id_subject' => $id_subject,
		            'id_user' => $id_user,
		            'book_name' => $book_name,
		            'book_image' => $filename,
		            'book_author' => $book_author,
		            'book_price' => $book_price,
		            'book_description' => $book_description,
		            'book_condition' => $book_condition,
		            'book_status' => $book_status,
		        );
		        $insertBook = DB::table('books')->insert($datas);
		        if($insertBook>0){
		            $bookId = DB::getPdo()->lastInsertId();
                    $userInfo = DB::table('users')->where('id', $id_user)->first();

                    $pay = array(
                        'book_id' => $bookId,
                        'user_id' => $id_user,
                        'school_id' => $id_school,
                        'subject_id' => $id_subject,
                        'item_name' => $book_name,
                        'trans_currency' => 'USD',
                        'itm_quantity' => 1,
                        'itm_currency' => 'USD',
                        'item_price' => '1.99',
                        'sale_total' => '1.99',
                        'payer_fname' => $userInfo->fname,
                        'payer_lname' => $userInfo->lname,
                        'parent_state' => 'approved',
                        'payer_status' => 'VERIFIED',
                        'merchant_email' => 'bookaway27-facilitator@hotmail.com',
                        'parent_id' => 'IOS Device Approved',
                        'payer_id' => 'IOS Device Approved',
                        'sale_id' => 'IOS Device Approved',
                        'merchant_id' => 'IOS Device Approved',
                        'trans_fee' =>"0.59",
                        'receaved_amount' =>"1.393",
                    );

                    $savePayment = DB::table('payments')->insert($pay);


		            $return_array["bookId"]=$bookId;
		            $return_array["status"]=$book_status;
		            $return = array('result' => $return_array,'status_code'=>200);
		            exit(json_encode($return)); 
		        }
		        else{
		            $return = array('result' => 'Please try again','status_code'=>204);
		            exit(json_encode($return));
		        }

        	}else{
        		$url=url('/').'/paywithpaypal/'.bookaway_encrypt($id_school).'/'.bookaway_encrypt($id_subject).'/'.bookaway_encrypt($id_user).'/'.bookaway_encrypt($book_name).'/'.bookaway_encrypt($filename).'/'.bookaway_encrypt($book_author).'/'.bookaway_encrypt($book_price).'/'.bookaway_encrypt($book_description).'/'.bookaway_encrypt($book_condition).'/'.bookaway_encrypt($fees);

	            $return = array('result' => $url,'status_code'=>200);
	            exit(json_encode($return));
        	}  
        }
        else{
            $return = array('result' => 'Please try again','status_code'=>204);
            exit(json_encode($return));
        }

        // $datas = array(
        //     'id_school' => $id_school,
        //     'id_subject' => $id_subject,
        //     'id_user' => $id_user,
        //     'book_name' => $book_name,
        //     'book_image' => $filename,
        //     'book_author' => $book_author,
        //     'book_price' => $book_price,
        //     'book_description' => $book_description,
        //     'book_condition' => $book_condition,
        //     'book_status' => $book_status,
        // );
        // $insertBook = DB::table('books')->insert($datas);
        // if($insertBook>0){
        //     $bookId = DB::getPdo()->lastInsertId();
        //     $return_array["bookId"]=$bookId;
        //     $return_array["status"]=$book_status;
        //     $return = array('result' => $return_array,'status_code'=>200);
        //     exit(json_encode($return)); 
        // }
        // else{
        //     $return = array('result' => 'Please try again','status_code'=>204);
        //     exit(json_encode($return));
        // }
    }


    //-bks
    public function book_detail(Request $request)
    {   
        $bookId=$request->bookId;
        if(!isset($bookId) || $bookId == "")            
        {               
            $return = array('result' => 'Please select book','status_code'=>204);
            exit(json_encode($return));       
        }
        $getBook = DB::table('books')
                     ->select('books.*','schools.name_school as schoolName','subjects.title_subject as subjectName','users.fname as userFname','users.lname as userLname')
                     ->join('schools', 'schools.id_school', '=', 'books.id_school')
                     ->join('subjects', 'subjects.id_subject', '=', 'books.id_subject')
                     ->join('users', 'users.id', '=', 'books.id_user')
                     ->join('book_conditions', 'book_conditions.id_condition', '=', 'books.book_condition')
                     ->where('books.id_book', $bookId)
                     ->where('schools.status_school', 1)
                     ->where('subjects.status_subject', 1)
                     ->where('users.enabled', 1)
                     ->first();
        $publicUrl=str_replace("index.php","", url('/'));
        $prefix = $publicUrl."books/";
        if((@$getBook)&&(count($getBook)>0)){
            $return_array["id_book"] = $getBook->id_book;
            $return_array["id_school"] = $getBook->id_school;
            $return_array["schoolName"] = $getBook->schoolName;
            $return_array["id_subject"] = $getBook->id_subject;
            $return_array["subjectName"] = $getBook->subjectName;
            $return_array["id_user"] = $getBook->id_user;
            $return_array["userFname"] = $getBook->userFname;
            $return_array["userLname"] = $getBook->userLname;
            $return_array["book_name"] = $getBook->book_name;
            if(@$getBook->book_image){
                $return_array["book_image"] = $prefix.$getBook->book_image;
            }else{
                $return_array["book_image"] = "";
            }
            $return_array["book_author"] = $getBook->book_author;
            $return_array["book_price"] = $getBook->book_price;
            $return_array["book_description"] = $getBook->book_description;
            $return_array["book_date"] = $getBook->book_date;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }else{
            $return = array('result' => 'No data found','status_code'=>204);
            exit(json_encode($return));
        }
    }

    //-subs
    public function book_conditions()
    {
        $conditions = DB::table('book_conditions')
                     ->where('status_condition', 1)
                     ->get();
        if((@$conditions)&&(count($conditions)>0)){
            foreach($conditions as $data)
            {      
                $res['id'] = $data->id_condition;
                $res['title'] = $data->title_condition;
                $return_array[] = $res;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }else{
            $return = array('result' => 'No data found','status_code'=>204);
            exit(json_encode($return));
        }
    }


    //-bks
    public function mybooks(Request $request)
    {   
        $userId=$request->userId;
        if(!isset($userId) || $userId == "")            
        {               
            $return = array('result' => 'Please add user','status_code'=>204);
            exit(json_encode($return));       
        }
        $books = DB::table('books')
                     ->select('books.*','schools.name_school as schoolName','subjects.title_subject as subjectName','users.fname as userFname','users.lname as userLname', 'users.email as userEmail', 'book_conditions.title_condition')
                     ->join('schools', 'schools.id_school', '=', 'books.id_school')
                     ->join('subjects', 'subjects.id_subject', '=', 'books.id_subject')
                     ->join('users', 'users.id', '=', 'books.id_user')
                     ->join('book_conditions', 'book_conditions.id_condition', '=', 'books.book_condition')
                     ->where('books.id_user', $userId)
                     ->where('books.book_status', 1)
                     ->where('schools.status_school', 1)
                     ->where('subjects.status_subject', 1)
                     ->where('users.enabled', 1)
                     ->get();
        $publicUrl=str_replace("index.php","", url('/'));
        $prefix = $publicUrl."books/";
        if((@$books)&&(count($books)>0)){
            foreach($books as $data)
            {
                $res["id_book"] = $data->id_book;
                $res["id_school"] = $data->id_school;
                $res["schoolName"] = $data->schoolName;
                $res["id_subject"] = $data->id_subject;
                $res["subjectName"] = $data->subjectName;
                $res["id_user"] = $data->id_user;
                $res["userFname"] = $data->userFname;
                $res["userLname"] = $data->userLname;
                $res["userEmail"] = $data->userEmail;
                $res["book_name"] = $data->book_name;
                $res["book_condition"] = $data->title_condition;
                if(@$data->book_image){
                    $res["book_image"] = $prefix.$data->book_image;
                }else{
                    $res["book_image"] = "";
                }
                $res["book_author"] = $data->book_author;
                $res["book_price"] = $data->book_price;
                $res["book_description"] = $data->book_description;
                $res["book_date"] = $data->book_date;
                $return_array[] = $res;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }else{
            $return = array('result' => 'You have not posted any book yet, please click on Sell icon to add one now!','status_code'=>204);
            exit(json_encode($return));
        }
    }



    public function update_profile(Request $request)
    {   
        $userId = $request->user_id;
        $school_id= $request->school_id;
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $password = $request->password;
        $mydate = Carbon::now();
        $mydate->toDateString();
        $mytime = Carbon::now();
        $mytime->toDateTimeString();

        if(!isset($userId) || $userId == "")            
        {               
            $return = array('result' => 'Please enter user id','status_code'=>204);
            exit(json_encode($return));       
        }
        if(!isset($firstname) || $firstname == "")            
        {               
            $return = array('result' => 'Please enter the firstname','status_code'=>204);
            exit(json_encode($return));       
        }
        if(!isset($school_id) || $school_id == "")            
        {               
            $return = array('result' => 'Please select school','status_code'=>204);
            exit(json_encode($return));
        }

        $checkUser = DB::table('users')->where('id',$userId)->first();
        if(@$checkUser){
            if(@$request->profile_pic){
                $files = "profile_pic";
                $file = Input::file($files);
                $destination_path=base_path()."/public/users";
                $filename = time().$file->getClientOriginalName();
                $file->move($destination_path,$filename);
            }else{
                $filename = $checkUser->imagesUser;
            }
            $datas = array(
              'fname' => $firstname,
              'lname' => $lastname,
              'school' => $school_id,
              'imagesUser' => $filename,
              'updated_at' => $mydate,
            );
            $updateUser = DB::table('users')->where('id',$userId)->update($datas);
            if($updateUser>0){
                $userDetails = DB::table('users')
                             ->select('users.*','schools.name_school as schoolName','countries.name as contName','states.name as stateName','cities.name as cityName')
                             ->leftjoin('schools', 'schools.id_school', '=', 'users.school')
                             ->leftjoin('countries', 'countries.id', '=', 'users.country')
                             ->leftjoin('states', 'states.id', '=', 'users.state')
                             ->leftjoin('cities', 'cities.id', '=', 'users.city')
                             ->where('users.id', $userId)
                             ->where('users.enabled', 1)
                             ->where('users.user_role', 2)
                             ->first();
                $publicUrl=str_replace("index.php","", url('/'));
                $prefix = $publicUrl."users/";
                if((@$userDetails)&&(count($userDetails)>0)){
                    $return_array["id"]=$userDetails->id;
                    $return_array["social_id"]=$userDetails->social_id;
                    $return_array["firstname"]=$userDetails->fname;
                    $return_array["lastname"]=$userDetails->lname;
                    $return_array["countryId"]=$userDetails->country;
                    $return_array["countryName"]=$userDetails->contName;
                    $return_array["stateId"]=$userDetails->state;
                    $return_array["stateName"]=$userDetails->stateName;
                    $return_array["cityId"]=$userDetails->city;
                    $return_array["cityName"]=$userDetails->cityName;
                    $return_array["schoolId"]=$userDetails->school;
                    $return_array["schoolName"]=$userDetails->schoolName;
                    $return_array["address"]=$userDetails->address;
                    $return_array["email"]=$userDetails->email;
                    $return_array["mobile"]=$userDetails->mobile;
                    $return_array["password"]=$password;
                    $return_array["chat_password"]=$userDetails->cpassword;
                    if(@$userDetails->imagesUser){
                        $return_array["imagesUser"] = $prefix.$userDetails->imagesUser;
                    }else{
                        $return_array["imagesUser"] = "";
                    }
                    $return_array["enabled"]=$userDetails->enabled;
                    $return_array["role"]=$userDetails->user_role;
                    $return = array('result' => $return_array,'status_code'=>200);
                    exit(json_encode($return));
                }
            }  
        }else{
            $return = array('result' => 'User not found','status_code'=>204);
            exit(json_encode($return));
        }
   
    }

    public function change_password(Request $request)
    {   
        $userId = $request->user_id;
        $email = $request->email;
        $oldPass= $request->oldpassword;
        $newPass = $request->newpassword;
        $mydate = Carbon::now();
        $mydate->toDateString();
        $mytime = Carbon::now();
        $mytime->toDateTimeString();

        if(!isset($userId) || $userId == "")            
        {               
            $return = array('result' => 'Please enter user id','status_code'=>204);
            exit(json_encode($return));       
        }
        if(!isset($email) || $email == "")            
        {               
            $return = array('result' => 'Please enter the email','status_code'=>204);
            exit(json_encode($return));       
        }
        if(!isset($oldPass) || $oldPass == "")            
        {               
            $return = array('result' => 'Please add old password','status_code'=>204);
            exit(json_encode($return));
        }
        if(!isset($newPass) || $newPass == "")            
        {               
            $return = array('result' => 'Please add new password','status_code'=>204);
            exit(json_encode($return));
        }

        if(Auth::attempt(array('email' => $email, 'password' => $oldPass))) {
             $datas = array(
              'password' => bcrypt($newPass),
              'updated_at' => $mydate,
            );
            $updatePassword = DB::table('users')->where('id',$userId)->update($datas);
            if($updatePassword>0){
                $return = array('result' => 'Password updated successfully','status_code'=>200);
                exit(json_encode($return));
            }
        }
        else{
            $return = array('result' => 'Incorrect Old password','status_code'=>204);
            exit(json_encode($return)); 
        }
   
    }



    public function feedback(Request $request) 
    {   
        $userId=$request->userId;
        $value=$request->value;
        $comment=$request->comment;
        if(!isset($userId) || $userId == "")            
        {               
            $return = array('result' => 'Please enter user id','status_code'=>204);
            exit(json_encode($return));       
        }
        if(!isset($value) || $value == "")            
        {               
            $return = array('result' => 'Please enter feedback value','status_code'=>204);
            exit(json_encode($return));       
        }
        if(!isset($comment) || $comment == "")            
        {               
            $return = array('result' => 'Please add feedback','status_code'=>204);
            exit(json_encode($return));
        }
        if($value==1){
            $valueText='Not Good';
        }elseif($value==2){
            $valueText='Average';
        }elseif($value==3){
            $valueText='Good';
        }elseif($value==4){
            $valueText='Very Good';
        }elseif($value==5){
            $valueText='Excellent';
        }
        $userDetails = DB::table('users')
                     ->select('users.*','schools.name_school as schoolName','countries.name as contName','states.name as stateName','cities.name as cityName')
                     ->leftjoin('schools', 'schools.id_school', '=', 'users.school')
                     ->leftjoin('countries', 'countries.id', '=', 'users.country')
                     ->leftjoin('states', 'states.id', '=', 'users.state')
                     ->leftjoin('cities', 'cities.id', '=', 'users.city')
                     ->where('users.id', $userId)
                     ->where('users.enabled', 1)
                     ->where('users.user_role', 2)
                     ->first();
        $name=$userDetails->fname." ".$userDetails->lname;
        $school=$userDetails->schoolName;
        if(@$userDetails->email){
            $from=$userDetails->email;
        }else{
            $from='Bookaway27@hotmail.com'; 
        }

        $data = array( 'userName' => $name, 'school' => $school, 'value' => $valueText, 'comment' => $comment, 'from' => $from, 'from_name' => $name );
        Mail::send( 'email.feedback', $data, function( $message ) use ($data)
        {
            $message->to( 'Bookaway27@hotmail.com' )->from( $data['from'], $data['from_name'] )->subject( 'Bookaway Feedback!' );
        });
        $return = array('result' => 'Your Feedback submitted successfully','status_code'=>200);
        exit(json_encode($return));
    }





    public function forgot_password(Request $request) 
    {   
        $email=$request->email;
        if(!isset($email) || $email == "")            
        {               
            $return = array('result' => 'Please enter your email','status_code'=>204);
            exit(json_encode($return));       
        }
        $userDetails = DB::table('users')
                     ->where('email', $email)
                     ->where('user_role', 2)
                     ->first();

        if(count($userDetails)>0){
            if($userDetails->enabled==0){
                $return = array('result' => 'Sorry, Your account is inactive','status_code'=>204);
                exit(json_encode($return));
            }
            else{
                $userId=$userDetails->id;
                $name=$userDetails->fname."/".$userDetails->lname;
                $token = $this->getToken();
                $tokenData = array(
                  'user_id' => $userId,
                  'token' => $token,
                );
                $inserttoken = DB::table('user_forgot')->insert($tokenData);
                $link = route('user.forgot', $token);

                $data = array( 'email' => $email, 'message_content' => $link, 'firstname' => $name, 'from' => 'Bookaway27@hotmail.com', 'from_name' => 'Bookaway' );
                Mail::send( 'email.forgot', $data, function( $message ) use ($data)
                {
                    $message->to( $data['email'] )->from( $data['from'], $data['from_name'] )->subject( 'Welcome!' );
                });
                
                $return = array('result' => 'Please check your email to reset your password','status_code'=>200);
                exit(json_encode($return));
            }
        }
        else{
            $return = array('result' => 'There is no user in Bookaway with this email address','status_code'=>204);
            exit(json_encode($return));  
        }

        
    }


    public function forgotForm($token) 
    {
        $result = DB::table('user_forgot')->where('token',$token)->first();
        $count=count($result);
        if($count>0)
        {
            $userId=$result->user_id;
            return view('fgot.resetPassword', [
              'userId' => $userId,
              'token' => $token,
            ]);
        }
        else {
            $message="Your token is expired, please send a request again.";
            return view('fgot.failForgot', [
              'message' => $message
            ]);
        }
    }



    public function saveResetPass(Request $request){
        $user = Auth::user();
        $this->validate(
            $request, 
            [
                'idUser' => 'required',
                'tokenUser' => 'required',
                'password' => 'required',
            ],
            [
                'idUser.required' => 'Please add user id',
                'tokenUser.required' => 'Please add token',
                'password.required' => 'Please add your password',
            ]
        );
        $userId=$request->idUser;
        $token=$request->tokenUser;
        $password=$request->password;
        $newPass = array(
          'password' => bcrypt($password),
        );
        $savPass = DB::table('users')->where('id', $userId)->update($newPass);
        $savPass = DB::table('user_forgot')->where('user_id', $userId)->delete();
        $savPass = DB::table('user_forgot')->where('token', $token)->delete();
        Session::flash('updatePassSuccess', 'Subject added successfully.'); 
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('api/successForgot');
    }


    public function successForgot(){
        return view('fgot.successForgot');
    }


    //-scls
    public function delete_user(Request $request)
    {
    	$userId=$request->userId;
        if(!isset($userId) || $userId == "")            
        {               
            $return = array('result' => 'Please enter user id','status_code'=>204);
            exit(json_encode($return));       
        }
    	$delUser = DB::table('users')
                 ->where('id', $userId)
                 ->where('user_role', 2)
                 ->delete();
        if($delUser>0){
	        $return = array('result' => 'User deleted successfully','status_code'=>200);
	        exit(json_encode($return));
        }else{
            $return = array('result' => 'Please try again','status_code'=>204);
            exit(json_encode($return));
        }
    }


    public function payForm($id, $name) 
    {
        return view('paypal.payform', [
          'id' => $id,
          'name' => $name
        ]);
    }


    public function listener($id, $name) 
    {
        // CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory.
        // Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
        // Set this to 0 once you go live or don't require logging.
        // define("DEBUG", 1);
        // // Set to 0 once you're ready to go live
        // define("USE_SANDBOX", 1);
        // define("LOG_FILE", "ipn.log");
        // // Read POST data
        // // reading posted data directly from $_POST causes serialization
        // // issues with array data in POST. Reading raw POST data from input stream instead.
        // $raw_post_data = file_get_contents('php://input');
        // $raw_post_array = explode('&', $raw_post_data);
        // $myPost = array();
        // foreach ($raw_post_array as $keyval) {
        //     $keyval = explode ('=', $keyval);
        //     if (count($keyval) == 2)
        //         $myPost[$keyval[0]] = urldecode($keyval[1]);
        // }
        // // read the post from PayPal system and add 'cmd'
        // $req = 'cmd=_notify-validate';
        // if(function_exists('get_magic_quotes_gpc')) {
        //     $get_magic_quotes_exists = true;
        // }
        // foreach ($myPost as $key => $value) {
        //     if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
        //         $value = urlencode(stripslashes($value));
        //     } else {
        //         $value = urlencode($value);
        //     }
        //     $req .= "&$key=$value";
        // }
        // // Post IPN data back to PayPal to validate the IPN data is genuine
        // // Without this step anyone can fake IPN data
        // if(USE_SANDBOX == true) {
        //     $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
        // } else {
        //     $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
        // }
        // $ch = curl_init($paypal_url);
        // if ($ch == FALSE) {
        //     return FALSE;
        // }
        // curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        // curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        // if(DEBUG == true) {
        //     curl_setopt($ch, CURLOPT_HEADER, 1);
        //     curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
        // }
        // // CONFIG: Optional proxy configuration
        // //curl_setopt($ch, CURLOPT_PROXY, $proxy);
        // //curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
        // // Set TCP timeout to 30 seconds
        // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
        // // CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
        // // of the certificate as shown below. Ensure the file is readable by the webserver.
        // // This is mandatory for some environments.
        // //$cert = __DIR__ . "./cacert.pem";
        // //curl_setopt($ch, CURLOPT_CAINFO, $cert);
        // $res = curl_exec($ch);
        // if (curl_errno($ch) != 0) // cURL error
        //     {
        //     if(DEBUG == true) { 
        //         error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
        //     }
        //     curl_close($ch);
        //     exit;
        // } else {
        //         // Log the entire HTTP response if debug is switched on.
        //         if(DEBUG == true) {
        //             error_log(date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
        //             error_log(date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);
        //         }
        //         curl_close($ch);
        // }
        // // Inspect IPN validation result and act accordingly
        // // Split response headers and payload, a better way for strcmp
        // $tokens = explode("\r\n\r\n", trim($res));
        // $res = trim(end($tokens));
        // if (strcmp ($res, "VERIFIED") == 0) {
        //     // assign posted variables to local variables
        //     $item_name = $_POST['item_name'];
        //     $item_number = $_POST['item_number'];
        //     $payment_status = $_POST['payment_status'];
        //     $payment_amount = $_POST['mc_gross'];
        //     $payment_currency = $_POST['mc_currency'];
        //     $txn_id = $_POST['txn_id'];
        //     $receiver_email = $_POST['receiver_email'];
        //     $payer_email = $_POST['payer_email'];
            
        //     include("dbcontroller.php");
        //     $db = new DBController();
            
        //     // check whether the payment_status is Completed
        //     $isPaymentCompleted = false;
        //     if($payment_status == "Completed") {
        //         $isPaymentCompleted = true;
        //     }
        //     // check that txn_id has not been previously processed
        //     $isUniqueTxnId = false; 
        //     $result = $db->selectQuery("SELECT * FROM payments WHERE txn_id = '$txn_id'");
        //     if(empty($result)) {
        //         $isUniqueTxnId = true;
        //     }   
        //     // check that receiver_email is your PayPal email
        //     // check that payment_amount/payment_currency are correct
        //     if($isPaymentCompleted && $isUniqueTxnId && $payment_amount == "0.01" && $payment_currency == "USD") {
        //         $payment_id = $db->insertQuery("INSERT INTO payment(item_number, item_name, payment_status, payment_amount, payment_currency, txn_id) VALUES('$item_number', '$item_name', $payment_status, '$payment_amount', '$payment_currency', '$txn_id')");
        //     }
        //     // process payment and mark item as paid.
            
            
        //     if(DEBUG == true) {
        //         error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
        //     }
            
        // } else if (strcmp ($res, "INVALID") == 0) {
        //     // log for manual investigation
        //     // Add business logic here which deals with invalid IPN messages
        //     if(DEBUG == true) {
        //         error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
        //     }
        // }
    }




    public function report_book(Request $request)
    {
        $userid= $request->userid;
        $bookid= $request->bookid;
        if(@$request->comment){
        $comment = $request->comment;
    	}else{
    	$comment = "";
    	}

        $id_reason = $request->id_reason;

        if(!isset($userid) || $userid == "")            
        {               
            $return = array('result' => 'Please enter the userid','status_code'=>204);
            exit(json_encode($return));       
        
        }
        if(!isset($bookid) || $bookid == "")            
        {               
            $return = array('result' => 'Please add bookid','status_code'=>204);
            exit(json_encode($return));       
        
        }
        $checkRep = DB::table('book_spam')->where('id_user',$userid)->where('id_book',$bookid)->first();
        if(@$checkRep){
            $return = array('result' => 'You have already reported this book!','status_code'=>204);
            exit(json_encode($return)); 
        }else{
           
            $datas = array(
              'id_user' => $userid,
              'id_book' => $bookid,
              'comment' => $comment,
              'id_reason' => $id_reason,
            );
            $insert = DB::table('book_spam')->insert($datas);
            if($insert>0){
            	$userDetails = DB::table('users')
                     ->select('users.*','schools.name_school as schoolName')
                     ->join('schools', 'schools.id_school', '=', 'users.school')
                     ->where('users.id', $userid)
                     ->where('users.enabled', 1)
                     ->where('users.user_role', 2)
                     ->where('schools.status_school', 1)
                     ->first();
                $reasons = DB::table('report_reason')->where('id_reason', $id_reason)->first();
                $bookData = DB::table('books')->where('id_book', $bookid)->first();
            	$data = array( 'userName' => $userDetails->fname.' '.$userDetails->lname, 'school' => $userDetails->schoolName, 'value' => $reasons->title_reason, 'comment' => $comment, 'bookname' => $bookData->book_name, 'from' => 'Bookaway27@hotmail.com', 'from_name' => $userDetails->fname.' '.$userDetails->lname );
		        Mail::send( 'email.report', $data, function( $message ) use ($data)
		        {
		            $message->to( 'Bookaway27@gmail.com' )->from( $data['from'], $data['from_name'] )->subject( 'Report from Bookaway' );
		        });
                $return = array('result' => 'Book report is added successfully','status_code'=>200);
                exit(json_encode($return));
            }else{
                $return = array('result' => 'Please try again','status_code'=>204);
                exit(json_encode($return)); 
            }
        }
    }


    public function report_reasons()
    {   
        $reasons = DB::table('report_reason')
                     ->where('status_reason', 1)
                     ->get();
        if((@$reasons)&&(count($reasons)>0)){
            foreach($reasons as $data)
            {
                $res['id_reason'] = $data->id_reason;
                $res['titie_reason'] = $data->title_reason;
                $return_array[] = $res;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }else{
            $return = array('result' => 'No data found','status_code'=>204);
            exit(json_encode($return));
        }
    }



}
