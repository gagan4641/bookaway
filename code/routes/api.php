<?php



use Illuminate\Http\Request;



/*

|--------------------------------------------------------------------------

| API Routes

|--------------------------------------------------------------------------

|

| Here is where you can register API routes for your application. These

| routes are loaded by the RouteServiceProvider within a group which

| is assigned the "api" middleware group. Enjoy building your API!

|

*/





Route::post('/registration', 'apiController@registration');

Route::get('user/activation/{token}', 'apiController@activateUser')->name('user.activate');

Route::get('user/forgot/{token}', 'apiController@forgotForm')->name('user.forgot');

Route::get('/schools', 'apiController@schools');

Route::get('/all_schools', 'apiController@all_schools');

Route::post('/subjects', 'apiController@subjects');

Route::post('/all_subjects', 'apiController@all_subjects');

Route::post('/books', 'apiController@books');

Route::post('/myProfile', 'apiController@myProfile');

Route::post('/addBook', 'apiController@addBook');

Route::post('/book_detail', 'apiController@book_detail');

Route::post('/login', 'apiController@login');

Route::post('/socialLogin', 'apiController@socialLogin');

Route::post('/mybooks', 'apiController@mybooks');

Route::post('/update_profile', 'apiController@update_profile');

Route::post('/change_password', 'apiController@change_password');

Route::get('/book_conditions', 'apiController@book_conditions');

Route::post('/feedback', 'apiController@feedback');

Route::post('/forgot_password', 'apiController@forgot_password');

Route::post('/saveResetPass', 'apiController@saveResetPass');

Route::get('/successForgot', 'apiController@successForgot');

Route::post('/delete_user', 'apiController@delete_user');

Route::get('/payForm/{id}/{name}', 'apiController@payForm');

Route::get('/listener', 'apiController@listener');

Route::post('/report_book', 'apiController@report_book');

Route::get('/report_reasons', 'apiController@report_reasons');





// // route for view/blade file

// Route::get('paywithpaypal', array('as' => 'paywithpaypal','uses' => 'PaypalController@payWithPaypal',));

// // route for post request

// Route::post('paypal', array('as' => 'paypal','uses' => 'PaypalController@postPaymentWithpaypal',));

// // route for check status responce

// Route::get('paypal', array('as' => 'status','uses' => 'PaypalController@getPaymentStatus',));







Route::middleware('auth:api')->get('/user', function (Request $request) {

    return $request->user();

});









