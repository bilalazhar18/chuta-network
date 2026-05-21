<?php

/**
 * Class PublicController
 *
 * @category Worketic
 *
 * @package Worketic
 * @author  Amentotech <theamentotech@gmail.com>
 * @license http://www.amentotech.com Amentotech
 * @link    http://www.amentotech.com
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\User;
use App\Language;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationMailable;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Hash;
use Auth;
use DB;
use App\Helper;
use App\Degree;
use App\Profile;
use App\Offer;
use App\Category;
use App\Location;
use App\Skill;
use App\Resources;
use App\Transactions;
use Session;
use Storage;
use App\Report;
use App\Job;
use App\Proposal;
use App\EmailTemplate;
use App\Mail\GeneralEmailMailable;
use App\Mail\AdminEmailMailable;
use App\SiteManagement;
use App\Review;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Payout;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use App\Service;
use App\DeliveryTime;
use App\ResponseTime;
use App\Article;
use App\Resourcefeedback;

/**
 * Class PublicController
 *
 */
class PublicController extends Controller
{

    /**
     * User Login Function
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function loginUser(Request $request)
    {
        $json = array();
        if (Session::has('user_id')) {
            $id = Session::get('user_id');
            $user = User::find($id);
            Auth::login($user);
            $json['type'] = 'success';
            $json['role'] = $user->getRoleNames()->first();
            session()->forget('user_id');
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }

    /** 
     * Step1 Registeration Validation
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function registerStep1Validation(Request $request)
    {
        Log::info('SHOWING LOGS 1: ');
        Log::info($request);
        $this->validate(
            $request,
            [
                'first_name' => 'required',
                'company_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users',
                'contact_number'=>'required',
            ]
        );
    }

    /**
     * Step2 Registeration Validation
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function registerStep2Validation(Request $request)
    {
        Log::info('SHOWING LOGS 2: ');
        Log::info($request);
        $this->validate(
            $request,
            [
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required',
                'termsconditions' => 'required',
            ]
        );
    }

    /**
     * Set slug before saving in DB
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function verifyUserCode(Request $request)
    {
        $json = array();
        if (Session::has('user_id')) {
            $id = Session::get('user_id');
            $email = Session::get('email');
            $password = Session::get('password');
            $user = User::find($id);
            if (!empty($request['code'])) {
                if ($request['code'] === $user->verification_code) {
                    $user->user_verified = 1;
                    $user->verification_code = null;
                    $user->save();
                    $json['type'] = 'success';
                    //send mail
                    if (!empty(config('mail.username')) && !empty(config('mail.password'))) {
                        $email_params = array();
                        $template = DB::table('email_types')->select('id')->where('email_type', 'new_user')->get()->first();
                        if (!empty($template->id)) {
                            $template_data = EmailTemplate::getEmailTemplateByID($template->id);
                            $email_params['name'] = Helper::getUserName($id);
                            $email_params['email'] = $email;
                            $email_params['password'] = $password;
                            Mail::to($email)
                            ->send(
                                new GeneralEmailMailable(
                                    'new_user',
                                    $template_data,
                                    $email_params
                                )
                            );
                        }
                        $admin_template = DB::table('email_types')->select('id')->where('email_type', 'admin_email_registration')->get()->first();
                        if (!empty($template->id)) {
                            $template_data = EmailTemplate::getEmailTemplateByID($admin_template->id);
                            $email_params['name'] = Helper::getUserName($id);
                            $email_params['email'] = $email;
                            $email_params['link'] = url('profile/' . $user->slug);
                            Mail::to(config('mail.username'))
                            ->send(
                                new AdminEmailMailable(
                                    'admin_email_registration',
                                    $template_data,
                                    $email_params
                                )
                            );
                        }
                    }
                    session()->forget('password');
                    session()->forget('email');
                    return $json;
                } else {
                    $json['type'] = 'error';
                    $json['message'] = trans('lang.invalid_verify_code');
                    return $json;
                }
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.verify_code');
                return $json;
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.session_expire');
            return $json;
        }
    }

    /**
     * Download file.
     *
     * @param type    $type     file type
     * @param string  $filename file typname
     * @param integer $id       id
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    function getFile($type, $filename, $id)
    {
        if (!empty($type) && !empty($filename) && !empty($id)) {
            if (Storage::disk('local')->exists('uploads/' . $type . '/' . $id . '/' . $filename)) {
                return Storage::download('uploads/' . $type . '/' . $id . '/' . $filename);
            } else {
                Session::flash('error', trans('lang.file_not_found'));
                return Redirect::back();
            }
        } else {
            abort(404);
        }
    }

    public function showRegistrationForm($id,$channel)
    {
        
        return view('auth.register',compact('id','channel'));
        
    }

    /**
     * Show user profile.
     *
     * @param string $slug slug
     *
     * @return \Illuminate\Http\Response
     */
    public function showUserProfile($slug)
    {

        $user = User::select('id')->where('slug', $slug)->first();
        //$user = User::select('id')->where('slug', $slug)->get();
        if (!empty($user)) {
            $user = User::find($user->id);
            if ($user->is_disabled == 'true') {
                abort(404);
            }
            $skills = $user->skills()->get();
            $job = Job::where('user_id', $user->id)->get();
            $profile = Profile::all()->where('user_id', $user->id)->first();
           // $resources = DB::table('resources')->where('user_id', $user->id)->get();


            $resources = DB::table('resources')->where('user_id', $user->id)->get();
            
            $reasons = Helper::getReportReasons();
            $avatar = !empty($profile->avater) ? '/uploads/users/' . $profile->user_id . '/' . $profile->avater : '/images/user.jpg';
            $banner = !empty($profile->banner) ? '/uploads/users/' . $profile->user_id . '/' . $profile->banner : Helper::getUserProfileBanner($user->id);
            $auth_user = Auth::user() ? true : false;
            $user_name = Helper::getUserName($profile->user_id);
            $current_date = Carbon::now()->format('M d, Y');
            $tagline = !empty($profile) ? $profile->tagline : '';
            $desc = !empty($profile) ? $profile->description : '';
            if ($user->getRoleNames()->first() === 'student') {
                $services = array();
                if (Schema::hasTable('services') && Schema::hasTable('service_user')) {
                    $services = $user->services;
                }
                Log::info("You mean me!");
                $acceptStatus = 0;
                $requestId = 0;
                if(!empty(Auth::user()->id)){
                    $is_friend=Offer::where([
                        ['freelancer_id', '=', $profile->user_id],
                        ['user_id', '=', Auth::user()->id]
                    ])->get()->toArray();

                    // For Following! 0 means: Req not send | 1 means: Req sent & not accepted | 2 means: Req sent & accepted
                    if (!empty($is_friend)){
                        if($is_friend[0]['is_friend']==0){
                            $acceptStatus = 1;
                        }else{
                            $acceptStatus = 2;
                        }
                    }else{
                        Log::info("Here!");
                        $is_friend=Offer::where([
                            ['user_id', '=', $profile->user_id],
                            ['freelancer_id', '=', Auth::user()->id]
                        ])->get()->toArray();
                        Log::info($profile->user_id);
                        Log::info(Auth::user()->id);
                        Log::info($is_friend);
                        if (!empty($is_friend)){
                            if($is_friend[0]['is_friend']==0){
                                $acceptStatus = 3;
                                $requestId = $is_friend[0]['id']; 
                            }else{
                                $acceptStatus = 2;
                            }
                        }
                    }
                }


                
                $reviews = Review::where('receiver_id', $user->id)->get();
                $awards = !empty($profile->awards) ? unserialize($profile->awards) : array();
                $projects = !empty($profile->projects) ? unserialize($profile->projects) : array();
                $experiences = !empty($profile->experience) ? unserialize($profile->experience) : array();
                $education = !empty($profile->education) ? unserialize($profile->education) : array();
                $student_rating  = !empty($user->profile->ratings) ? Helper::getUnserializeData($user->profile->ratings) : 0;
                $rating = !empty($student_rating) ? $student_rating[0] : 0;
                $joining_date = !empty($profile->created_at) ? Carbon::parse($profile->created_at)->format('M d, Y') : '';
                $jobs = Job::select('title', 'id')->get()->pluck('title', 'id');
                $save_student = !empty(auth()->user()->profile->saved_student) ? unserialize(auth()->user()->profile->saved_student) : array();
                $badge = Helper::getUserBadge($user->id);
                $feature_class = !empty($badge) ? 'wt-featured' : '';
                $badge_color = !empty($badge) ? $badge->color : '';
                $badge_img  = !empty($badge) ? $badge->image : '';
                $amount = Payout::where('user_id', $user->id)->select('amount')->pluck('amount')->first();
                $employer_projects = Auth::user() ? Helper::getEmployerJobs(Auth::user()->id) : array();
                $currency_symbol  = !empty($payment_settings) && !empty($payment_settings[0]['currency']) ? Helper::currencyList($payment_settings[0]['currency']) : array();
                $symbol = !empty($currency_symbol['symbol']) ? $currency_symbol['symbol'] : '$';
                $settings = !empty(SiteManagement::getMetaValue('settings')) ? SiteManagement::getMetaValue('settings') : array();
                $display_chat = !empty($settings[0]['chat_display']) ? $settings[0]['chat_display'] : false;
                $payment_settings = SiteManagement::getMetaValue('commision');
                $enable_package = !empty($payment_settings) && !empty($payment_settings[0]['enable_packages']) ? $payment_settings[0]['enable_packages'] : 'true';
                $videos = !empty($profile->videos) ? Helper::getUnserializeData($profile->videos) : '';
                $feedbacks = Review::select('feedback')->where('receiver_id', $user->id)->count(); 
                $average_rating_count = !empty($feedbacks) ? $reviews->sum('avg_rating')/$feedbacks : 0;
                if (file_exists(resource_path('views/extend/front-end/users/student-show.blade.php'))) {
                    return View(
                        'extend.front-end.users.student-show',
                        compact(
                            'average_rating_count',
                            'videos',
                            'services',
                            'acceptStatus',
                            'requestId',
                            'profile',
                            'amount',
                            'skills',
                            'resources',
                            'user',
                            'job',
                            'reasons',
                            'reviews',
                            'avatar',
                            'banner',
                            'user_name',
                            'jobs',
                            'rating',
                            'education',
                            'experiences',
                            'projects',
                            'awards',
                            'joining_date',
                            'save_student',
                            'auth_user',
                            'badge',
                            'feature_class',
                            'badge_color',
                            'badge_img',
                            'employer_projects',
                            'currency_symbol',
                            'current_date',
                            'symbol',
                            'tagline',
                            'desc',
                            'display_chat',
                            'enable_package'
                        )
                    );
                } else {
                    return View(
                        'front-end.users.student-show',
                        compact(
                            'average_rating_count',
                            'videos',
                            'services',
                            'profile',
                            'acceptStatus',
                            'requestId',
                            'amount',
                            'skills',
                            'user',
                            'resources',
                            'job',
                            'reasons',
                            'reviews',
                            'avatar',
                            'banner',
                            'user_name',
                            'jobs',
                            'rating',
                            'education',
                            'experiences',
                            'projects',
                            'awards',
                            'joining_date',
                            'save_student',
                            'auth_user',
                            'badge',
                            'feature_class',
                            'badge_color',
                            'badge_img',
                            'employer_projects',
                            'currency_symbol',
                            'current_date',
                            'symbol',
                            'tagline',
                            'desc',
                            'display_chat',
                            'enable_package'
                        )
                    );
                }
            } elseif ($user->getRoleNames()->first() === 'employer') {
                $jobs = Job::where('user_id', $profile->user_id)->latest()->paginate(7);
                $followers = DB::table('followers')->where('following', $profile->user_id)->get();
                $save_employer = !empty(auth()->user()->profile->saved_employers) ? unserialize(auth()->user()->profile->saved_employers) : array();
                $save_jobs = !empty(auth()->user()->profile->saved_jobs) ? unserialize(auth()->user()->profile->saved_jobs) : array();
                $currency = SiteManagement::getMetaValue('commision');
                $symbol   = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
                $breadcrumbs_settings = SiteManagement::getMetaValue('show_breadcrumb');
                $show_breadcrumbs = !empty($breadcrumbs_settings) ? $breadcrumbs_settings : 'true';
                if (file_exists(resource_path('views/extend/front-end/users/employer-show.blade.php'))) {
                    return View(
                        'extend.front-end.users.employer-show',
                        compact(
                            'profile',
                            'skills',
                            'user',
                            'job',
                            'reasons',
                            'avatar',
                            'banner',
                            'user_name',
                            'jobs',
                            'followers',
                            'save_employer',
                            'save_jobs',
                            'auth_user',
                            'current_date',
                            'symbol',
                            'tagline',
                            'desc',
                            'show_breadcrumbs'
                        )
                    );
                } else {
                    return View(
                        'front-end.users.employer-show',
                        compact(
                            'profile',
                            'skills',
                            'user',
                            'job',
                            'reasons',
                            'avatar',
                            'banner',
                            'user_name',
                            'jobs',
                            'followers',
                            'save_employer',
                            'save_jobs',
                            'auth_user',
                            'current_date',
                            'symbol',
                            'tagline',
                            'desc',
                            'show_breadcrumbs'
                        )
                    );
                }
            }
        } else {
            abort(404);
        }
    }
    
    
    public function showSpecificResource($dataType,$slug)
    {


        if($dataType=='resource')
        {

           $resources =  Resources::where('slug',$slug)->get();


           $is_login = Auth::user();
           Log::info("Search Bar");
        // $categories = array();
        // $degree = array();
        // $locations  = array();
        // $languages  = array();
        // $categories = Category::all();
        // $degree = Degree::all();
        // $locations  = Location::all();
        // $languages  = Language::all();
        // $skills     = Skill::all();
        // $currency   = SiteManagement::getMetaValue('commision');
        // $symbol     = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
        // $student_skills = Helper::getstudentLevelList();
        // $project_length = Helper::getJobDurationList();


        // if ($type == 'job') {
        //     if (Helper::getAccessType() == 'both' || Helper::getAccessType() == 'services') {
        //         abort(404);
        //     }
        // }
        // if ($type == 'service') {
        //     if (Helper::getAccessType() == 'both' || Helper::getAccessType() == 'jobs') {
        //         abort(404);
        //     }
        // }
        // $search_categories = !empty($_GET['category']) ? $_GET['category'] : array();
        // $search_degree = !empty($_GET['degree']) ? $_GET['degree'] : array();
        // $search_locations = !empty($_GET['locations']) ? $_GET['locations'] : array();
        // $search_skills = !empty($_GET['skills']) ? $_GET['skills'] : array();
        // $search_project_lengths = !empty($_GET['project_lengths']) ? $_GET['project_lengths'] : array();
        // $search_languages = !empty($_GET['languages']) ? $_GET['languages'] : array();
        // $search_employees = !empty($_GET['employees']) ? $_GET['employees'] : array();
        // $search_hourly_rates = !empty($_GET['hourly_rate']) ? $_GET['hourly_rate'] : array();
        // $search_freelaner_types = !empty($_GET['freelaner_type']) ? $_GET['freelaner_type'] : array();
        // $search_english_levels = !empty($_GET['english_level']) ? $_GET['english_level'] : array();
        // $search_delivery_time = !empty($_GET['delivery_time']) ? $_GET['delivery_time'] : array();
        // $search_response_time = !empty($_GET['response_time']) ? $_GET['response_time'] : array();
        // $current_date = Carbon::now()->toDateTimeString();
        // $currency = SiteManagement::getMetaValue('commision');
        // $symbol   = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
        // $inner_page  = SiteManagement::getMetaValue('inner_page_data');

        // $payment_settings = SiteManagement::getMetaValue('commision');
        // $enable_package = !empty($payment_settings) && !empty($payment_settings[0]['enable_packages']) ? $payment_settings[0]['enable_packages'] : 'true';
        // $breadcrumbs_settings = SiteManagement::getMetaValue('show_breadcrumb');
        // $show_breadcrumbs = !empty($breadcrumbs_settings) ? $breadcrumbs_settings : 'true';



           return view(
            'front-end.services.index2',
            compact(
                'services_total_records',
                'type',
                'services',
                'is_login',
                'resources',
                'is_login',
                'skills'
            )
        );
       }

   }

