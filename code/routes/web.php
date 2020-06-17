<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/', 'HomeController@index');
Route::any('/logout', 'Auth\LoginController@logout');

//-- My Routes
Route::get('/userList', 'mainController@userList');
Route::get('/viewUser/{id}', 'mainController@viewUser');
Route::get('/enDsUser/{id}', 'mainController@enDsUser');
Route::get('/schools', 'mainController@schools');
Route::get('/addSchool', 'mainController@addSchool');
Route::get('/editSchool/{id}', 'mainController@editSchool');
Route::get('/enDsSchool/{id}', 'mainController@enDsSchool');
Route::get('/delSchool/{id}', 'mainController@delSchool');
Route::post('/updateSchool', 'mainController@updateSchool');
Route::post('/getstates', 'mainController@getstates');
Route::post('/getcities', 'mainController@getcities');
Route::post('/assignAjaxSubject', 'mainController@assignAjaxSubject');
Route::post('/saveSchool', 'mainController@saveSchool');
Route::get('/subjects', 'mainController@subjects');
Route::get('/addSubject', 'mainController@addSubject');
Route::get('/editSubject/{id}', 'mainController@editSubject');
Route::get('/enDsSubject/{id}', 'mainController@enDsSubject');
Route::get('/delSubject/{id}', 'mainController@delSubject');
Route::post('/updateSubject', 'mainController@updateSubject');
Route::post('/saveSubject', 'mainController@saveSubject');
Route::get('/books/{schoolId?}', 'mainController@books');
Route::get('/payments', 'mainController@payments');
Route::get('/changeFees', 'mainController@changeFees');
Route::post('/updateFees', 'mainController@updateFees');
Route::get('/assignSubjects/{sid?}', 'mainController@assignSubjects');
Route::post('/assignSubjectsSave', 'mainController@assignSubjectsSave');
Route::get('paywithpaypal/{schid}/{subId}/{uid}/{bname}/{bpic}/{bauth}/{bprice}/{bdes}/{bcon}/{fees}', array('as' => 'paywithpaypal','uses' => 'PaypalController@payWithPaypal',));
Route::post('paypal', array('as' => 'paypal','uses' => 'PaypalController@postPaymentWithpaypal',));
Route::get('paypal', array('as' => 'status','uses' => 'PaypalController@getPaymentStatus',));
Route::get('/paymentSuccess', 'PaypalController@paymentSuccess');
Route::get('/paymentFail', 'PaypalController@paymentFail');
Route::post('/getSubjectsAjax', 'mainController@getSubjectsAjax');
Route::post('/getUsersAjax', 'mainController@getUsersAjax');
Route::get('/assignAllSubjects', 'mainController@assignAllSubjects');
Route::get('/allSchSubBookAdd', 'mainController@allSchSubBookAdd');
Route::get('/blockBook/{bid}', 'mainController@blockBook');
Route::get('/viewBook/{bid}', 'mainController@viewBook');
Route::get('/termsConditions', 'mainController@termsConditions');
Route::post('/saveTermCondition', 'mainController@saveTermCondition');

Route::get('/terms', 'staticController@terms');