<?php

namespace App\Http\Controllers;



use App\Http\Requests;

use Illuminate\Http\Request;

use Validator;

use URL;

use Session;

use Redirect;

//use Input;



/** All Paypal Details class **/

use PayPal\Rest\ApiContext;

use PayPal\Auth\OAuthTokenCredential;

use PayPal\Api\Amount;

use PayPal\Api\Details;

use PayPal\Api\Item;

use PayPal\Api\ItemList;

use PayPal\Api\Payer;

use PayPal\Api\Payment;

use PayPal\Api\RedirectUrls;

use PayPal\Api\ExecutePayment;

use PayPal\Api\PaymentExecution;

use PayPal\Api\Transaction;

use Illuminate\Support\Facades\Input;

use DB;



class PaypalController extends Controller

{

    private $_api_context;

    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function __construct()

    {

        //parent::__construct();

        

        /** setup PayPal api context **/

        $paypal_conf = \Config::get('paypal');

        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));

        $this->_api_context->setConfig($paypal_conf['settings']);

    }



    /**

     * Show the application paywith paypalpage.

     *

     * @return \Illuminate\Http\Response

     */

    public function payWithPaypal($schid,$subId,$uid,$bname,$bpic,$bauth,$bprice,$bdes,$bcon,$fees)

    {

        $schid_dec=bookaway_decrypt($schid);

        $subId_dec=bookaway_decrypt($subId);

        $uid_dec=bookaway_decrypt($uid);

        $bname_dec=bookaway_decrypt($bname);

        $bpic_dec=bookaway_decrypt($bpic);

        $bauth_dec=bookaway_decrypt($bauth);

        $bprice_dec=bookaway_decrypt($bprice);

        $bdes_dec=bookaway_decrypt($bdes);

        $bcon_dec=bookaway_decrypt($bcon);

        $fees_dec=bookaway_decrypt($fees);

        return view('paywithpaypal', [

          'schid' => $schid_dec,

          'subId' => $subId_dec,

          'uid' => $uid_dec,

          'bname' => $bname_dec,

          'bpic' => $bpic_dec,

          'bauth' => $bauth_dec,

          'bprice' => $bprice_dec,

          'bdes' => $bdes_dec,

          'bcon' => $bcon_dec,

          'fees' => $fees_dec,

        ]);

    }



    /**

     * Store a details of payment with paypal.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function postPaymentWithpaypal(Request $request)

    {

        $fees=$request->get('fees');

        $bprice=$request->get('bprice');

        $bookName=$request->get('bookName');

        $userid=$request->get('buserid');

        $bschool=$request->get('bschool');

        $bsubject=$request->get('bsubject');

        $bpic=$request->get('bpic');

        $bauth=$request->get('bauth');

        $bdescription=$request->get('bdescription');

        $bcondition=$request->get('bcondition');



        $payer = new Payer();

        $payer->setPaymentMethod('paypal');



        $item_1 = new Item();



        $item_1->setName($bookName) /** item name **/

            ->setCurrency('USD')

            ->setQuantity(1)

            ->setPrice($fees); /** unit price **/



        $item_list = new ItemList();

        $item_list->setItems(array($item_1));



        $amount = new Amount();

        $amount->setCurrency('USD')

            ->setTotal($fees);



        $transaction = new Transaction();

        $transaction->setAmount($amount)

            ->setItemList($item_list)

            ->setDescription('Your transaction description');



        $redirect_urls = new RedirectUrls();

        $redirect_urls->setReturnUrl(URL::route('status')) /** Specify return URL **/

            ->setCancelUrl(URL::route('status'));



        $payment = new Payment();

        $payment->setIntent('Sale')

            ->setPayer($payer)

            ->setRedirectUrls($redirect_urls)

            ->setTransactions(array($transaction));

            /** dd($payment->create($this->_api_context));exit; **/

        try {

            $payment->create($this->_api_context);

        } catch (\PayPal\Exception\PPConnectionException $ex) {

            if (\Config::get('app.debug')) {

                \Session::put('error','Connection timeout');

                //return Redirect::route('paywithpaypal');

                return Redirect::to('paymentFail');

                /** echo "Exception: " . $ex->getMessage() . PHP_EOL; **/

                /** $err_data = json_decode($ex->getData(), true); **/

                /** exit; **/

            } else {

                \Session::put('error','Some error occur, sorry for inconvenient');

                //return Redirect::route('paywithpaypal');

                return Redirect::to('paymentFail');

                /** die('Some error occur, sorry for inconvenient'); **/

            }

        }



        foreach($payment->getLinks() as $link) {

            if($link->getRel() == 'approval_url') {

                $redirect_url = $link->getHref();

                break;

            }

        }

        /** add payment ID to session **/

        Session::put('paypal_payment_id', $payment->getId());

        Session::put('sess_fees', $fees);

        Session::put('sess_bprice', $bprice);

        Session::put('sess_bookName', $bookName);

        Session::put('sess_userid', $userid);

        Session::put('sess_bschool', $bschool);

        Session::put('sess_bsubject', $bsubject);

        Session::put('sess_bpic', $bpic);

        Session::put('sess_bauth', $bauth);

        Session::put('sess_bdescription', $bdescription);

        Session::put('sess_bcondition', $bcondition);

        if(isset($redirect_url)) {

            /** redirect to paypal **/

            return Redirect::away($redirect_url);

        }



        \Session::put('error','Unknown error occurred');

        //return Redirect::route('paywithpaypal');

        return Redirect::to('paymentFail');

    }



    public function getPaymentStatus()

    {

        /** Get the payment ID before session clear **/

        $payment_id = Session::get('paypal_payment_id');

        /** clear the session payment ID **/

        Session::forget('paypal_payment_id');

        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {

            \Session::put('error','Payment failed');

            //return Redirect::route('paywithpaypal');

            return Redirect::to('paymentFail');

        }

        $payment = Payment::get($payment_id, $this->_api_context);

        /** PaymentExecution object includes information necessary **/

        /** to execute a PayPal account payment. **/

        /** The payer_id is added to the request query parameters **/

        /** when the user is redirected from paypal back to your site **/

        $execution = new PaymentExecution();

        $execution->setPayerId(Input::get('PayerID'));

        /**Execute the payment **/

        $result = $payment->execute($execution, $this->_api_context);

        /** dd($result);exit; /** DEBUG RESULT, remove it later **/

        if ($result->getState() == 'approved') {

            $sess_fees=Session::get('sess_fees');

            $sess_bprice=Session::get('sess_bprice');

            $sess_bookName=Session::get('sess_bookName');

            $sess_userid=Session::get('sess_userid');

            $sess_bschool=Session::get('sess_bschool');

            $sess_bsubject=Session::get('sess_bsubject');

            $sess_bpic=Session::get('sess_bpic');

            $sess_bauth=Session::get('sess_bauth');

            $sess_bdescription=Session::get('sess_bdescription');

            $sess_bcondition=Session::get('sess_bcondition');

            $datas = array(

                'id_school' => $sess_bschool,

                'id_subject' => $sess_bsubject,

                'id_user' => $sess_userid,

                'book_name' => $sess_bookName,

                'book_image' => $sess_bpic,

                'book_author' => $sess_bauth,

                'book_price' => $sess_bprice,

                'book_description' => $sess_bdescription,

                'book_condition' => $sess_bcondition,

                'book_status' => 1,

            );

            $insertBook = DB::table('books')->insert($datas);

            if($insertBook>0){

                $bookId = DB::getPdo()->lastInsertId();

                $res=json_decode($result);

                $parentId=$res->id;

                $payerId=$res->payer->payer_info->payer_id;

                $saleid=$res->transactions[0]->related_resources[0]->sale->id;

                $merchantId=$res->transactions[0]->payee->merchant_id;

                $userid=Session::get('sess_userid');

                $parentState=$res->state;

                $payerStatus=$res->payer->status;

                $payerFname=$res->payer->payer_info->first_name;

                $payerLname=$res->payer->payer_info->last_name;

                $saleTotal=$res->transactions[0]->related_resources[0]->sale->amount->total;

                $merchant_email=$res->transactions[0]->payee->email;

                $itmName=$res->transactions[0]->item_list->items[0]->name;

                $itmPrice=$res->transactions[0]->item_list->items[0]->price;

                $itmCurrency=$res->transactions[0]->item_list->items[0]->currency;

                $itmQuantity=$res->transactions[0]->item_list->items[0]->quantity;

                $trans_fee=$res->transactions[0]->related_resources[0]->sale->transaction_fee->value;

                $trans_currency=$res->transactions[0]->related_resources[0]->sale->transaction_fee->currency;

                $receavedAmount = $saleTotal - $trans_fee;

                $pay = array(

                    'parent_id' => $parentId,

                    'payer_id' => $payerId,

                    'sale_id' => $saleid,

                    'merchant_id' => $merchantId,

                    'book_id' => $bookId,

                    'user_id' => $userid,

                    'school_id' => $sess_bschool,
                    
                    'subject_id' => $sess_bsubject,

                    'parent_state' => $parentState,

                    'payer_status' => $payerStatus,

                    'payer_fname' => $payerFname,

                    'payer_lname' => $payerLname,

                    'sale_total' => $saleTotal,

                    'merchant_email' => $merchant_email,

                    'item_name' => $itmName,

                    'item_price' => $itmPrice,

                    'itm_currency' => $itmCurrency,

                    'itm_quantity' => $itmQuantity,

                    'trans_fee' => $trans_fee,

                    'trans_currency' => $trans_currency,

                    'receaved_amount' =>$receavedAmount,

                );

                $savePayment = DB::table('payments')->insert($pay);

                if($savePayment>0){

                    \Session::put('success','Payment success');

                    //return Redirect::route('paywithpaypal');

                    return Redirect::to('paymentSuccess');

                }

                else{

                    \Session::put('error','Payment failed');

                    //return Redirect::route('paywithpaypal');

                    return Redirect::to('paymentFail');

                }

            }

            else{

                \Session::put('error','Payment failed');

                //return Redirect::route('paywithpaypal');

                return Redirect::to('paymentFail');

            }

        }

        \Session::put('error','Payment failed');

        //return Redirect::route('paywithpaypal');

        return Redirect::to('paymentFail');

    }



    public function paymentSuccess(){

        return view('paypal.paymentSuccess');

    }



    public function paymentFail(){

        return view('paypal.paymentFail');

    }

    

}