   public function demo()
   {

       return view('front-end.services.demo');
     // return View::make('front-end.services.demo')->render();
   }


   public function showspecificprofile($dataType,$slug)
   {

    $is_login = Auth::user();

    if($dataType=='location')
    {
        $users = User::where('location_id', $slug)->where('is_disabled', 'false')->get();

    }
    elseif($dataType=='degree')
    {
            //$users= User::role('student')->get();

        $degree=Profile::where('degree_id',$slug)->select('user_id')->get();
        if(sizeof($degree)>0)
        {
            foreach($degree as $val)
            {
                $users= User::role('student')->where('id',$val->user_id)->where('is_disabled', 'false')->get();
            }
        }
        else
        {
            $users=[]; 
        }      
    }

    elseif($dataType=='skills')
    {

        $getskill=DB::table('skill_user')->where('skill_id',$slug)->get();
        if(sizeof($getskill)>0)
        {
            foreach($getskill as $val)
            {
                $users = User::where('id', $val->user_id)->get();
            }

        }
        else
        {
           $users=array();
       }

   }

   elseif($dataType=='student')
   {

    $users = User::where('slug', $slug)->where('is_disabled', 'false')->get();

}


else
{
 $cgpa=Profile::where('cgpa',$slug)->select('user_id')->get();
 if(sizeof($cgpa)>0)
 {
    foreach($cgpa as $val)
    {
        $users= User::role('student')->where('id',$val->user_id)->where('is_disabled', 'false')->get();
    }

}
else
{
    $users=[];   
}

}

Log::info("Search Bar");
$categories = array();
$degree = array();
$locations  = array();
$languages  = array();
$categories = Category::all();
$degree = Degree::all();
$locations  = Location::all();
$languages  = Language::all();
$skills     = Skill::all();
$currency   = SiteManagement::getMetaValue('commision');
$symbol     = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
$student_skills = Helper::getstudentLevelList();
$project_length = Helper::getJobDurationList();
        //$keyword = !empty($_GET['s']) ? $_GET['s'] : '';

        //$type = !empty($_GET['type']) ? $_GET['type'] : $search_type;
$search_categories = !empty($_GET['category']) ? $_GET['category'] : array();
$search_degree = !empty($_GET['degree']) ? $_GET['degree'] : array();
$search_locations = !empty($_GET['locations']) ? $_GET['locations'] : array();
$search_skills = !empty($_GET['skills']) ? $_GET['skills'] : array();
$search_project_lengths = !empty($_GET['project_lengths']) ? $_GET['project_lengths'] : array();
$search_languages = !empty($_GET['languages']) ? $_GET['languages'] : array();
$search_employees = !empty($_GET['employees']) ? $_GET['employees'] : array();
$search_hourly_rates = !empty($_GET['hourly_rate']) ? $_GET['hourly_rate'] : array();
$search_freelaner_types = !empty($_GET['freelaner_type']) ? $_GET['freelaner_type'] : array();
$search_english_levels = !empty($_GET['english_level']) ? $_GET['english_level'] : array();
$search_delivery_time = !empty($_GET['delivery_time']) ? $_GET['delivery_time'] : array();
$search_response_time = !empty($_GET['response_time']) ? $_GET['response_time'] : array();
$current_date = Carbon::now()->toDateTimeString();
$currency = SiteManagement::getMetaValue('commision');
$symbol   = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
$inner_page  = SiteManagement::getMetaValue('inner_page_data');

$payment_settings = SiteManagement::getMetaValue('commision');
$enable_package = !empty($payment_settings) && !empty($payment_settings[0]['enable_packages']) ? $payment_settings[0]['enable_packages'] : 'true';
$breadcrumbs_settings = SiteManagement::getMetaValue('show_breadcrumb');
$show_breadcrumbs = !empty($breadcrumbs_settings) ? $breadcrumbs_settings : 'true';


if (file_exists(resource_path('views/extend/front-end/students/index1.blade.php'))) {
    return view(
        'extend.front-end.students.index1',
        compact(
            'type',
            'users',
            'categories',
            'locations',
            'languages',
            'skills',
            'is_login',
            'project_length',
            'keyword',
            'users_total_records',
            'save_student',
            'symbol',
            'current_date',
            'f_list_meta_title',
            'f_list_meta_desc',
            'show_f_banner',
            'f_inner_banner',
            'enable_package',
            'show_breadcrumbs'
        )
    );
} else {
    return view(
        'front-end.students.index1',
        compact(
            'type',
            'users',
            'categories',
            'is_login',
            'locations',
            'languages',
            'skills',
            'project_length',
            'keyword',
            'users_total_records',
            'save_student',
            'symbol',
            'current_date',
            'f_list_meta_title',
            'f_list_meta_desc',
            'show_f_banner',
            'f_inner_banner',
            'enable_package',
            'show_breadcrumbs'
        )
    );
}

}

public function showtutor($dataType,$slug)
{
    //      echo $dataType; echo "<br>";
    //    echo $slug;
    //    die;
    if($dataType=='tutor')
    {
        $users = User::role('student')->where('slug',$slug)->where('is_disabled', 'false')->get();
    }

    elseif($dataType=='location')
    {

            // $users = User::with(['gettutoruser'=> function($q){
            //     $q->where('is_tutor', 1);
            // }])->where('location_id', $slug)->get();
            // dd($users);


        $users = User::whereHas('gettutoruser', function($q){
            $q->where('is_tutor', 1);
        })->where('location_id', $slug)->get();
            // dd($users);


    }

    elseif($dataType=='skills')
    {


        $tutoruser = DB::table("skill_user")
        ->select(
            "is_tutor",
            'user_id'
        )->groupby('user_id')->where('is_tutor','=',1)->where('skill_id',$slug)->get();
            // dd($tutoruser);
        if(sizeof($tutoruser)>0)
        {
            foreach($tutoruser as $val)
            {

                $users = User::role('student')->where('id',$val->user_id)->where('is_disabled', 'false')->get();

            }
        }
        else
        {
           $users=[];
       }

   }

   else
   {


    $tutoruser = DB::table("skill_user")
    ->select(
        "is_tutor",
        'user_id'
    )->groupby('user_id')->where('is_tutor','=',1)->get();

    foreach($tutoruser as $val)
    {

        $degree=Profile::where('user_id',$val->user_id)->where('degree_id',$slug)->select('user_id')->get();


        if(sizeof($degree)>0)
        {
            foreach ($degree as $key => $value) {

                $users= User::role('student')->where('id',$value->user_id)->where('is_disabled', 'false')->get();
                
            }

        }
        else
        {
          $users=[];  
      } 

  }

}

$is_login = Auth::user();


Log::info("Search Bar");
$categories = array();
$degree = array();
$locations  = array();
$languages  = array();
$categories = Category::all();
$degree = Degree::all();
$locations  = Location::all();
$languages  = Language::all();
$skills     = Skill::all();
$currency   = SiteManagement::getMetaValue('commision');
$symbol     = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
$student_skills = Helper::getstudentLevelList();
$project_length = Helper::getJobDurationList();
        //$keyword = !empty($_GET['s']) ? $_GET['s'] : '';

        //$type = !empty($_GET['type']) ? $_GET['type'] : $search_type;
$search_categories = !empty($_GET['category']) ? $_GET['category'] : array();
$search_degree = !empty($_GET['degree']) ? $_GET['degree'] : array();
$search_locations = !empty($_GET['locations']) ? $_GET['locations'] : array();
$search_skills = !empty($_GET['skills']) ? $_GET['skills'] : array();
$search_project_lengths = !empty($_GET['project_lengths']) ? $_GET['project_lengths'] : array();
$search_languages = !empty($_GET['languages']) ? $_GET['languages'] : array();
$search_hourly_rates = !empty($_GET['hourly_rate']) ? $_GET['hourly_rate'] : array();
$current_date = Carbon::now()->toDateTimeString();

return view(
    'front-end.students.tutor',
    compact(
        'type',
        'users',
        'categories',
        'is_login',
        'locations',
        'languages',
        'skills',
        'project_length',
        'keyword',
        'users_total_records',
        'save_student',
        'symbol',
        'current_date',
        'f_list_meta_title',
        'f_list_meta_desc',
        'show_f_banner',
        'f_inner_banner',
        'enable_package',
        'show_breadcrumbs'
    )
);


}



public function showspecificjob($dataType,$slug)
{
   $is_login = Auth::user();
   if($dataType=='jobs')
   {
    $jobs = job::where('slug', $slug)->get();
}

elseif($dataType=='location')
{
    $jobs = job::where('location_id',$slug)->get();

}
else
{

    $jobs = job::where('faculty',$slug)->get();

}

Log::info("Search Bar");
$categories = array();
$degree = array();
$locations  = array();
$languages  = array();
$categories = Category::all();
$degree = Degree::all();
$locations  = Location::all();
$languages  = Language::all();
$skills     = Skill::all();
$currency   = SiteManagement::getMetaValue('commision');
$symbol     = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
$student_skills = Helper::getstudentLevelList();
$project_length = Helper::getJobDurationList();
        //$keyword = !empty($_GET['s']) ? $_GET['s'] : '';

        //$type = !empty($_GET['type']) ? $_GET['type'] : $search_type;
$search_categories = !empty($_GET['category']) ? $_GET['category'] : array();
$search_degree = !empty($_GET['degree']) ? $_GET['degree'] : array();
$search_locations = !empty($_GET['locations']) ? $_GET['locations'] : array();
$search_skills = !empty($_GET['skills']) ? $_GET['skills'] : array();
$search_project_lengths = !empty($_GET['project_lengths']) ? $_GET['project_lengths'] : array();
$search_languages = !empty($_GET['languages']) ? $_GET['languages'] : array();
$search_employees = !empty($_GET['employees']) ? $_GET['employees'] : array();
$search_hourly_rates = !empty($_GET['hourly_rate']) ? $_GET['hourly_rate'] : array();
$search_freelaner_types = !empty($_GET['freelaner_type']) ? $_GET['freelaner_type'] : array();
$search_english_levels = !empty($_GET['english_level']) ? $_GET['english_level'] : array();
$search_delivery_time = !empty($_GET['delivery_time']) ? $_GET['delivery_time'] : array();
$search_response_time = !empty($_GET['response_time']) ? $_GET['response_time'] : array();
$current_date = Carbon::now()->toDateTimeString();
$currency = SiteManagement::getMetaValue('commision');
$symbol   = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
$inner_page  = SiteManagement::getMetaValue('inner_page_data');

$payment_settings = SiteManagement::getMetaValue('commision');
$enable_package = !empty($payment_settings) && !empty($payment_settings[0]['enable_packages']) ? $payment_settings[0]['enable_packages'] : 'true';
$breadcrumbs_settings = SiteManagement::getMetaValue('show_breadcrumb');
$show_breadcrumbs = !empty($breadcrumbs_settings) ? $breadcrumbs_settings : 'true';              
$is_login = Auth::user();
$Jobs_total_records = Job::count();
$job_list_meta_title = !empty($inner_page) && !empty($inner_page[0]['job_list_meta_title']) ? $inner_page[0]['job_list_meta_title'] : trans('lang.job_listing');
$job_list_meta_desc = !empty($inner_page) && !empty($inner_page[0]['job_list_meta_desc']) ? $inner_page[0]['job_list_meta_desc'] : trans('lang.job_meta_desc');
$show_job_banner = !empty($inner_page) && !empty($inner_page[0]['show_job_banner']) ? $inner_page[0]['show_job_banner'] : 'true';
$job_inner_banner = !empty($inner_page) && !empty($inner_page[0]['job_inner_banner']) ? $inner_page[0]['job_inner_banner'] : null;
$project_settings = !empty(SiteManagement::getMetaValue('project_settings')) ? SiteManagement::getMetaValue('project_settings') : array();
$completed_project_setting = !empty($project_settings) && !empty($project_settings['enable_completed_projects']) ? $project_settings['enable_completed_projects'] : 'true';

return view(
    'front-end.jobs.index2',
    compact(
        'jobs',
        'is_login',
        'categories',
        'degree',
        'locations',
        'languages',
        'is_login',
        'student_skills',
        'project_length',
        'Jobs_total_records',
        'keyword',
        'skills',
        'type',
        'current_date',
        'symbol',
        'job_list_meta_title',
        'job_list_meta_desc',
        'show_job_banner',
        'job_inner_banner',
        'show_breadcrumbs'
    )
);

}


