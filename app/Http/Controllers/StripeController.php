<?php

/**
 * Class StripeController
 *
 * @category Worketic
 *
 * @package Worketic
 * @author  Amentotech <theamentotech@gmail.com>
 * @license http://www.amentotech.com Amentotech
 * @version <PHP: 1.0.0>
 * @link    http://www.amentotech.com
 */
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Validator;
use URL;
use Session;
use Redirect;
use Input;
use App\Profile;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Stripe\Error\Card;
use App\Proposal;
use App\Job;
use App\User;
use App\Resources;
use App\Transactions;
use Auth;
use App\Invoice;
use DB;
use App\Package;
use Illuminate\Support\Facades\Mail;
use App\EmailTemplate;
// use App\Mail\studentEmailMailable;
use App\Mail\EmployerEmailMailable;
use App\Helper;
use App\Item;
use Carbon\Carbon;
use App\Message;
use App\Service;
use App\SiteManagement;

/**
 * Class StripeController
 *
 */
class StripeController extends Controller
{
    /**
     * Show the application paywith stripe.
     *
     * @return \Illuminate\Http\Response
     */
    public function payWithStripe()
    {
        if (file_exists(resource_path('views/extend/back-end/paymentstripe.blade.php'))) {
            return view('extend.back-end.paymentstripe');
        } else {
            return view('back-end.paymentstripe');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request req->attr
     *
     * @return \Illuminate\Http\Response
     */
     public function postPaymentWithStripe(Request $request)
    {
       // dd(config('constants.stripe_secret'));
        //dd(env('STRIPE'));
        //print_r($request->all());die;
        Log::info("In stripe controller");
        Log::info($request);
        $settings = SiteManagement::getMetaValue('commision');
        $seller = Profile::where('user_id', $request->seller_id)->first();
        //echo "<pre>";print_r($seller);die;
        Log::info("PROFILE");
        Log::info($seller);
        $currency = 'AUD';
        $current_year = Carbon::now()->format('Y');
        $validator = Validator::make(
            $request->all(),
            [
                'card_no' => 'required',
                'ccExpiryMonth' => 'required',
                'ccExpiryYear' => 'required',
                'cvvNumber' => 'required',
            ]
        );
        if ($request['ccExpiryYear'] < $current_year) {
            // Session::flash('error', trans('lang.valid_year'));
            // return Redirect::back()->withInput();
            $json['type'] = 'error';
            $json['message'] = trans('lang.valid_year');
            return $json;
        }
        if ($seller->payout_settings === "") {
           
            // Session::flash('error', trans('lang.valid_year'));
            // return Redirect::back()->withInput();            $json['type'] = 'error';
            $json['message'] = trans('lang.seller_info_missing');
            return $json;
        }
       
        $product_id = $request->slug;
        $product_title = $request->title;
        $product_price = Resources::select('price')->where('slug', $request->slug)->first();

        //print_r($product_price);die;
        Log::info("AMOUNT !");
        Log::info($product_price);
        $type = Session::has('type') ? session()->get('type') : '';
        $user_id = Auth::user() ? Auth::user()->id : '';
        $input = $request->all();
        if ($validator->passes()) {
            $input = array_except($input, array('_token'));
             if (!empty(config('constants.stripe_secret'))) {
                \Artisan::call('optimize:clear');
                $stripe = Stripe::make(config('constants.stripe_secret'));
                //$stripe=getconfig('constants.stripe_key');
            } else {
                // Session::flash('error', trans('lang.empty_stripe_key'));
                // return Redirect::back();
                $json['type'] = 'error';
                $json['message'] = trans('lang.empty_stripe_key');
                return $json;
            }
            try {
                $token = $stripe->tokens()->create(
                    [
                        'card' => [
                            'number'    => $request->get('card_no'),
                            'exp_month' => $request->get('ccExpiryMonth'),
                            'exp_year'  => $request->get('ccExpiryYear'),
                            'cvc'       => $request->get('cvvNumber'),
                        ],
                    ]
                );
                if (!isset($token['id'])) {
                    // Session::flash('error', 'The Stripe Token was not generated correctly');
                    // return Redirect::back();
                    $json['type'] = 'error';
                    $json['message'] = 'The Stripe Token was not generated correctly';
                    return $json;
                }
                Log::info("I am TOKEN");
                Log::info($token);
                $customer = $stripe->customers()->create(
                    [
                        'email' => Auth::user()->email,
                    ]
                );
                $stripe1 = \Stripe\Stripe::setApiKey("");
                \Stripe\Customer::createSource(
                    $customer['id'],
                    ['source' => $token['id']]
                );
                Log::info("CUSTOMER ID");
                Log::info($customer['id']);
                // $payment_detail1 = $stripe->charges()->create(
                //     [
                //         'customer' => $customer['id'],
                //         'currency' => "AUD",
                //         'amount'   => $product_price,
                //         'description' => trans('lang.add_in_wallet'),
                //     ]
                // );

                // Log::info("payment_detail");
                // Log::info($payment_detail1['id']);
                Log::info($seller->payout_settings);
                $stripe = new \Stripe\StripeClient(
                    ''
                  );

                 

                $payment_detail = \Stripe\PaymentIntent::create([
                        'payment_method_types' => ['card'],
                        "amount" => ($product_price['price']) * 100,
                        "currency" => "aud",
                        'customer' => $customer['id'],
                        'transfer_data' => [
                            'destination' => 'acct_1H7wj6LmdQUNB0w5',
                        ],
                        "application_fee_amount"=> (0.15*($product_price['price'])) * 100,
                    ]
                );
               

                $payment_detail=$stripe->paymentIntents->confirm(
                    $payment_detail['id'],
                    ['payment_method' => 'pm_card_visa']
                  );
                Log::info("COMING ");
                Log::info($payment_detail);
                
                if ($payment_detail['status'] == 'succeeded') {

                    $fee = !empty($payment_detail['application_fee_amount']) ? $payment_detail['application_fee_amount'] : 0;
                    $invoice = new Invoice();
                    $invoice->title = 'Invoice';
                    $invoice->price = $product_price;
                    $invoice->payer_name = filter_var($customer['name'], FILTER_SANITIZE_STRING);
                    $invoice->payer_email = filter_var($customer['email'], FILTER_SANITIZE_EMAIL);
                    $invoice->seller_email = 'test@email.com';
                    $invoice->currency_code = filter_var($payment_detail['currency'], FILTER_SANITIZE_STRING);
                    $invoice->payer_status = '';
                    $invoice->transaction_id = filter_var($payment_detail['id'], FILTER_SANITIZE_STRING);
                    $invoice->invoice_id = filter_var($payment_detail['source']['id'], FILTER_SANITIZE_STRING);
                    $invoice->customer_id = filter_var($customer['id'], FILTER_SANITIZE_STRING);
                    $invoice->shipping_amount = 0;
                    $invoice->handling_amount = 0;
                    $invoice->insurance_amount = 0;
                    $invoice->sales_tax = 0;
                    $invoice->payment_mode = filter_var('stripe', FILTER_SANITIZE_STRING);
                    $invoice->paypal_fee = $fee;
                    $invoice->paid = "PAID";//$payment_detail['paid'];
                    $product_type = $type;
                    $invoice->type = $product_type;
                    $invoice->save();
                    $invoice_id = DB::getPdo()->lastInsertId();
                    if ($type == 'package') {
                        $item = DB::table('items')->select('id')->where('subscriber', $user_id)->first();
                        if (!empty($item)) {
                            $item = Item::find($item->id);
                        } else {
                            $item = new Item();
                        }
                    } else {
                        $item = new Item();
                    }
                    $item->invoice_id = filter_var($invoice_id, FILTER_SANITIZE_NUMBER_INT);
                    $item->product_id = filter_var($product_id, FILTER_SANITIZE_NUMBER_INT);
                    $item->subscriber = $user_id;
                    $item->item_name = filter_var($product_title, FILTER_SANITIZE_STRING);
                    $item->item_price = $product_price;
                    $item->item_qty = filter_var(1, FILTER_SANITIZE_NUMBER_INT);
                    $item->save();
                    $last_order_id = session()->get('custom_order_id');
                    DB::table('orders')
                        ->where('id', $last_order_id)
                        ->update(['status' => 'completed']);
                    if (Auth::user()) {
                        if ($product_type == 'package') {
                            if (session()->has('product_id')) {
                                $package_item = \App\Item::where('subscriber', Auth::user()->id)->first();
                                $id = session()->get('product_id');
                                $package = \App\Package::find($id);
                                $option = !empty($package->options) ? unserialize($package->options) : '';
                                $expiry = !empty($option) ? $package_item->created_at->addDays($option['duration']) : '';
                                $expiry_date = !empty($expiry) ? Carbon::parse($expiry)->toDateTimeString() : '';
                                $user = \App\User::find(Auth::user()->id);
                                if (!empty($package->badge_id) && $package->badge_id != 0) {
                                    $user->badge_id = $package->badge_id;
                                }
                                $user->expiry_date = $expiry_date;
                                $user->save();
                                // send mail
                                if (!empty(config('mail.username')) && !empty(config('mail.password'))) {
                                    $item = DB::table('items')->where('product_id', $id)->get()->toArray();
                                    $package =  Package::where('id', $item[0]->product_id)->first();
                                  //  $user = User::find($item[0]->subscriber);
                                    $role = $user->getRoleNames()->first();
                                    $package_options = unserialize($package->options);
                                    if (!empty($invoice)) {
                                        if ($package_options['duration'] === 'Quarter') {
                                            $expiry_date = $invoice->created_at->addDays(4);
                                        } elseif ($package_options['duration'] === 'Month') {
                                            $expiry_date = $invoice->created_at->addMonths(1);
                                        } elseif ($package_options['duration'] === 'Year') {
                                            $expiry_date = $invoice->created_at->addYears(1);
                                        }
                                    }
                                    if ($role === 'employer') {
                                        if (!empty($user->email)) {
                                            $email_params = array();
                                            $template = DB::table('email_types')->select('id')->where('email_type', 'employer_email_package_subscribed')->get()->first();
                                            if (!empty($template->id)) {
                                                $template_data = EmailTemplate::getEmailTemplateByID($template->id);
                                                $email_params['employer'] = Helper::getUserName(Auth::user()->id);
                                                $email_params['employer_profile'] = url('profile/' . Auth::user()->slug);
                                                $email_params['name'] = $package->title;
                                                $email_params['price'] = $package->cost;
                                                $email_params['expiry_date'] = !empty($expiry_date) ? Carbon::parse($expiry_date)->format('M d, Y') : '';
                                                Mail::to(Auth::user()->email)
                                                    ->send(
                                                        new EmployerEmailMailable(
                                                            'employer_email_package_subscribed',
                                                            $template_data,
                                                            $email_params
                                                        )
                                                    );
                                            }
                                        }
                                    } 
                                    
                                }
                            }
                        } else if ($product_type == 'project') {
                            if (session()->has('project_type')) {
                                $project_type = session()->get('project_type');
                                if ($project_type == 'service') {
                                    $id = session()->get('product_id');
                                    $student = session()->get('service_seller');
                                    $service = Service::find($id);
                                    $service->users()->attach(Auth::user()->id, ['type' => 'employer', 'status' => 'hired', 'seller_id' => $student, 'paid' => 'pending']);
                                    $service->save();
                                    // send message to student
                                    $message = new Message();
                                    $user = User::find(intval(Auth::user()->id));
                                    $message->user()->associate($user);
                                    $message->receiver_id = intval($student);
                                    $message->body = Helper::getUserName(Auth::user()->id) . ' ' . trans('lang.service_purchase') . ' ' . $service->title;
                                    $message->status = 0;
                                    $message->save();
                                    // send mail
                                    if (!empty(config('mail.username')) && !empty(config('mail.password'))) {
                                        $email_params = array();
                                        $template_data = Helper::getstudentNewOrderEmailContent();
                                        $email_params['title'] = $service->title;
                                        $email_params['service_link'] = url('service/' . $service->slug);
                                        $email_params['amount'] = $service->price;
                                        $email_params['student_name'] = Helper::getUserName($student);
                                        $email_params['employer_profile'] = url('profile/' . $user->slug);
                                        $email_params['employer_name'] = Helper::getUserName($user->id);
                                        $student_data = User::find(intval($student));
                                        Mail::to($student_data->email)
                                            ->send(
                                                new studentEmailMailable(
                                                    'student_email_new_order',
                                                    $template_data,
                                                    $email_params
                                                )
                                            );
                                    }
                                }
                            } else {
                                $id = session()->get('product_id');
                                $proposal = Proposal::find($id);
                                $proposal->hired = 1;
                                $proposal->status = 'hired';
                                $proposal->paid = 'pending';
                                $proposal->save();
                                $job = Job::find($proposal->job->id);
                                $job->status = 'hired';
                                $job->save();
                                // send message to student
                                $message = new Message();
                                $user = User::find(intval(Auth::user()->id));
                                $message->user()->associate($user);
                                $message->receiver_id = intval($proposal->freelancer_id);
                                $message->body = trans('lang.hire_for') . ' ' . $job->title . ' ' . trans('lang.project');
                                $message->status = 0;
                                $message->save();
                                // send mail
                                if (!empty(config('mail.username')) && !empty(config('mail.password'))) {
                                    $student = User::find($proposal->freelancer_id);
                                    $employer = User::find($job->user_id);
                                    if (!empty($student->email)) {
                                        $email_params = array();
                                        $template = DB::table('email_types')->select('id')->where('email_type', 'student_email_hire_student')->get()->first();
                                        if (!empty($template->id)) {
                                            $template_data = EmailTemplate::getEmailTemplateByID($template->id);
                                            $email_params['resource_title'] = $job->title;
                                            $email_params['hired_project_link'] = url('job/' . $job->slug);
                                            $email_params['name'] = Helper::getUserName($student->id);
                                            $email_params['link'] = url('profile/' . $student->slug);
                                            $email_params['employer_profile'] = url('profile/' . $employer->slug);
                                            $email_params['emp_name'] = Helper::getUserName($employer->id);
                                            Mail::to($student->email)
                                                ->send(
                                                    new studentEmailMailable(
                                                        'student_email_hire_student',
                                                        $template_data,
                                                        $email_params
                                                    )
                                                );
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $json['type'] = 'error';
                    $json['message'] = trans('lang.money_not_add');
                    return $json;
                }
            } catch (Exception $e) {
                $json['type'] = 'error';
                $json['message'] = $e->getMessage();
                return $json;
            } catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {
                $json['type'] = 'error';
                $json['message'] = $e->getMessage();
                return $json;
            } catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {
                $json['type'] = 'error';
                $json['message'] = $e->getMessage();
                return $json;
            }
        }
        session()->forget('product_id');
        session()->forget('product_title');
        session()->forget('product_price');
        session()->forget('custom_order_id');
        $project_type = session()->get('project_type');
        if (Auth::user()->getRoleNames()[0] == "employer") {
            if ($type == 'project') {
                if ($project_type == 'service') {
                    $json['url'] = url('employer/services/hired');
                } else {
                    $json['url'] = url('employer/jobs/hired');
                }
                $json['type'] = 'success';
                $json['message'] = trans('lang.student_successfully_hired');
                session()->forget('type');
                return $json;
            } else {
                $json['type'] = 'success';
                $json['message'] = trans('lang.thanks_subscription');
                $json['url'] = url('dashboard/packages/employer');
                session()->forget('type');
                return $json;
            }
        } else if (Auth::user()->getRoleNames()[0] == "student") {
            Log::info($request);
            $resource_info=Resources::where([
                ['slug', '=', $request['slug']],
            ])->first();

              
            $rooms = new Transactions;
            $rooms->seller_id = $resource_info->user_id;
            $rooms->seller_name = $resource_info->user_name;
            $rooms->total_price = $resource_info['price'];
            $rooms->buyer_id = Auth::user()->id;
            $rooms->buyer_name = Auth::user()->first_name." ".Auth::user()->last_name;
            $rooms->buyers_rating = 0;
            $rooms->resource_slug = $resource_info['slug'];
            $rooms->comment = '';
            $rooms->save();


            $json['type'] = 'success';
            $json['message'] = trans('lang.thanks_subscription');
            $json['url'] = url('resource/'.$resource_info['slug']);
            session()->forget('type');
            return $json;
        }
    }


   public function stripepackages(Request $request)
    {

        
        //print_r($coupon_price);die;

        Log::info("In stripe controller");
        Log::info($request);
        $settings = SiteManagement::getMetaValue('commision');
        $seller = Profile::where('user_id', $request->seller_id)->first();
        //echo "<pre>";print_r($seller);die;
        Log::info("PROFILE");
        Log::info($seller);
        $currency = 'AUD';
        $current_year = Carbon::now()->format('Y');
        $validator = Validator::make(
            $request->all(),
            [
                'card_no' => 'required',
                'ccExpiryMonth' => 'required',
                'ccExpiryYear' => 'required',
                'cvvNumber' => 'required',
            ]
        );
        if ($request['ccExpiryYear'] < $current_year) {
            // Session::flash('error', trans('lang.valid_year'));
            // return Redirect::back()->withInput();
            $json['type'] = 'error';
            $json['message'] = trans('lang.valid_year');
            return $json;
        }
        if ($seller->payout_settings === "") {

            // Session::flash('error', trans('lang.valid_year'));
            // return Redirect::back()->withInput();
            $json['type'] = 'error';

            $json['message'] = trans('lang.seller_info_missing');
            return $json;
        }
      
        $product_id = $request->slug;
        // $id = session()->get('product_id');
        // dd($id);
        $product_title = $request->title;
        $product_price = Package::select('cost','id')->where('slug', $request->slug)->first();
        $packid=$product_price->id;
        
        
       
        Log::info("AMOUNT !");
        Log::info($product_price);
        $type = Session::has('type') ? session()->get('type') : '';
        $user_id = Auth::user() ? Auth::user()->id : '';
        $input = $request->all();
        if ($validator->passes()) {
            $input = array_except($input, array('_token'));
            if (!empty(config('constants.stripe_secret'))) {
                \Artisan::call('optimize:clear');
                $stripe = Stripe::make(config('constants.stripe_secret'));
                //$stripe=getconfig('constants.stripe_key');
            } else {
                // Session::flash('error', trans('lang.empty_stripe_key'));
                // return Redirect::back();
                $json['type'] = 'error';
                $json['message'] = trans('lang.empty_stripe_key');
                return $json;
            }
            try {
                $token = $stripe->tokens()->create(
                    [
                        'card' => [
                            'number'    => $request->get('card_no'),
                            'exp_month' => $request->get('ccExpiryMonth'),
                            'exp_year'  => $request->get('ccExpiryYear'),
                            'cvc'       => $request->get('cvvNumber'),
                        ],
                    ]
                );
                if (!isset($token['id'])) {
                    // Session::flash('error', 'The Stripe Token was not generated correctly');
                    // return Redirect::back();
                    $json['type'] = 'error';
                    $json['message'] = 'The Stripe Token was not generated correctly';
                    return $json;
                }
                Log::info("I am TOKEN");
                Log::info($token);

                $customer = $stripe->customers()->create(
                    [
                        'email' => Auth::user()->email,
                    ]
                );

                $coupon_price=config('constants.coupon_price');
                $amountded=($product_price['cost']) - $coupon_price;

                $checktransaction=DB::table('transactions')->where('buyer_id','=', $user_id)->first();
                if($checktransaction===null)
                {
                  
                $stripe1 = \Stripe\Stripe::setApiKey("");
                \Stripe\Customer::createSource(
                    $customer['id'],
                    ['source' => $token['id']]
                );
                $stripe = new \Stripe\StripeClient(
                    ''
                  );

                $payment_detail = \Stripe\PaymentIntent::create([
                        'payment_method_types' => ['card'],
                        "amount" => ($amountded)*100,
                        "currency" => "aud",
                        'customer' => $customer['id']

                ]);
                
                $payment_detail=$stripe->paymentIntents->confirm(
                    $payment_detail['id'],
                    ['payment_method' => 'pm_card_visa']
                  );
                Log::info("COMING ");
                Log::info($payment_detail);
                
                }

                else
                {
                    
                     $stripe = \Stripe\Stripe::setApiKey("");
                \Stripe\Customer::createSource(
                    $customer['id'],
                    ['source' => $token['id']]
                );
                
                $payment_detail = \Stripe\PaymentIntent::create([
                        'payment_method_types' => ['card'],
                        "amount" => ($product_price['cost']) * 100,
                        "currency" => "aud",
                        'customer' => $customer['id']

                ]);
             
                $payment_detail=$stripe->paymentIntents->confirm(
                    $payment_detail['id'],
                    ['payment_method' => 'pm_card_visa']
                  );
                Log::info("COMING ");
                Log::info($payment_detail);

                }


                
                if ($payment_detail['status'] == 'succeeded') {

                    $fee = !empty($payment_detail['application_fee_amount']) ? $payment_detail['application_fee_amount'] : 0;
                    $invoice = new Invoice();
                    $invoice->title = 'Invoice';
                    $invoice->price = $product_price['cost'];
                    $invoice->payer_name = filter_var($customer['name'], FILTER_SANITIZE_STRING);
                    $invoice->payer_email = filter_var($customer['email'], FILTER_SANITIZE_EMAIL);
                    $invoice->seller_email = 'test@email.com';
                    $invoice->currency_code = filter_var($payment_detail['currency'], FILTER_SANITIZE_STRING);
                    $invoice->payer_status = '';
                    $invoice->transaction_id = filter_var($payment_detail['id'], FILTER_SANITIZE_STRING);
                    $invoice->invoice_id = filter_var($payment_detail['source']['id'], FILTER_SANITIZE_STRING);
                    $invoice->customer_id = filter_var($customer['id'], FILTER_SANITIZE_STRING);
                    $invoice->shipping_amount = 0;
                    $invoice->handling_amount = 0;
                    $invoice->insurance_amount = 0;
                    $invoice->sales_tax = 0;
                    $invoice->payment_mode = filter_var('stripe', FILTER_SANITIZE_STRING);
                    $invoice->paypal_fee = $fee;
                    $invoice->paid = "PAID";//$payment_detail['paid'];
                    $product_type = $type;
                    $invoice->type = $product_type;
                    $invoice->save();
                    $invoice_id = DB::getPdo()->lastInsertId();
                    if ($type == 'package') {
                        $item = DB::table('items')->select('id')->where('subscriber', $user_id)->first();
                        if (!empty($item)) {
                            $item = Item::find($item->id);
                        } else {
                            $item = new Item();
                        }
                    } else {
                        $item = new Item();
                    }
                    $item->invoice_id = filter_var($invoice_id, FILTER_SANITIZE_NUMBER_INT);
                    $item->product_id = filter_var($product_id, FILTER_SANITIZE_NUMBER_INT);
                    $item->subscriber = $user_id;
                    $item->item_name = filter_var($product_title, FILTER_SANITIZE_STRING);
                    $item->item_price = $product_price;
                    $item->item_qty = filter_var(1, FILTER_SANITIZE_NUMBER_INT);
                    $item->save();
                    $last_order_id = session()->get('custom_order_id');
                   
                    DB::table('orders')
                        ->where('id', $last_order_id)
                        ->update(['status' => 'completed']);
                    if (Auth::user()) {
                        if ($product_type == 'package') {
                            if (session()->has('product_id')) {
                                 $date=date('Y-m-d');
                                $package_item = \App\Item::where('subscriber', Auth::user()->id)->first();
                                $id =$packid;


                                $package = \App\Package::find($id);
                                $option = !empty($package->options) ? unserialize($package->options) : '';
                                $duration=$option['duration'];
                                $expiry = !empty($option) ? date('Y-m-d',strtotime($date) + (24*3600*$duration)) : '';
                                $expiry_date = !empty($expiry) ? Carbon::parse($expiry)->toDateTimeString() : '';
                                $user = \App\User::find(Auth::user()->id);
                                if (!empty($package->badge_id) && $package->badge_id != 0) {
                                    $user->badge_id = $package->badge_id;
                                }
                                $user->expiry_date = $expiry_date;
                                $user->save();
                                // send mail
                                if (!empty(config('mail.username')) && !empty(config('mail.password'))) {
                                    $item = DB::table('items')->where('product_id', $id)->get()->toArray();
                                    $package =  Package::where('id', $item[0]->product_id)->first();
                                    $user = User::find($item[0]->subscriber);
                                    $user22 = \App\User::find(Auth::user()->id);
                                    $role = $user22->getRoleNames()->first();

                                    $package_options = unserialize($package->options);
                                    if (!empty($invoice)) {
                                        if ($package_options['duration'] === 'Quarter') {
                                            $expiry_date = $invoice->created_at->addDays(4);
                                        } elseif ($package_options['duration'] === 'Month') {
                                            $expiry_date = $invoice->created_at->addMonths(1);
                                        } elseif ($package_options['duration'] === 'Year') {
                                            $expiry_date = $invoice->created_at->addYears(1);
                                        }
                                    }
                                    if ($role === 'employer') {
                                        if (!empty($user->email)) {
                                            $email_params = array();
                                            $template = DB::table('email_types')->select('id')->where('email_type', 'employer_email_package_subscribed')->get()->first();
                                            if (!empty($template->id)) {
                                                $template_data = EmailTemplate::getEmailTemplateByID($template->id);
                                                $email_params['employer'] = Helper::getUserName(Auth::user()->id);
                                                $email_params['employer_profile'] = url('profile/' . Auth::user()->slug);
                                                $email_params['name'] = $package->title;
                                                $email_params['price'] = $package->cost;
                                                $email_params['expiry_date'] = !empty($expiry_date) ? Carbon::parse($expiry_date)->format('M d, Y') : '';
                                                Mail::to(Auth::user()->email)
                                                    ->send(
                                                        new EmployerEmailMailable(
                                                            'employer_email_package_subscribed',
                                                            $template_data,
                                                            $email_params
                                                        )
                                                    );
                                            }
                                        }
                                    } elseif ($role === 'student') {
                                        if (!empty(Auth::user()->email)) {
                                            $email_params = array();
                                            $template = DB::table('email_types')->select('id')->where('email_type', 'student_email_package_subscribed')->get()->first();
                                            if (!empty($template->id)) {
                                                $template_data = EmailTemplate::getEmailTemplateByID($template->id);
                                                $email_params['student'] = Helper::getUserName(Auth::user()->id);
                                                $email_params['student_profile'] = url('profile/' . Auth::user()->slug);
                                                $email_params['name'] = $product_price;
                                                $email_params['price'] = $product_title;
                                                $email_params['expiry_date'] = !empty($expiry_date) ? Carbon::parse($expiry_date)->format('M d, Y') : '';
                                                Mail::to(Auth::user()->email)
                                                    ->send(
                                                        new studentEmailMailable(
                                                            'student_email_package_subscribed',
                                                            $template_data,
                                                            $email_params
                                                        )
                                                    );
                                            }
                                        }
                                    }
                                }
                            }
                        } else if ($product_type == 'project') {
                            if (session()->has('project_type')) {
                                $project_type = session()->get('project_type');
                                if ($project_type == 'service') {
                                    $id = session()->get('product_id');
                                    $student = session()->get('service_seller');
                                    $service = Service::find($id);
                                    $service->users()->attach(Auth::user()->id, ['type' => 'employer', 'status' => 'hired', 'seller_id' => $student, 'paid' => 'pending']);
                                    $service->save();
                                    // send message to student
                                    $message = new Message();
                                    $user = User::find(intval(Auth::user()->id));
                                    $message->user()->associate($user);
                                    $message->receiver_id = intval($student);
                                    $message->body = Helper::getUserName(Auth::user()->id) . ' ' . trans('lang.service_purchase') . ' ' . $service->title;
                                    $message->status = 0;
                                    $message->save();
                                    // send mail
                                    if (!empty(config('mail.username')) && !empty(config('mail.password'))) {
                                        $email_params = array();
                                        $template_data = Helper::getstudentNewOrderEmailContent();
                                        $email_params['title'] = $service->title;
                                        $email_params['service_link'] = url('service/' . $service->slug);
                                        $email_params['amount'] = $service->price;
                                        $email_params['student_name'] = Helper::getUserName($student);
                                        $email_params['employer_profile'] = url('profile/' . $user->slug);
                                        $email_params['employer_name'] = Helper::getUserName($user->id);
                                        $student_data = User::find(intval($student));
                                        Mail::to($student_data->email)
                                            ->send(
                                                new studentEmailMailable(
                                                    'student_email_new_order',
                                                    $template_data,
                                                    $email_params
                                                )
                                            );
                                    }
                                }
                            } else {
                                $id = session()->get('product_id');
                                $proposal = Proposal::find($id);
                                $proposal->hired = 1;
                                $proposal->status = 'hired';
                                $proposal->paid = 'pending';
                                $proposal->save();
                                $job = Job::find($proposal->job->id);
                                $job->status = 'hired';
                                $job->save();
                                // send message to student
                                $message = new Message();
                                $user = User::find(intval(Auth::user()->id));
                                $message->user()->associate($user);
                                $message->receiver_id = intval($proposal->freelancer_id);
                                $message->body = trans('lang.hire_for') . ' ' . $job->title . ' ' . trans('lang.project');
                                $message->status = 0;
                                $message->save();
                                // send mail
                                if (!empty(config('mail.username')) && !empty(config('mail.password'))) {
                                    $student = User::find($proposal->freelancer_id);
                                    $employer = User::find($job->user_id);
                                    if (!empty($student->email)) {
                                        $email_params = array();
                                        $template = DB::table('email_types')->select('id')->where('email_type', 'student_email_hire_student')->get()->first();
                                        if (!empty($template->id)) {
                                            $template_data = EmailTemplate::getEmailTemplateByID($template->id);
                                            $email_params['resource_title'] = $job->title;
                                            $email_params['hired_project_link'] = url('job/' . $job->slug);
                                            $email_params['name'] = Helper::getUserName($student->id);
                                            $email_params['link'] = url('profile/' . $student->slug);
                                            $email_params['employer_profile'] = url('profile/' . $employer->slug);
                                            $email_params['emp_name'] = Helper::getUserName($employer->id);
                                            Mail::to($student->email)
                                                ->send(
                                                    new studentEmailMailable(
                                                        'student_email_hire_student',
                                                        $template_data,
                                                        $email_params
                                                    )
                                                );
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $json['type'] = 'error';
                    $json['message'] = trans('lang.money_not_add');
                    return $json;
                }
            } catch (Exception $e) {
                $json['type'] = 'error';
                $json['message'] = $e->getMessage();
                return $json;
            } catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {
                $json['type'] = 'error';
                $json['message'] = $e->getMessage();
                return $json;
            } catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {
                $json['type'] = 'error';
                $json['message'] = $e->getMessage();
                return $json;
            }
        }
        session()->forget('product_id');
        session()->forget('product_title');
        session()->forget('product_price');
        session()->forget('custom_order_id');
        $project_type = session()->get('project_type');
        if (Auth::user()->getRoleNames()[0] == "employer") {
                $resource_info=Package::where([
                ['slug', '=', $request['slug']],
            ])->first();


            $rooms = new Transactions;
            $rooms->seller_id = $resource_info->user_id;
            $rooms->seller_name = $resource_info->user_name;
            $rooms->buyer_id = Auth::user()->id;
            $rooms->buyer_name = Auth::user()->first_name." ".Auth::user()->last_name;
            $rooms->buyers_rating = 0;
            $rooms->resource_slug = $request['slug'];
            $rooms->comment = '';
            $rooms->save();

            if ($type == 'project') {
                if ($project_type == 'service') {
                    $json['url'] = url('employer/services/hired');
                } else {
                    $json['url'] = url('employer/jobs/hired');
                }
                $json['type'] = 'success';
                $json['message'] = trans('lang.student_successfully_hired');
                session()->forget('type');
                return $json;
            } else {
                $json['type'] = 'success';
                $json['message'] = trans('lang.thanks_subscription');
                $json['url'] = url('employer/dashboard');
                session()->forget('type');
                return $json;
            }
        } 

    }

}
