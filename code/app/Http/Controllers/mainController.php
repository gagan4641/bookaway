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



class mainController extends Controller

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
    public function userList(){
        $user = Auth::user();
        $usersList = DB::table('users')
                     ->select('users.*','schools.name_school as schoolName','countries.name as contName','states.name as stateName','cities.name as cityName')
                     ->leftjoin('schools', 'schools.id_school', '=', 'users.school')
                     ->leftjoin('countries', 'countries.id', '=', 'users.country')
                     ->leftjoin('states', 'states.id', '=', 'users.state')
                     ->leftjoin('cities', 'cities.id', '=', 'users.city')
                     ->where('users.user_role', 2)
                     ->get();
        return view('home', [
          'usersList' => $usersList,
        ]);

    }

    public function viewUser($id){
        $user = Auth::user();
        $usersDetail = DB::table('users')
                     ->select('users.*','schools.name_school as schoolName','countries.name as contName','states.name as stateName','cities.name as cityName')
                     ->leftjoin('schools', 'schools.id_school', '=', 'users.school')
                     ->leftjoin('countries', 'countries.id', '=', 'users.country')
                     ->leftjoin('states', 'states.id', '=', 'users.state')
                     ->leftjoin('cities', 'cities.id', '=', 'users.city')
                     ->where('users.id', $id)
                     ->where('users.user_role', 2)
                     ->first();
        if(count($usersDetail)>0){
            return view('viewUser', [
              'usersDetail' => $usersDetail,
            ]);
        }
    }

    public function enDsUser($id){
        $user = Auth::user();
        $editStatus = DB::table('users')
                    ->select('enabled')
                    ->where('id', $id)
                    ->where('user_role', 2)
                    ->first();
        if($editStatus->enabled==1){
            $newStatus=0;
        }elseif($editStatus->enabled==0) {
            $newStatus=1;
        }
        $editUserStatus = array(
            'enabled' => $newStatus,
        );
        $upUserStatus = DB::table('users')
                         ->where('id', $id)
                         ->update($editUserStatus);
        \Session::flash('upUserSucc', 'User status updated successfully. ');
        return Redirect::to('home');
    }

    public function schools(){
        $user = Auth::user();
        $schools = DB::table('schools')
                 ->select('schools.*','countries.name as contName','states.name as stateName','cities.name as cityName')
                 ->leftjoin('countries', 'countries.id', '=', 'schools.country_school')
                 ->leftjoin('states', 'states.id', '=', 'schools.state_school')
                 ->leftjoin('cities', 'cities.id', '=', 'schools.city_school')
                 ->get();
        return view('schools', [
          'schools' => $schools,
        ]);

    }

    public function enDsSchool($id){
        $user = Auth::user();
        $editStatus = DB::table('schools')
                    ->select('status_school')
                    ->where('id_school', $id)
                    ->first();
        if($editStatus->status_school==1){
            $newStatus=0;
        }elseif($editStatus->status_school==0) {
            $newStatus=1;
        }
        $editSchoolStatus = array(
            'status_school' => $newStatus,
        );
        $upSchoolStatus = DB::table('schools')
                         ->where('id_school', $id)
                         ->update($editSchoolStatus);
        \Session::flash('updateSchoolSuccess', 'School status updated successfully. ');
        return Redirect::to('schools');
    }

    public function delSchool($id){
        $user = Auth::user();
        $delSchool=DB::table('schools')
                   ->where('id_school', $id)
                   ->delete();
        if($delSchool) {
            Session::put('updateSchoolSuccess', 'School Deleted Successfully.');
            return Redirect::to('schools');
        }
        else{
            Session::put('updateSchoolSuccess', 'Please try again.');
            return Redirect::to('schools');
        }
    }


    public function editSchool($id){
        $user = Auth::user();
        $schoolDetails = DB::table('schools')
                 ->select('schools.*','countries.name as contName','states.name as stateName','cities.name as cityName')
                 ->leftjoin('countries', 'countries.id', '=', 'schools.country_school')
                 ->leftjoin('states', 'states.id', '=', 'schools.state_school')
                 ->leftjoin('cities', 'cities.id', '=', 'schools.city_school')
                 ->where('id_school', $id)
                 ->first();
        $countryList = DB::table('countries')->get();
        $stateList=array();
        $cityList=array();
        if(@$schoolDetails->country_school){
          $stateList = DB::table('states')
                       ->where('country_id', $schoolDetails->country_school)
                       ->get();  
        }
        if(@$schoolDetails->state_school){
            $cityList = DB::table('cities')
                       ->where('state_id', $schoolDetails->state_school)
                       ->get();
        }
        if($schoolDetails!==""){
            return view('editSchool', [
              'schoolDetails' => $schoolDetails,
              'countryList' => $countryList,
              'stateList' => $stateList,
              'cityList' => $cityList,
            ]);
        }  
    }

    public function getstates(Request $request)
    {      
        $user = Auth::user();
        $contId=$request->countryIdAjax;
        $stateList = DB::table('states')
                   ->where('country_id', $contId)
                   ->get();
        if($request->ajax()){
            return response()->json([
            'statesList' => $stateList
            ]);
        }
    }

    public function getcities(Request $request)
    {   
        $user = Auth::user();
        $stateId=$request->stateIdAjax;
        $cityList = DB::table('cities')
                  ->where('state_id', $stateId)
                  ->get();
        if($request->ajax()){
            return response()->json([
            'citiesList' => $cityList
            ]);
        }
    }



    public function assignAjaxSubject(Request $request)
    {   
        $user = Auth::user();
        $subid=$request->subidAjax;
        $schoolid=$request->schoolidAjax;
        $checked=$request->checkedAjax;
        $pass=0;
        if($checked==1){
          $chkSub = DB::table('school_subjects')
                        ->where('id_school', $schoolid)
                        ->where('id_subject', $subid)
                        ->first();

          if((@$chkSub)&&(count($chkSub)>0)){ $pass=1; }else{
            $assign = array(
                'id_school' => $schoolid,
                'id_subject' => $subid,
                'status' => 1,
            );
            $saveAssign = DB::table('school_subjects')->insert($assign);
            if($saveAssign>0){
              $pass=2;
            }
          }
        }elseif($checked==0){
          $delSchoolSub = DB::table('school_subjects')
                        ->where('id_school', $schoolid)
                        ->where('id_subject', $subid)
                        ->delete();
          $pass=3;
        }
        if($request->ajax()){
            return response()->json([
              'pass' => $pass
            ]);
        }
    }



    public function updateSchool(Request $request){
        $user = Auth::user();
        $this->validate(
            $request, 
            [
                'schoolNameEdit' => 'required',
                'addressEdit' => 'required',
            ],
            [
                'schoolNameEdit.required' => 'Please add ashool name',
                'addressEdit.required' => 'Please add school address',
            ]
        );
        $schoolId=$request->id_school;
        $name=$request->schoolNameEdit;
        $address=$request->addressEdit;
        $country=(isset($request->countryEdit))? $request->countryEdit : "0";
        $state=(isset($request->stateEdit))? $request->stateEdit : "0";
        $city=(isset($request->cityEdit))? $request->cityEdit : "0";
        $upSchool = array(
            'name_school' => $name,
            'country_school' => $country,
            'state_school' => $state,
            'city_school' => $city,
            'address_school' => $address,
        );
        $editSchool = DB::table('schools')
                     ->where('id_school', $schoolId)
                     ->update($upSchool);
        if($editSchool==1){
            Session::flash('updateSchoolSuccess', 'School Updated successfully.'); 
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('schools');
        }
        else {
            Session::flash('upSchoolErr', 'Please add some changes to update and try again.'); 
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to('editSchool/'.$schoolId);
        }
    }


    public function addSchool(){
        $user = Auth::user();
        $countryList = DB::table('countries')->get();
        return view('addSchool', [
          'countryList' => $countryList
        ]);
    }


    public function saveSchool(Request $request){
        $user = Auth::user();
        $this->validate(
            $request, 
            [
                'schoolName' => 'required',
                'address' => 'required',
            ],
            [
                'schoolName.required' => 'Please add ashool name',
                'address.required' => 'Please add school address',
            ]
        );
        $name=$request->schoolName;
        $address=$request->address;
        $country=(isset($request->country))? $request->country : "0";
        $state=(isset($request->state))? $request->state : "0";
        $city=(isset($request->city))? $request->city : "0";
        $addSchool = array(
            'name_school' => $name,
            'country_school' => $country,
            'state_school' => $state,
            'city_school' => $city,
            'address_school' => $address,
            'status_school' => 1,
        );
        $saveSchool = DB::table('schools')->insert($addSchool);
        if($saveSchool>0){
            Session::flash('updateSchoolSuccess', 'School added successfully.');
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('schools');
        }
        else {
            Session::flash('addSchoolErr', 'Please try again.');
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to('addSchool');
        }
    }

    public function subjects(){ 
        $user = Auth::user();
        $subjects = DB::table('subjects')->get();
        return view('subjects', [
          'subjects' => $subjects,
        ]);

    }

    public function enDsSubject($id){
        $user = Auth::user();
        $editStatus = DB::table('subjects')
                    ->select('status_subject')
                    ->where('id_subject', $id)
                    ->first();
        if($editStatus->status_subject==1){
            $newStatus=0;
        }elseif($editStatus->status_subject==0) {
            $newStatus=1;
        }
        $editSubStatus = array(
            'status_subject' => $newStatus,
        );
        $upSubStatus = DB::table('subjects')
                         ->where('id_subject', $id)
                         ->update($editSubStatus);
        \Session::flash('updateSubjectSuccess', 'Subject status updated successfully. ');
        return Redirect::to('subjects');
    }


    public function delSubject($id){
        $user = Auth::user();
        $delSubject=DB::table('subjects')
                   ->where('id_subject', $id)
                   ->delete();
        if($delSubject!=="") {
            Session::put('updateSubjectSuccess', 'Subject Deleted Successfully.');
            return Redirect::to('subjects');
        }
        else{
            Session::put('updateSubjectSuccess', 'Please try again.');
            return Redirect::to('subjects');
        }
    }

    public function editSubject($id){
        $user = Auth::user();
        $editSubject = DB::table('subjects')
                     ->where('id_subject', $id)
                     ->first();
        if($editSubject!==""){
            return view('editSubject', [
              'editSubject' => $editSubject
            ]);
        }  
    }


    public function updateSubject(Request $request){
        $user = Auth::user();
        $this->validate(
            $request, 
            [
                'subjectNameEdit' => 'required'
            ],
            [
                'subjectNameEdit.required' => 'Please add subject name'
            ]
        );
        $subjectId=$request->id_subject;
        $name=$request->subjectNameEdit;
        $upSubject = array(
            'title_subject' => $name,
        );
        $editSubject = DB::table('subjects')
                     ->where('id_subject', $subjectId)
                     ->update($upSubject);
        if($editSubject==1){
            Session::flash('updateSubjectSuccess', 'Subject Updated successfully.'); 
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('subjects');
        }
        else {
            Session::flash('upSubjectErr', 'Please add some changes to update and try again.'); 
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to('editSubject/'.$subjectId);
        }
    }

    public function addSubject(){
        return view('addSubject');
    }


    public function saveSubject(Request $request){
        $user = Auth::user();
        $this->validate(
            $request, 
            [
                'subjectName' => 'required'
            ],
            [
                'subjectName.required' => 'Please add subject name'
            ]
        );
        $name=$request->subjectName;
        $addSubject = array(
            'title_subject' => $name,
            'status_subject' => 1,
        );
        $saveSubject = DB::table('subjects')->insert($addSubject);
        if($saveSubject==1){
            Session::flash('updateSubjectSuccess', 'Subject added successfully.'); 
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('subjects');
        }
        else {
            Session::flash('addSubjectErr', 'Please add some changes to update and try again.'); 
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to('addSubject');
        }
    }


    public function books($schoolId=NULL){
        $user = Auth::user();
        if(@$schoolId){
            $books = DB::table('books')
                     ->select('books.*','schools.name_school as schoolName','subjects.title_subject as subjectName','users.fname as userFname','users.lname as userLname')
                     ->leftjoin('schools', 'schools.id_school', '=', 'books.id_school')
                     ->leftjoin('subjects', 'subjects.id_subject', '=', 'books.id_subject')
                     ->leftjoin('users', 'users.id', '=', 'books.id_user')
                     ->where('books.id_school', $schoolId)
                     ->get();
        }else{
            $books = DB::table('books')
                     ->select('books.*','schools.name_school as schoolName','subjects.title_subject as subjectName','users.fname as userFname','users.lname as userLname')
                     ->leftjoin('schools', 'schools.id_school', '=', 'books.id_school')
                     ->leftjoin('subjects', 'subjects.id_subject', '=', 'books.id_subject')
                     ->leftjoin('users', 'users.id', '=', 'books.id_user')
                     ->get(); 
        }
        $schools = DB::table('schools')
               ->select('name_school','id_school')
               ->get();

        if(@$schoolId){
            $sid=$schoolId;
        }else{
            $sid="";
        }
        return view('books', [
          'books' => $books,
          'schools' => $schools,
          'sid' => $sid,
        ]);
    }


    public function payments(Request $request){
        $user = Auth::user();
        $paymentsList1 = DB::table('payments')
                 ->select('payments.*','schools.name_school as schoolName','books.book_name as bookName','users.fname as userFname','users.lname as userLname')
                 ->leftjoin('users', 'users.id', '=', 'payments.user_id')
                 ->leftjoin('schools', 'schools.id_school', '=', 'users.school')
                 ->leftjoin('books', 'books.id_book', '=', 'payments.book_id');
                 if(@$request->selectSchool){
    				$paymentsList1->where('payments.school_id',$request->selectSchool);
		         }
		         if(@$request->selectSubject){
		        	$paymentsList1->where('payments.subject_id',$request->selectSubject);
		         }
		         if(@$request->selectUser){
		        	$paymentsList1->where('payments.user_id',$request->selectUser);
		         }
		         if(@$request->selectDate){
		        	$paymentsList1->whereDate('payments.created', '=' ,$request->selectDate);
		         }
            $paymentsList=$paymentsList1->orderby('payments.id')->get();
            $schoolsList = DB::table('schools')
            			  ->where('status_school', 1)
                    	  ->get();

			if(@$request->selectSchool){
				$selectedSchool=$request->selectSchool;
                $subjectsList = DB::table('school_subjects')
        		   ->select('school_subjects.*','subjects.title_subject')
                   ->join('subjects', 'subjects.id_subject', '=', 'school_subjects.id_subject')
                   ->where('school_subjects.id_school', $selectedSchool)
                   ->where('school_subjects.status', 1)
                   ->where('subjects.status_subject', 1)
                   ->get();
			}else{
				$selectedSchool="";
				$subjectsList=array();
			}

			if(@$request->selectSubject){
				$selectedSubject=$request->selectSubject;
		        $usersList = DB::table('payments')
		        		   ->select('payments.user_id', 'users.fname', 'users.lname', 'users.id')
		        		   ->join('users', 'users.id', '=', 'payments.user_id')
		                   ->where('payments.school_id', $selectedSchool)
		                   ->where('payments.subject_id', $selectedSubject)
		                   ->groupBy('payments.user_id')
		                   ->get();
			}else{
				$selectedSubject="";
				$usersList="";
			}

			if(@$request->selectUser){
				$selectedUser=$request->selectUser;
			}else{
				$selectedUser="";
			}

			if(@$request->selectDate){
				$selectedDate=$request->selectDate;
			}else{
				$selectedDate="";
			}

        return view('payments', [
          'paymentsList' => $paymentsList,
          'schoolsList' => $schoolsList,
          'selectedSchool' => $selectedSchool,
          'subjectsList' => $subjectsList,
          'subjectsList' => $subjectsList,
          'selectedSubject' => $selectedSubject,
          'usersList' => $usersList,
          'selectedUser' => $selectedUser,
          'selectedDate' => $selectedDate,
        ]);
    }

    public function changeFees(){
        $user = Auth::user();
        $fees = DB::table('add_book_fees')->where('status_fees', 1)->first();
        return view('changeFees', [
          'fees' => $fees
        ]);
    }

    public function updateFees(Request $request){
        $user = Auth::user();
        $this->validate(
            $request, 
            [
                'bookFees' => 'required'
            ],
            [
                'bookFees.required' => 'Please add book fees'
            ]
        );
        $editFees = array(
            'amount_fees' => $request->bookFees,
            'status_fees' => 1,
        );
        $saveFees = DB::table('add_book_fees')->update($editFees);
        Session::flash('updateSubjectSuccess', 'Book fees updated successfully.'); 
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('changeFees');
    }

    public function assignSubjects($sid=NULL){
        $user = Auth::user();
        $subjects = DB::table('subjects')->where('status_subject', 1)->get();
        $schools = DB::table('schools')->where('status_school', 1)->get();
        if(@$sid){
          $school_subjects = DB::table('school_subjects')->where('id_school', $sid)->where('status', 1)->get();
          $sschool=$sid;
        }else{
          $school_subjects = DB::table('school_subjects')->where('id_school', $schools[0]->id_school)->where('status', 1)->get();
          $sschool=$schools[0]->id_school;
        }
        //dd($school_subjects);
        $arr=array();
        foreach($school_subjects as $sSubs){
          $arr[]=$sSubs->id_subject;
        }
        //dd($arr);
        return view('assignSubjects', [
          'subjects' => $subjects,
          'schools' => $schools,
          'school_subjects' => $arr,
          'sschool' => $sschool,
        ]);
    }


    public function getSubjectsAjax(Request $request)
    {      
        $user = Auth::user();
        $schoolId=$request->schoolIdAjax;
        $subjectsList = DB::table('school_subjects')
        		   ->select('school_subjects.*','subjects.title_subject')
                   ->join('subjects', 'subjects.id_subject', '=', 'school_subjects.id_subject')
                   ->where('school_subjects.id_school', $schoolId)
                   ->where('school_subjects.status', 1)
                   ->where('subjects.status_subject', 1)
                   ->get();
        if($request->ajax()){
            return response()->json([
            'subjectsList' => $subjectsList
            ]);
        }
    }

    public function getUsersAjax(Request $request)
    {      
        $user = Auth::user();
        $schoolId=$request->schoolIdAjax;
        $subjectId=$request->subjectIdAjax;
        $usersList = DB::table('payments')
        		   ->select('payments.user_id', 'users.fname', 'users.lname', 'users.id')
        		   ->join('users', 'users.id', '=', 'payments.user_id')
                   ->where('payments.school_id', $schoolId)
                   ->where('payments.subject_id', $subjectId)
                   ->groupBy('payments.user_id')
                   ->get();
        if($request->ajax()){
            return response()->json([
            'usersList' => $usersList
            ]);
        }
    }

    

    //-- Assign All Subjects
    public function assignAllSubjects(){
      $schools = DB::table('schools')->get();
      foreach($schools as $aschools){
        $schId=$aschools->id_school;
        $subjects = DB::table('subjects')->get();
        foreach($subjects as $aSubs){
          $subId=$aSubs->id_subject;
          $checkData = DB::table('school_subjects')
                        ->where('id_school', $schId)
                        ->where('id_subject', $subId)
                        ->first();
          if((@$checkData)&&(count($checkData)>0)){}else{
            $assign = array(
                'id_school' => $schId,
                'id_subject' => $subId,
                'status' => 1,
            );
            $saveAssign = DB::table('school_subjects')->insert($assign);
          }
        }
      }
      dd('Done');
    }


    //-- Assign Dummy Book In All Schools And Subjects
    public function allSchSubBookAdd(){
      //-- Get All Schools
        $schools = DB::table('schools')->get();
        $xxx=1;
        foreach($schools as $aschools){
          $schId=$aschools->id_school;
          //-- Add Dummy User
            $datasUser = array(
              'fname' => "Dummy",
              'lname' => "User",
              'email' => "dummyUser".$xxx."@bookaway.com",
              'password' => bcrypt(12345678),
              'cpassword' => 12345678,
              'school' => $schId,
              'imagesUser' => "placeholder.png",
              'address' => "Bookaway User",
              'enabled' => 1,
              'user_role' => 2,
            );
            $insertUser = DB::table('users')->insert($datasUser);
            $addedUserId = DB::getPdo()->lastInsertId();
          //-- Add Dummy User

          //-- Get All Subjects
            $subjects = DB::table('subjects')->get();
            foreach($subjects as $aSubs){
              $subId=$aSubs->id_subject;
              $description="For what class did you use this book ?";
              //-- Add Dummy Book
                $datasBooks = array(
                  'id_school' => $schId,
                  'id_subject' => $subId,
                  'id_user' => $addedUserId,
                  'book_name' => "Sample Book Name",
                  'book_image' => "placeholder.png",
                  'book_author' => "Sample Author Name",
                  'book_price' => 1,
                  'book_description' => $description,
                  'book_condition' => 1,
                  'book_status' => 1,
                );
                $insertBook = DB::table('books')->insert($datasBooks);
              //-- Add Dummy Book
            }
          //-- Get All Subjects
        $xxx++;
        }
      //-- Get All Schools
      dd('Done');
    }


    
    public function blockBook($bid){
        $user = Auth::user();
        $editStatus = DB::table('books')
                    ->select('book_status')
                    ->where('id_book', $bid)
                    ->first();
        if($editStatus->book_status==1){
            $newStatus=0;
        }elseif($editStatus->book_status==0) {
            $newStatus=1;
        }
        $bbook = array(
            'book_status' => $newStatus,
        );
        $blockBook = DB::table('books')
                         ->where('id_book', $bid)
                         ->update($bbook);
        if($newStatus==0){
          Session::flash('updateBookSuccess', 'Book blocked successfully. ');
          Session::flash('alert-class', 'alert-success');
        }else{
          Session::flash('updateBookSuccess', 'Book unblocked successfully. ');
          Session::flash('alert-class', 'alert-success');
        }
        return Redirect::to('books');
    }



    
    public function viewBook($bid){
        $user = Auth::user();
        $bookDetail = DB::table('books')
                 ->select('books.*','schools.name_school as schoolName','subjects.title_subject as subjectName','users.fname as userFname','users.lname as userLname')
                 ->leftjoin('schools', 'schools.id_school', '=', 'books.id_school')
                 ->leftjoin('subjects', 'subjects.id_subject', '=', 'books.id_subject')
                 ->leftjoin('users', 'users.id', '=', 'books.id_user')
                 ->where('books.id_book', $bid)
                 ->first();
      
        $repUsers = DB::table('book_spam')
               ->select('book_spam.*','users.fname','users.lname','users.email','schools.name_school')
               ->leftjoin('users', 'users.id', '=', 'book_spam.id_user')
               ->leftjoin('schools', 'schools.id_school', '=', 'users.school')
               ->where('book_spam.id_book', $bid)
               ->get();

        return view('bookDetail', [
          'bookDetail' => $bookDetail,
          'repUsers' => $repUsers
        ]);
    }

    public function termsConditions(){
        $user = Auth::user();      
        $termsConditions = DB::table('terms')
               ->where('status', 1)
               ->first();
        return view('termsConditions', [
          'termsConditions' => $termsConditions
        ]);
    }


    


    public function saveTermCondition(Request $request){
        $user = Auth::user();
        $this->validate(
            $request, 
            [
                'termTitle' => 'required',
                'termContent' => 'required',
            ],
            [
                'termTitle.required' => 'Please add title',
                'termContent.required' => 'Please add content',
            ]
        );
        $title=$request->termTitle;
        $text=$request->termContent;
        $id=$request->termId;

        $upTerm = array(
            'title' => $title,
            'content' => $text
        );
        $editTerm = DB::table('terms')
                     ->where('id', $id)
                     ->update($upTerm);
            Session::flash('updateTermSuccess', 'Terms and Conditions Updated successfully.'); 
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('termsConditions');
    }


}