    /**
     * Get filtered list.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFilterlist()
    {
        $json = array();
        $filters = Helper::getSearchFilterList();
        if (!empty($filters)) {
            $json['type'] = 'success';
            $json['result'] = $filters;
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }
    
    
    public function searchfilters(Request $req)
    {
     $type=$req->input('type');
     $s=$req->input('s');
       //echo $type;"<br>";
     $res=[];
     $search_categories = !empty($_GET['category']) ? $_GET['category'] : array();

     $search_degree = !empty($_GET['degree']) ? $_GET['degree'] : array();

     $search_locations = !empty($_GET['locations']) ? $_GET['locations'] : array();

     $search_skills = !empty($_GET['skills']) ? $_GET['skills'] : array();
     $jobsearch=array_merge($search_degree,$search_locations);
   // $studentsearch=array_merge($search_skills,$search_locations);
    //dd($studentsearch);
     if($type=='resource')
     {

       Log::info("Search Bar");
       $categories = array();
       $degree = array();
       $locations  = array();
       $languages  = array();
       $categories = Category::all();
       $degree = Degree::all();
       $locations  = Location::all();
       $languages  = Language::all();
       $skills     = Skill::all();
       $currency   = SiteManagement::getMetaValue('commision');
       $symbol     = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
       $student_skills = Helper::getstudentLevelList();
       $project_length = Helper::getJobDurationList();
       $keyword = !empty($_GET['s']) ? $_GET['s'] : '';

       $type = !empty($_GET['type']) ? $_GET['type'] : $search_type;

       $search_categories = !empty($_GET['category']) ? $_GET['category'] : array();
       $search_degree = !empty($_GET['degree']) ? $_GET['degree'] : array();
       $search_locations = !empty($_GET['locations']) ? $_GET['locations'] : array();
       $search_skills = !empty($_GET['skills']) ? $_GET['skills'] : array();
       $search_project_lengths = !empty($_GET['project_lengths']) ? $_GET['project_lengths'] : array();
       $search_languages = !empty($_GET['languages']) ? $_GET['languages'] : array();
       $search_employees = !empty($_GET['employees']) ? $_GET['employees'] : array();
       $search_hourly_rates = !empty($_GET['hourly_rate']) ? $_GET['hourly_rate'] : array();
       $search_freelaner_types = !empty($_GET['freelaner_type']) ? $_GET['freelaner_type'] : array();
       $search_english_levels = !empty($_GET['english_level']) ? $_GET['english_level'] : array();
       $search_delivery_time = !empty($_GET['delivery_time']) ? $_GET['delivery_time'] : array();
       $search_response_time = !empty($_GET['response_time']) ? $_GET['response_time'] : array();
       $current_date = Carbon::now()->toDateTimeString();
       $currency = SiteManagement::getMetaValue('commision');
       $symbol   = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
       $inner_page  = SiteManagement::getMetaValue('inner_page_data');

       $payment_settings = SiteManagement::getMetaValue('commision');
       $enable_package = !empty($payment_settings) && !empty($payment_settings[0]['enable_packages']) ? $payment_settings[0]['enable_packages'] : 'true';
       $breadcrumbs_settings = SiteManagement::getMetaValue('show_breadcrumb');
       $show_breadcrumbs = !empty($breadcrumbs_settings) ? $breadcrumbs_settings : 'true';

       if(!empty($search_categories))
       {
        foreach ($search_categories as $key => $value) {

          $resources=Resources::where('course_id',$value)->get();
      }
  }
  else
  {
    $resources=Resources::all();
}


return view(
    'front-end.services.index1',
    compact(
        'services_total_records',
        'type',
        'services',
        'resources',
        'is_login',
        'transactions',
        'skills',
        'symbol',
        'keyword',
        'categories',
        'locations',
        'languages',
        'delivery_time',
        'response_time',
        'service_list_meta_title',
        'service_list_meta_desc',
        'show_service_banner',
        'service_inner_banner',
        'show_breadcrumbs'
    )
);
} 


if($type=='job')
{

   Log::info("Search Bar");
   $categories = array();
   $degree = array();
   $locations  = array();
   $languages  = array();
   $categories = Category::all();
   $degree = Degree::all();
   $locations  = Location::all();
   $languages  = Language::all();
   $skills     = Skill::all();
   $currency   = SiteManagement::getMetaValue('commision');
   $symbol     = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
   $student_skills = Helper::getstudentLevelList();
   $project_length = Helper::getJobDurationList();
   $keyword = !empty($_GET['s']) ? $_GET['s'] : '';

   $type = !empty($_GET['type']) ? $_GET['type'] : $search_type;

        // if ($type == 'job') {
        //     if (Helper::getAccessType() == 'both' || Helper::getAccessType() == 'services') {
        //         abort(404);
        //     }
        // }
        // if ($type == 'service') {
        //     if (Helper::getAccessType() == 'both' || Helper::getAccessType() == 'jobs') {
        //         abort(404);
        //     }
        // }
   $search_categories = !empty($_GET['category']) ? $_GET['category'] : array();
   $search_degree = !empty($_GET['degree']) ? $_GET['degree'] : array();
   $search_locations = !empty($_GET['locations']) ? $_GET['locations'] : array();
   $search_skills = !empty($_GET['skills']) ? $_GET['skills'] : array();
   $search_project_lengths = !empty($_GET['project_lengths']) ? $_GET['project_lengths'] : array();
   $search_languages = !empty($_GET['languages']) ? $_GET['languages'] : array();
   $search_employees = !empty($_GET['employees']) ? $_GET['employees'] : array();
   $search_hourly_rates = !empty($_GET['hourly_rate']) ? $_GET['hourly_rate'] : array();
   $search_freelaner_types = !empty($_GET['freelaner_type']) ? $_GET['freelaner_type'] : array();
   $search_english_levels = !empty($_GET['english_level']) ? $_GET['english_level'] : array();
   $search_delivery_time = !empty($_GET['delivery_time']) ? $_GET['delivery_time'] : array();
   $search_response_time = !empty($_GET['response_time']) ? $_GET['response_time'] : array();
   $current_date = Carbon::now()->toDateTimeString();
   $currency = SiteManagement::getMetaValue('commision');
   $symbol   = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
   $inner_page  = SiteManagement::getMetaValue('inner_page_data');

   $payment_settings = SiteManagement::getMetaValue('commision');
   $enable_package = !empty($payment_settings) && !empty($payment_settings[0]['enable_packages']) ? $payment_settings[0]['enable_packages'] : 'true';
   $breadcrumbs_settings = SiteManagement::getMetaValue('show_breadcrumb');
   $show_breadcrumbs = !empty($breadcrumbs_settings) ? $breadcrumbs_settings : 'true';



   if(!empty($req->input('s')))
   {
    $jobs=Job::Where('title', 'like', '%' . $req->input('s') . '%')->get();
}
elseif(!empty($search_degree))
{

                    // $degrees[]=$value;
    $jobs=Job::whereIn('degree_id',$search_degree)->get();

                    // $jobs=Job::where(function ($query) use($value) {
                    // $query->where('degree_id', '=',$value)
                    // ->orWhere('location_id', '=', $search_locations[$key]);
                    // })
                    // ->get();

}
elseif(!empty($search_locations))
{
   $jobs=Job::whereIn('location_id',$search_locations)->get();

}


else
{
    $jobs=Job::all();
}
return view(
    'front-end.jobs.index1',
    compact(
        'jobs',
        'categories',
        'locations',
        'languages',
        'degree',
        'is_login',
        'student_skills',
        'project_length',
        'Jobs_total_records',
        'keyword',
        'skills',
        'type',
        'current_date',
        'symbol',
        'job_list_meta_title',
        'job_list_meta_desc',
        'show_job_banner',
        'job_inner_banner',
        'show_breadcrumbs'
    )
);

}

if($type=='tutor')
{
    $is_login = Auth::user();
    $categories = array();
    $degree = array();
    $locations  = array();
    $languages  = array();
    $categories = Category::all();
    $degree = Degree::all();
    $locations  = Location::all();
    $languages  = Language::all();
    $skills     = Skill::all();
    $currency   = SiteManagement::getMetaValue('commision');
    $symbol     = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
    $student_skills = Helper::getstudentLevelList();
    $project_length = Helper::getJobDurationList();

    $search_categories = !empty($_GET['category']) ? $_GET['category'] : array();
    $search_degree = !empty($_GET['degree']) ? $_GET['degree'] : array();
    $search_locations = !empty($_GET['locations']) ? $_GET['locations'] : array();

    $search_skills = !empty($_GET['skills']) ? $_GET['skills'] : array();
    $search_project_lengths = !empty($_GET['project_lengths']) ? $_GET['project_lengths'] : array();
    $search_languages = !empty($_GET['languages']) ? $_GET['languages'] : array();
    $search_employees = !empty($_GET['employees']) ? $_GET['employees'] : array();
    $search_hourly_rates = !empty($_GET['hourly_rate']) ? $_GET['hourly_rate'] : array();

    $current_date = Carbon::now()->toDateTimeString();
    $currency = SiteManagement::getMetaValue('commision');
    $symbol   = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
    $inner_page  = SiteManagement::getMetaValue('inner_page_data');

    $payment_settings = SiteManagement::getMetaValue('commision');
    $enable_package = !empty($payment_settings) && !empty($payment_settings[0]['enable_packages']) ? $payment_settings[0]['enable_packages'] : 'true';
    $breadcrumbs_settings = SiteManagement::getMetaValue('show_breadcrumb');
    $show_breadcrumbs = !empty($breadcrumbs_settings) ? $breadcrumbs_settings : 'true';


    if(!empty($req->input('s')))
    {

        $users = User::role('student')->Where('first_name', 'like', '%' .$req->input('s') . '%')->where('is_disabled', 'false')->where('is_tutor','=',1)->get();

    }

    elseif(!empty($search_locations))
    {


        $users = User::role('student')->whereIn('location_id',$search_locations)->where('is_disabled', 'false')->where('is_tutor','=',1)->get();

        


    }



    elseif(!empty($search_skills))
    {

        $course=DB::table('skill_user')->select('user_id')->whereIn('skill_id',$search_skills)->where('is_tutor','=',1)->get();

        if(count($course)>0)
        {
         foreach($course as $val)
         {

            $users = User::role('student')->where('id',$val->user_id)->where('is_disabled', 'false')->get();

        }
    }
    else
    {
        $users = User::role('student')->where('is_tutor','=',1)->get();

    }
}


return view(
    'front-end.students.tutor',
    compact(
        'type',
        'date',
        'users',
        'categories',
        'is_login',
        'locations',
        'languages',
        'skills',
        'project_length',
        'keyword',
        'users_total_records',
        'save_student',
        'symbol',
        'current_date',
        'f_list_meta_title',
        'f_list_meta_desc',
        'show_f_banner',
        'f_inner_banner',
        'enable_package',
        'show_breadcrumbs'
    )
);

}

if($type=='student')
{



    $categories = array();
    $degree = array();
    $locations  = array();
    $languages  = array();
    $categories = Category::all();
    $degree = Degree::all();
    $locations  = Location::all();
    $languages  = Language::all();
    $skills     = Skill::all();
    $currency   = SiteManagement::getMetaValue('commision');
    $symbol     = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
    $student_skills = Helper::getstudentLevelList();
    $project_length = Helper::getJobDurationList();
    $keyword = !empty($_GET['s']) ? $_GET['s'] : '';

    $type = !empty($_GET['type']) ? $_GET['type'] : $search_type;
    $search_categories = !empty($_GET['category']) ? $_GET['category'] : array();
    $search_degree = !empty($_GET['degree']) ? $_GET['degree'] : array();
    $search_locations = !empty($_GET['locations']) ? $_GET['locations'] : array();
    $search_skills = !empty($_GET['skills']) ? $_GET['skills'] : array();
    $search_project_lengths = !empty($_GET['project_lengths']) ? $_GET['project_lengths'] : array();
    $search_languages = !empty($_GET['languages']) ? $_GET['languages'] : array();
    $search_employees = !empty($_GET['employees']) ? $_GET['employees'] : array();
    $search_hourly_rates = !empty($_GET['hourly_rate']) ? $_GET['hourly_rate'] : array();
    $search_freelaner_types = !empty($_GET['freelaner_type']) ? $_GET['freelaner_type'] : array();
    $search_english_levels = !empty($_GET['english_level']) ? $_GET['english_level'] : array();
    $search_delivery_time = !empty($_GET['delivery_time']) ? $_GET['delivery_time'] : array();
    $search_response_time = !empty($_GET['response_time']) ? $_GET['response_time'] : array();
    $current_date = Carbon::now()->toDateTimeString();
    $currency = SiteManagement::getMetaValue('commision');
    $symbol   = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
    $inner_page  = SiteManagement::getMetaValue('inner_page_data');

    $payment_settings = SiteManagement::getMetaValue('commision');
    $enable_package = !empty($payment_settings) && !empty($payment_settings[0]['enable_packages']) ? $payment_settings[0]['enable_packages'] : 'true';
    $breadcrumbs_settings = SiteManagement::getMetaValue('show_breadcrumb');
    $show_breadcrumbs = !empty($breadcrumbs_settings) ? $breadcrumbs_settings : 'true';


    Log::info("Intention is: ONLY LoggedIn students can see students");
    $is_login = Auth::user();
    Log::info(Auth::user());
    $f_list_meta_title = !empty($inner_page) && !empty($inner_page[0]['f_list_meta_title']) ? $inner_page[0]['f_list_meta_title'] : trans('lang.student_listing');
    $f_list_meta_desc = !empty($inner_page) && !empty($inner_page[0]['f_list_meta_desc']) ? $inner_page[0]['f_list_meta_desc'] : trans('lang.student_meta_desc');
    $show_f_banner = !empty($inner_page) && !empty($inner_page[0]['show_f_banner']) ? $inner_page[0]['show_f_banner'] : 'true';

    $f_inner_banner = !empty($inner_page) && !empty($inner_page[0]['f_inner_banner']) ? $inner_page[0]['f_inner_banner'] : null;





    $date=date("Y-m-d");
    if(!empty($req->input('s')))
    {

        $users = User::role('student')->Where('first_name', 'like', '%' .$req->input('s') . '%')->where('is_disabled', 'false')->get();

    }


    elseif(!empty($search_locations))
    {


        $users = User::role('student')->whereIn('location_id',$search_locations)->where('is_disabled', 'false')->get();
        

    }


    elseif(!empty($search_skills))
    {

        $course=DB::table('skill_user')->select('user_id')->whereIn('skill_id',$search_skills)->get();

        if(count($course)>0)
        {
         foreach($course as $val)
         {

            $users = User::role('student')->where('id',$val->user_id)->where('is_disabled', 'false')->get();

        }
    }
    else
    {
       $users = [];
   }

}
return view(
    'front-end.students.index2',
    compact(
        'type',
        'date',
        'users',
        'categories',
        'is_login',
        'locations',
        'languages',
        'skills',
        'project_length',
        'keyword',
        'users_total_records',
        'save_student',
        'symbol',
        'current_date',
        'f_list_meta_title',
        'f_list_meta_desc',
        'show_f_banner',
        'f_inner_banner',
        'enable_package',
        'show_breadcrumbs'
    )
);

}

}


public function getfacultyResult($id='')
{

    $categories = Category::all();
    $locations  = Location::all();
    $skills     = Skill::all();
    $is_login = Auth::user();
    $users = User::role('student')->where('faculty',$id)->where('is_disabled', 'false')->get();
    return view(
        'front-end.students.facultystudent',
        compact(
            'users',
            'is_login',
            'type',
            'date',
            'skills',
            'categories',
            'locations',
            'users_total_records'

        )
    );

}


public function facultyall()
{

    $categories = Category::all();
    $locations  = Location::all();
    $skills     = Skill::all();
    $is_login = Auth::user();
    $is_login = Auth::user();
    $users = User::role('student')->whereNotNull('faculty')->where('is_disabled', 'false')->get();
    return view(
        'front-end.students.facultystudentall',
        compact(
           'users',
           'is_login',
           'type',
           'date',
           'skills',
           'categories',
           'locations',
           'users_total_records'
       )
    );

}



    /**
     * Get searchable data.
     *
     * @param mixed $request request->attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function getSearchableData(Request $request)
    {

        $json = array();
        if (!empty($request['type'])) {
            $searchables = Helper::getSearchableList($request['type']);
            log::info("Searched data: ", $searchables);
            if (!empty($searchables)) {
                $json['type'] = 'success';
                $json['searchables'] = $searchables;
                return $json;
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.something_wrong');
                return $json;
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }

    /**
     * Get search result.
     *
     * @param string $search_type search type
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getSearchResult($search_type = "")
    {

        Log::info("Search Bar");
        $categories = array();
        $faculty = array();
        $faculty =DB::table('faculties')->select('id','title','slug')->get();
        $degree = array();
        $locations  = array();
        $languages  = array();
        $categories = Category::all();
        $degree = Degree::all();
        $locations  = Location::all();
        $languages  = Language::all();
        $skills     = Skill::all();
        $currency   = SiteManagement::getMetaValue('commision');
        $symbol     = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
        $student_skills = Helper::getstudentLevelList();
        $project_length = Helper::getJobDurationList();
        $keyword = !empty($_GET['s']) ? $_GET['s'] : '';

        $type = !empty($_GET['type']) ? $_GET['type'] : $search_type;

        // if ($type == 'job') {
        //     if (Helper::getAccessType() == 'both' || Helper::getAccessType() == 'services') {
        //         abort(404);
        //     }
        // }
        // if ($type == 'service') {
        //     if (Helper::getAccessType() == 'both' || Helper::getAccessType() == 'jobs') {
        //         abort(404);
        //     }
        // }
        $search_categories = !empty($_GET['category']) ? $_GET['category'] : array();
        $search_degree = !empty($_GET['degree']) ? $_GET['degree'] : array();
        $search_locations = !empty($_GET['locations']) ? $_GET['locations'] : array();
        $search_skills = !empty($_GET['skills']) ? $_GET['skills'] : array();
        $search_project_lengths = !empty($_GET['project_lengths']) ? $_GET['project_lengths'] : array();
        $search_languages = !empty($_GET['languages']) ? $_GET['languages'] : array();
        $search_employees = !empty($_GET['employees']) ? $_GET['employees'] : array();
        $search_hourly_rates = !empty($_GET['hourly_rate']) ? $_GET['hourly_rate'] : array();
        $search_freelaner_types = !empty($_GET['freelaner_type']) ? $_GET['freelaner_type'] : array();
        $search_english_levels = !empty($_GET['english_level']) ? $_GET['english_level'] : array();
        $search_delivery_time = !empty($_GET['delivery_time']) ? $_GET['delivery_time'] : array();
        $search_response_time = !empty($_GET['response_time']) ? $_GET['response_time'] : array();
        $current_date = Carbon::now()->toDateTimeString();
        $currency = SiteManagement::getMetaValue('commision');
        $symbol   = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
        $inner_page  = SiteManagement::getMetaValue('inner_page_data');

        $payment_settings = SiteManagement::getMetaValue('commision');
        $enable_package = !empty($payment_settings) && !empty($payment_settings[0]['enable_packages']) ? $payment_settings[0]['enable_packages'] : 'true';
        $breadcrumbs_settings = SiteManagement::getMetaValue('show_breadcrumb');
        $show_breadcrumbs = !empty($breadcrumbs_settings) ? $breadcrumbs_settings : 'true';
        if (!empty($_GET['type'])) {
            if ($type == 'employer' || $type == 'student') {

                $users_total_records = User::count();
                $search =  User::getSearchResult(
                    $type,
                    $keyword,
                    $search_locations,
                    $search_employees,
                    $search_skills,
                    $search_hourly_rates,
                    $search_freelaner_types,
                    $search_english_levels,
                    $search_languages
                );
                $users = count($search['users']) > 0 ? $search['users'] : '';
                $save_student = !empty(auth()->user()->profile->saved_student) ?
                unserialize(auth()->user()->profile->saved_student) : array();
                $save_employer = !empty(auth()->user()->profile->saved_employers) ?
                unserialize(auth()->user()->profile->saved_employers) : array();
                if ($type === 'employer') {
                    $emp_list_meta_title = !empty($inner_page) && !empty($inner_page[0]['emp_list_meta_title']) ? $inner_page[0]['emp_list_meta_title'] : trans('lang.emp_listing');
                    $emp_list_meta_desc = !empty($inner_page) && !empty($inner_page[0]['emp_list_meta_desc']) ? $inner_page[0]['emp_list_meta_desc'] : trans('lang.emp_meta_desc');
                    $show_emp_banner = !empty($inner_page) && !empty($inner_page[0]['show_emp_banner']) ? $inner_page[0]['show_emp_banner'] : 'true';
                    $e_inner_banner = !empty($inner_page) && !empty($inner_page[0]['e_inner_banner']) ? $inner_page[0]['e_inner_banner'] : null;
                    if (file_exists(resource_path('views/extend/front-end/employers/index.blade.php'))) {
                        return view(
                            'extend.front-end.employers.index',
                            compact(
                                'users',
                                'locations',
                                'languages',
                                'student_skills',
                                'project_length',
                                'keyword',
                                'type',
                                'users_total_records',
                                'save_employer',
                                'current_date',
                                'emp_list_meta_title',
                                'emp_list_meta_desc',
                                'show_emp_banner',
                                'e_inner_banner',
                                'enable_package',
                                'show_breadcrumbs'
                            )
                        );
                    } else {
                        return view(
                            'front-end.employers.index',
                            compact(
                                'users',
                                'locations',
                                'languages',
                                'student_skills',
                                'project_length',
                                'keyword',
                                'type',
                                'users_total_records',
                                'save_employer',
                                'current_date',
                                'emp_list_meta_title',
                                'emp_list_meta_desc',
                                'show_emp_banner',
                                'e_inner_banner',
                                'enable_package',
                                'show_breadcrumbs'
                            )
                        );
                    }
                } elseif ($type === 'student') {

                    Log::info("Intention is: ONLY LoggedIn students can see students");
                    $is_login = Auth::user();
                    Log::info(Auth::user());
                    $f_list_meta_title = !empty($inner_page) && !empty($inner_page[0]['f_list_meta_title']) ? $inner_page[0]['f_list_meta_title'] : trans('lang.student_listing');
                    $f_list_meta_desc = !empty($inner_page) && !empty($inner_page[0]['f_list_meta_desc']) ? $inner_page[0]['f_list_meta_desc'] : trans('lang.student_meta_desc');
                    $show_f_banner = !empty($inner_page) && !empty($inner_page[0]['show_f_banner']) ? $inner_page[0]['show_f_banner'] : 'true';
                    
                    $f_inner_banner = !empty($inner_page) && !empty($inner_page[0]['f_inner_banner']) ? $inner_page[0]['f_inner_banner'] : null;
                    if (file_exists(resource_path('views/extend/front-end/students/index.blade.php'))) {
                        return view(
                            'extend.front-end.students.index',
                            compact(
                                'type',
                                'users',
                                'categories',
                                'faculty',
                                'locations',
                                'languages',
                                'skills',
                                'is_login',
                                'project_length',
                                'keyword',
                                'users_total_records',
                                'save_student',
                                'symbol',
                                'current_date',
                                'f_list_meta_title',
                                'f_list_meta_desc',
                                'show_f_banner',
                                'f_inner_banner',
                                'enable_package',
                                'show_breadcrumbs'
                            )
                        );
                    } else {
                        return view(
                            'front-end.students.index',
                            compact(
                                'type',
                                'users',
                                'categories',
                                'is_login',
                                'faculty',
                                'locations',
                                'languages',
                                'skills',
                                'project_length',
                                'keyword',
                                'users_total_records',
                                'save_student',
                                'symbol',
                                'current_date',
                                'f_list_meta_title',
                                'f_list_meta_desc',
                                'show_f_banner',
                                'f_inner_banner',
                                'enable_package',
                                'show_breadcrumbs'
                            )
                        );
                    }
                } else {
                    abort(404);
                }
            } elseif ($type == 'resource') {

            //service
                Log::info("Intention is: ONLY LoggedIn students can see resources");
                $is_login = Auth::user();
                Log::info(Auth::user());
                $service_list_meta_title = !empty($inner_page) && !empty($inner_page[0]['service_list_meta_title']) ? $inner_page[0]['service_list_meta_title'] : trans('lang.service_listing');
                $service_list_meta_desc = !empty($inner_page) && !empty($inner_page[0]['service_list_meta_desc']) ? $inner_page[0]['service_list_meta_desc'] : trans('lang.service_meta_desc');
                $show_service_banner = !empty($inner_page) && !empty($inner_page[0]['show_service_banner']) ? $inner_page[0]['show_service_banner'] : 'true';
                $service_inner_banner = !empty($inner_page) && !empty($inner_page[0]['service_inner_banner']) ? $inner_page[0]['service_inner_banner'] : null;

                // $services= Service::all();
                $delivery_time = DeliveryTime::all();
                $response_time = ResponseTime::all();
                $resources = Resources::all();
                $transactions = Transactions::all();
                Log::info("resources");
                Log::info($resources);
                $services_total_records = Service::count();
                $results = Resources::getSearchResult(
                    $keyword,
                    $search_degree,
                    $search_categories,
                    $search_locations,
                    $search_languages,
                    $search_delivery_time,
                    $search_response_time
                );
                Log::info("results: ");
                Log::info($results['services']);
                $resources = $results['services'];

                if (file_exists(resource_path('views/extend/front-end/services/index.blade.php'))) {
                    return view(
                        'extend.front-end.services.index',
                        compact(
                            'services_total_records',
                            'type',
                            'services',
                            'resources',
                            'is_login',
                            'skills',
                            'symbol',
                            'keyword',
                            'categories',
                            'locations',
                            'languages',
                            'delivery_time',
                            'response_time',
                            'service_list_meta_title',
                            'service_list_meta_desc',
                            'show_service_banner',
                            'service_inner_banner',
                            'show_breadcrumbs'
                        )
                    );
                } else {
                    return view(
                        'front-end.services.index',
                        compact(
                            'services_total_records',
                            'type',
                            'services',
                            'resources',
                            'is_login',
                            'transactions',
                            'skills',
                            'symbol',
                            'keyword',
                            'categories',
                            'locations',
                            'languages',
                            'delivery_time',
                            'response_time',
                            'service_list_meta_title',
                            'service_list_meta_desc',
                            'show_service_banner',
                            'service_inner_banner',
                            'show_breadcrumbs'
                        )
                    );
                }
            } else {

                Log::info("Intention is: ONLY LoggedIn students can see resources");
                $is_login = Auth::user();
                Log::info(Auth::user());
//              $ke = Degree::where('title', 'LIKE', '%' . $keyword . '%')->get();

                $Jobs_total_records = Job::count();
                $job_list_meta_title = !empty($inner_page) && !empty($inner_page[0]['job_list_meta_title']) ? $inner_page[0]['job_list_meta_title'] : trans('lang.job_listing');
                $job_list_meta_desc = !empty($inner_page) && !empty($inner_page[0]['job_list_meta_desc']) ? $inner_page[0]['job_list_meta_desc'] : trans('lang.job_meta_desc');
                $show_job_banner = !empty($inner_page) && !empty($inner_page[0]['show_job_banner']) ? $inner_page[0]['show_job_banner'] : 'true';
                $job_inner_banner = !empty($inner_page) && !empty($inner_page[0]['job_inner_banner']) ? $inner_page[0]['job_inner_banner'] : null;
                $project_settings = !empty(SiteManagement::getMetaValue('project_settings')) ? SiteManagement::getMetaValue('project_settings') : array();
                $completed_project_setting = !empty($project_settings) && !empty($project_settings['enable_completed_projects']) ? $project_settings['enable_completed_projects'] : 'true';
                $results = Job::getSearchResult(
                    $keyword,
                    $search_categories,
                    $search_degree,
                    $search_locations,
                    $search_skills,
                    $search_project_lengths,
                    $search_languages,
                    $completed_project_setting
                );
                $jobs = $results['jobs'];

                if (!empty($jobs)) {

                    if (file_exists(resource_path('views/extend/front-end/jobs/index.blade.php'))) {
                        return view(
                            'extend.front-end.jobs.index',
                            compact(
                                'jobs',
                                'categories',
                                'locations',
                                'languages',
                                'degree',
                                'is_login',
                                'student_skills',
                                'project_length',
                                'Jobs_total_records',
                                'keyword',
                                'skills',
                                'type',
                                'current_date',
                                'symbol',
                                'job_list_meta_title',
                                'job_list_meta_desc',
                                'show_job_banner',
                                'job_inner_banner',
                                'show_breadcrumbs'
                            )
                        );
                    } else {
                        return view(
                            'front-end.jobs.index',
                            compact(
                                'jobs',
                                'categories',
                                'degree',
                                'locations',
                                'languages',
                                'is_login',
                                'student_skills',
                                'project_length',
                                'Jobs_total_records',
                                'keyword',
                                'skills',
                                'type',
                                'current_date',
                                'symbol',
                                'job_list_meta_title',
                                'job_list_meta_desc',
                                'show_job_banner',
                                'job_inner_banner',
                                'show_breadcrumbs'
                            )
                        );
                    }
                }
            }
        } else {
            abort(404);
        }
    }

    /**
     * Get Pass Reset Form
     *
     * @param mixed $verification_code verification_code
     *
     * @access public
     *
     * @return View
     */
    public function resetPasswordView($verification_code)
    {
        dd($verification_code);
        if (!empty($verification_code)) {
            session()->put(['verification_code' => $verification_code]);
            if (file_exists(resource_path('views/extend/front-end/reset-password.blade.php'))) {
                return View('extend.front-end.reset-password');
            } else {
                return View('front-end.reset-password');
            }
        } else {
            abort(404);
        }
    }

    /**
     * Reset user password.
     *
     * @param mixed $request req->attr
     *
     * @access public
     *
     * @return View
     */
    public function resetUserPassword(Request $request)
    {
        if (Session::has('verification_code')) {
            $verification_code = Session::get('verification_code');
            if (!empty($request)) {
                $this->validate(
                    $request,
                    [
                        'new_password' => 'required',
                        'confirm_password' => 'required',
                    ]
                );
                $user_id = User::select('verification_code', 'id')
                ->where('verification_code', $verification_code)
                ->pluck('id')->first();
                $user = User::find($user_id);
                if ($request->new_password === $request->confirm_password) {
                    if ($verification_code === $user->verification_code) {
                        $user->password = Hash::make($request->confirm_password);
                        $user->verification_code = null;
                        $user->save();
                        Auth::logout();
                        session()->forget('verification_code');
                        return Redirect::to('/');
                    } else {
                        Session::flash('error', trans('lang.invalid_verify_code'));
                        return Redirect::back();
                    }
                } else {
                    Session::flash('error', trans('lang.pass_mismatched'));
                    return Redirect::back();
                }
            } else {
                Session::flash('error', trans('lang.something_wrong'));
                return Redirect::back();
            }
        } else {
            Session::flash('error', trans('lang.invalid_verify_code'));
            return Redirect::back();
        }
    }

    /**
     * Check user authorization.
     *
     * @access public
     *
     * @return View
     */
    public function checkProposalAuth()
    {
        $json = array();
        if (Auth::user() && Auth::user()->getRoleNames()->first() === 'student') {
            $json['auth'] = true;
            return $json;
        } else {
            $json['auth'] = false;
            $json['message'] = trans('lang.not_authorize');
            return $json;
        }
    }

    /**
     * Check user authorization.
     *
     * @access public
     *
     * @return View
     */
    public function checkServiceAuth()
    {
        $json = array();
        if (Auth::user() && Auth::user()->getRoleNames()->first() === 'employer') {
            $json['auth'] = true;
            return $json;
        } else {
            $json['auth'] = false;
            $json['message'] = trans('lang.not_authorize');
            return $json;
        }
    }
    
    public function getuserss()
    {
        $json = array();
        $userid=Auth::user()->id;
        $roles = DB::table('model_has_roles')->select('role_id')->where('model_id',$userid)->get();
        $json['type'] = 'success';
        $json['roles'] = $roles[0];
        return $json;
    }
    

    /**
     * Check user authorization.
     *
     * @access public
     *
     * @return unserialize array
     */
    public function getstudentExperience(Request $request)
    {
        $json = array();
        $id = $request['id'];
        $student = User::find($id);
        if (!empty($student)) {
            $json['type'] = 'success';
            $json['experience'] = unserialize($student->profile->experience);
            return $json;
        } else {
            $json['type'] = 'error';
            return $json;
        }
    }

    /**
     * Check user authorization.
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getstudentEducation(Request $request)
    {
        $json = array();
        $id = $request['id'];
        $student = User::find($id);
        if (!empty($student)) {
            $json['type'] = 'success';
            $json['education'] = unserialize($student->profile->education);
            return $json;
        } else {
            $json['type'] = 'error';
            return $json;
        }
    }

    /**
     * Check user authorization.
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getstudentService(Request $request)
    {
        $json = array();
        $id = $request['id'];
        $student = User::find($id);
        if (!empty($student)) {
            $json['type'] = 'success';
            $json['user'] = $student;
            $json['services'] = Helper::getUnserializeData($student->services);
            return $json;
        } else {
            $json['type'] = 'error';
            return $json;
        }
    }

    /**
     * get video
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getVideo($video)
    {
        $json = array();
        if (!empty($video)) {
            $width 	= 367;
            $height = 206;
            $url = parse_url($video);
            if (isset($url['host']) && ($url['host'] == 'vimeo.com' || $url['host'] == 'player.vimeo.com')) {
                $content_exp = explode("/", $url);
                $content_vimo = array_pop($content_exp);
                $json['video_content'] = '<iframe width="' . intval($width) . '" height="' . intval($height) . '" src="https://player.vimeo.com/video/' . $content_vimo . '" 
                ></iframe>';
            } else {
                $json['video'] = '<iframe width="'.$width.'" height="'.$height.'" src="https://www.youtube.com/embed/'.str_replace("v=", '', $url['query']).'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
            }
            $json['type'] = 'success';
            return $json;
        } else {
            $json['type'] = 'error';
            return $json;
        }
    }

    /**
     * Get article data
     *
     * @return \Illuminate\Http\Response
     */
    public function getArticles()
    {
        $json = array();
        $articles = Article::get()->toArray();
        $aticle_list = array();
        if (!empty($articles)) {
            foreach ($articles as $key => $article) {
                $article_obj = Article::find($article['id']);
                $aticle_list[$key]['id'] = $article['id'];
                $aticle_list[$key]['title'] = $article['title'];
                $aticle_list[$key]['slug'] = $article['slug'];
                $aticle_list[$key]['banner'] = asset(Helper::getImage('uploads/articles', $article['banner'], 'small-', 'small-default-article.png'));
                $aticle_list[$key]['published_date'] = $article['created_at'];
                $aticle_list[$key]['description'] = $article['description'];
                $aticle_list[$key]['name'] = Helper::getUserName($article['user_id']);
                $aticle_list[$key]['image'] = asset(Helper::getProfileImage($article['user_id']));
                if (!empty($article_obj->categories) && $article_obj->categories->count() > 0) {
                    foreach ($article_obj->categories as $cat_key => $category) {
                        $aticle_list[$key]['cat'][$cat_key]['title'] = $category->title;
                        $aticle_list[$key]['cat'][$cat_key]['slug'] = $category->slug;
                    }
                }
            }
            if (!empty($aticle_list)) {
                $json['type'] = 'success';
                $json['articles'] = $aticle_list;
                return $json;
            } else {
                $json['type'] = 'error';
                return $json;
            }
        } else {
            $json['type'] = 'error';
            return $json;
        }
    }
    
    
    public function getcourse($content)
    {
        $json = array();
        $course_list = array();
        $course =  DB::table('skills')->where('title', 'like', '%' . $content . '%')->limit(15)->get()->toArray();
        foreach ($course as $key => $value) {   
            $course_list[$key]['id'] = $value->id;    
            $course_list[$key]['title'] = $value->title;    
            $course_list[$key]['slug'] = $value->slug;    

        }
        if (!empty($course_list)) {
            $json['type'] = 'success';
            $json['courses'] = $course_list;
            return $json;
                        //dd($json);
        } else {
            $json['type'] = 'error';
            return $json;
        }

    }


    public function getdegree($content)
    {

        $json = array();
        $course_list = array();
        $course =  DB::table('degrees')->where('title', 'like', '%' . $content . '%')->limit(15)->get()->toArray();
        foreach ($course as $key => $value) {
            $course_list[$key]['id'] = $value->id;    
            $course_list[$key]['title'] = $value->title;    
            $course_list[$key]['slug'] = $value->slug;    
        }
        if (!empty($course_list)) {
            $json['type'] = 'success';
            $json['courses'] = $course_list;
            return $json;

        } else {
            $json['type'] = 'error';
            return $json;
        }

    }




    public function getlocation($content)
    {

        $json = array();
        $course_list = array();
        $course =  DB::table('locations')->where('title', 'like', '%' . $content . '%')->limit(15)->get()->toArray();
        foreach ($course as $key => $value) {
            $course_list[$key]['id'] = $value->id;    
            $course_list[$key]['title'] = $value->title;    
            $course_list[$key]['slug'] = $value->slug;    

        }
        if (!empty($course_list)) {
            $json['type'] = 'success';
            $json['courses'] = $course_list;
            return $json;

        } else {
            $json['type'] = 'error';
            return $json;
        }

    }

    public function postResourcefeed(Request $request)
    {
        $element=$request->element;
        $user_id=Auth::user()->id;
        $resource=new Resourcefeedback();
        $resource->feedback=$element;
        $resource->user_id=$user_id;
        $resource->save();
    }

    
    
    
}
