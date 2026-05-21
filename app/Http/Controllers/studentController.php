<?php

/**
 * Class studentController.
 *
 * @category Worketic
 *
 * @package Worketic
 * @author  Amentotech <theamentotech@gmail.com>
 * @license http://www.amentotech.com Amentotech
 * @link    http://www.amentotech.com
 */
namespace App\Http\Controllers;
require 'C:/xampp/htdocs/chuta/vendor/autoload.php';
use App\student;
use Illuminate\Http\Request;
use App\Helper;
use App\Location;
use App\Skill;
use App\Degree;
use App\Rooms;
use App\MeetingInvoice;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Twilio\Rest\Client;
use Session;
use Illuminate\Support\Facades\Log;
use App\Profile;
use App\Meeting;
use App\Resources;
use App\Transactions;
use Auth;
use File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\User;
use App\Offer;
use App\Proposal;
use App\Job;
use DB;
use App\Package;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use ValidateRequests;
use App\Item;
use Carbon\Carbon;
use App\Message;
use App\Payout;
use App\SiteManagement;
use App\Service;
use App\Review;
use App\Rating_resource;
use App\Events\callingFriend;

/**
 * Class studentController
 *
 */
class studentController extends Controller
{
    /**
     * Defining scope of the variable
     *
     * @access protected
     * @var    array $student
     */
    protected $student;

    /**
     * Create a new controller instance.
     *
     * @param instance $student instance
     *
     * @return void
     */
    public function __construct(Profile $student, Payout $payout)
    {
        $this->student = $student;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function index()
    {
        $locations = Location::pluck('title', 'id');
        //$skills = Skill::pluck('title', 'id');
        $profile = $this->student::where('user_id', Auth::user()->id)
            ->get()->first();
        $gender = !empty($profile->gender) ? $profile->gender : '';
        //$hourly_rate = !empty($profile->hourly_rate) ? $profile->hourly_rate : '';
        $tagline = !empty($profile->tagline) ? $profile->tagline : '';
        $description = !empty($profile->description) ? $profile->description : '';
        $address = !empty($profile->address) ? $profile->address : '';
        $longitude = !empty($profile->longitude) ? $profile->longitude : '';
        $latitude = !empty($profile->latitude) ? $profile->latitude : '';
        $banner = !empty($profile->banner) ? $profile->banner : '';
        $avater = !empty($profile->avater) ? $profile->avater : '';
        $role_id =  Helper::getRoleByUserID(Auth::user()->id);
        $faculty =Helper::getfacultyList(); 
        $degree = DB::table('degrees')->get();
        $packages = DB::table('items')->where('subscriber', Auth::user()->id)->count();
        $package_options = Package::select('options')->where('role_id', $role_id)->first();
        $options = !empty($package_options) ? unserialize($package_options['options']) : array();
        $videos = !empty($profile->videos) ? Helper::getUnserializeData($profile->videos) : '';
        if (file_exists(resource_path('views/extend/back-end/student/profile-settings/personal-detail/index.blade.php'))) {
            return view(
                'extend.back-end.student.profile-settings.personal-detail.index',
                compact(
                    'videos',
                    'locations',
                    'skills',
                    'profile',
                    'gender',
                    'faculty',
                    'degree',
                //    'hourly_rate',
                    'tagline',
                    'description',
                    'banner',
                    'address',
                    'longitude',
                    'latitude',
                    'avater',
                    'role_id',
                    'options'
                )
            );
        } else {
            return view(
                'back-end.student.profile-settings.personal-detail.index',
                compact(
                    'videos',
                    'locations',
                    'skills',
                    'profile',
                    'gender',
                    'faculty',
                    'degree',
                  //  'hourly_rate',
                    'tagline',
                    'description',
                    'banner',
                    'address',
                    'longitude',
                    'latitude',
                    'avater',
                    'role_id',
                    'options'
                )
            );
        }
    }

    /**
     * Upload Image to temporary folder.
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadTempImage(Request $request)
    {
        $path = Helper::PublicPath() . '/uploads/users/temp/';
        if (!empty($request['hidden_avater_image'])) {
            $profile_image = $request['hidden_avater_image'];
            $image_size = array(
                'small' => array(
                    'width' => 36,
                    'height' => 36,
                ),
                'medium-small' => array(
                    'width' => 60,
                    'height' => 60,
                ),
                'medium' => array(
                    'width' => 100,
                    'height' => 100,
                ),
                'listing' => array(
                    'width' => 255,
                    'height' => 255,
                ),
            );
            // return Helper::uploadTempImage($path, $profile_image);
            return Helper::uploadTempImageWithSize($path, $profile_image, '', $image_size);
        } elseif (!empty($request['hidden_banner_image'])) {
            $profile_image = $request['hidden_banner_image'];
            return Helper::uploadTempImage($path, $profile_image);
        } elseif (!empty($request['project_img'])) {
            $profile_image = $request['project_img'];
            return Helper::uploadTempImage($path, $profile_image);
        } elseif (!empty($request['award_img'])) {
            $profile_image = $request['award_img'];
            return Helper::uploadTempImage($path, $profile_image);
        }
    }




    /**
     * Store profile settings.
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function storeProfileSettings(Request $request)
    {
        $server = Helper::worketicIsDemoSiteAjax();
        if (!empty($server)) {
            $response['type'] = 'error';
            $response['message'] = $server->getData()->message;
            return $response;
        }
        $json = array();
        // Note: Commented on 18th May to store data in skills table from 'Courses' tab
        // $this->validate(
        //     $request,
        //     [
        //         'first_name'    => 'required',
        //         'last_name'    => 'required',
        //         'gender'    => 'required',
        //     ]
        // );
        if (!empty($request['latitude']) || !empty($request['longitude'])) {
            $this->validate(
                $request,
                [
                    'latitude' => ['regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
                    'longitude' => ['regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
                ]
            ); 
        }
        if (Auth::user()) {
            $role_id = Helper::getRoleByUserID(Auth::user()->id);
            $packages = DB::table('items')->where('subscriber', Auth::user()->id)->count();
            $package_options = Package::select('options')->where('role_id', $role_id)->first();
            $options = !empty($package_options) ? unserialize($package_options['options']) : array();
            $skills = !empty($options) ? $options['no_of_skills'] : array();
            $payment_settings = SiteManagement::getMetaValue('commision');
            $package_status = '';
            if (empty($payment_settings)) {
                $package_status = 'true';
            } else {
                $package_status =!empty($payment_settings[0]['enable_packages']) ? $payment_settings[0]['enable_packages'] : 'true';
            }
            if ($package_status == 'true') {
                if ($packages > 0) {
                    if (!empty($request['skills']) && count($request['skills']) > $skills) {
                        $json['type'] = 'error';
                        $json['message'] = trans('lang.cannot_add_morethan') . '' . $options['no_of_skills'] . ' ' . trans('lang.skills');
                        return $json;
                    } else {
                        $profile =  $this->student->storeProfile($request, Auth::user()->id);
                        if ($profile = 'success') {
                            $json['type'] = 'success';
                            $json['message'] = '';
                            return $json;
                        }
                    }
                } else {
                    $json['type'] = 'error';
                    $json['message'] = trans('lang.update_pkg');
                    return $json;
                }
            } else {
                $profile =  $this->student->storeProfile($request, Auth::user()->id);
                if ($profile = 'success') {
                    $json['type'] = 'success';
                    $json['message'] = '';
                    return $json;
                }
            }
            Session::flash('message', trans('lang.update_profile'));
            return Redirect::back();
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.not_authorize');
            return $json;
        }
    }

    /**
     * Get student skills.
     *
     * @return \Illuminate\Http\Response
     */
   public function getstudentSkills()
    {
        $json = array();
        if (Auth::user()) {
           $skills = User::find(Auth::user()->id)->skills()
           ->orderBy('title')->get()->toArray();   
            Log::info($skills);
            if (!empty($skills)) {
                $json['type'] = 'success';
                $json['student_skills'] = $skills;
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

    public function deletestudentSkills($id)
    {

        $json = array();
        if (Auth::user()) {
          $skills=DB::table('skill_user')->where('skill_id', $id)->delete();
   
            Log::info($skills);
            if (!empty($skills)) {
                $json['type'] = 'success';
                $json['student_skills'] = $skills;
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
    
    
      public function deletestudentResource($id)
    {

        $json = array();
        if (Auth::user()) {
          $skills=DB::table('resources')->where('id', $id)->delete();
   
            Log::info($skills);
            if (!empty($skills)) {
                $json['type'] = 'success';
                $json['student_skills'] = $skills;
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


    /**
     * Get student skills.
     *
     * @return \Illuminate\Http\Response
     */
    public function getstudentResources()
    {
        $json = array();
        if (Auth::user()) {
            $user_id = Auth::user()->id;
            $resources = Resources::getStudentResources($user_id);
            Log::info("My resources: ");
            Log::info($resources);
            $skills = User::find(Auth::user()->id)->skills()
                ->orderBy('title')->get()->toArray();
            Log::info($resources);
            if (!empty($resources)) {
                $json['type'] = 'success';
                $json['student_resources'] = $resources;
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

        /**
     * Get student skills.
     *
     * @return \Illuminate\Http\Response
     */
    public function getpurchasedResources()
    {
        $json = array();
        if (Auth::user()) {
            $user_id = Auth::user()->id;
           
            $resources = Resources::getStudentResourcesWithTransactionDetails($user_id);// Resources::getStudentResources($user_id);
           
            //$purchased_resources = [];
            // $transactions=Transactions::where([
            //     ['buyer_id', '=', $user_id],
            // ])->get()->toArray();
            
               //dd($transactions);
            // if($transactions){
            //     for( $i=0; $i < sizeof($transactions); $i++ ){
            //         $one_purchased_resource = Resources::where([
            //             ['slug', '=', $transactions[$i]['resource_slug']]
            //         ])->first();
                   
            //         array_push($purchased_resources, $one_purchased_resource);
            //     }
            // }
          //  dd($purchased_resources);
            Log::info("My resources: ");
            Log::info($resources);
            $skills = User::find(Auth::user()->id)->skills()
                ->orderBy('title')->get()->toArray();
            Log::info($resources);
            if (!empty($resources)) {
                $json['type'] = 'success';
                //$json['student_resources'] = $resources;
                $json['purchased_resources'] = $resources;
               // dd($json['purchased_resources']);
               
                //$json['transactions'] = $transactions;
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

    /**
     * Get meeting rooms.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMeetingRooms()
    {
       
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $twilio = new Client($sid, $token);

        $user_id = Auth::user()->id;
        $all_meetings_of_this_user = Meeting::getMeetingsSent($user_id);

        $all_meetings_of_this_user = Meeting::select('room_name')->where('first_person', $user_id)->get()->toArray();
        Log::info("all_meetings_of_this_user");
        Log::info($all_meetings_of_this_user);

        $array_of_user_rooms = [];
        foreach ($all_meetings_of_this_user as $record) {
            Log::info("Samee testing");
            Log::info($record['room_name']);
            array_push($array_of_user_rooms, $record['room_name']);
        }

        $json = array();
        if(!$all_meetings_of_this_user){
            $json['type'] = 'noMeetingBooked';
            return $json;
        }else{
            $user_profile = Profile::GetHourlyRate($user_id);
            Log::info($user_profile);
            $profile = Profile::where('user_id', $user_id)->first();
            Log::info($profile['hourly_rate']);
    
            Log::info("Observe!");

            $allRooms = [];
            $allMinutes = [];
            $allTimes = [];
    
            $userRooms = [];
            $userMinutes = [];
            $userTimes = [];
    
            set_time_limit(0);
            $twillioRequest = $twilio->video->v1->rooms
            ->read([
                       "Status" => "completed",
                   ],
                   1000
            );
    
            foreach ($twillioRequest as $record) {
                Log::info("START!");
                Log::info($record);
                Log::info($record->duration);
                Log::info($record->uniqueName);
                Log::info($record->dateCreated->format('h:i A'));
                Log::info($record->endTime->format('h:i A'));
                array_push($allMinutes, round(($record->duration / 3600), 4));
                array_push($allRooms, $record->uniqueName);
                array_push($allTimes, ($record->dateCreated->format('h.i A')). " - ".($record->endTime->format('h.i A')));
    
            }
    
            $roomIndex = 0;
            foreach($allRooms as $previousKey){
                Log::info("Comparing Twillio rooms with user's");
                Log::info($previousKey);
                if(in_array($previousKey, $array_of_user_rooms)){
                    Log::info("I FOUND YOUR ROOM");
                    Log::info($roomIndex);
                    array_push($userMinutes, $allMinutes[$roomIndex]);
                    array_push($userTimes, $allTimes[$roomIndex]);
                }
                $roomIndex++;
            }
            if(empty($userMinutes)){
                $json['type'] = 'meetingNotCompleted';
                return $json;                
            }    
        }

        if (!empty($all_meetings_of_this_user)) {
            $json['type'] = 'success';
            $json['meetings'] = $all_meetings_of_this_user;
            $json['durationOfMeeting'] = $userMinutes;
            $json['timeStamp'] = $userTimes;
            $json['hourlyRate'] = $profile['hourly_rate'];
            $json['sender'] = $user_id;
            Log::info("I am sending your meetings");
            Log::info($json);
            return $json;

        }

        if (Auth::user()) {
            $skills = User::find(Auth::user()->id)->skills()
                ->orderBy('title')->get()->toArray();
            if (!empty($skills)) {
                $json['type'] = 'success';
                $json['student_skills'] = $skills;
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

    /**
     * Get the list of people that send me bill invoice.
     *
     * @return \Illuminate\Http\Response
     */
    public function getReceivedInvoices()
    {
        $json = array();
        if (Auth::user()) {
            $requests=MeetingInvoice::where([
                ['receiver_Id', '=', Auth::user()->id],
            ])->get()->toArray();

            for( $i=0; $i < sizeof($requests); $i++ ){
                $array = User::select('first_name', 'last_name', 'id')->where('id', $requests[$i]['sender_Id'])->get()->toArray();
                $requests[$i]['first_name']=$array[0]['first_name'];
                $requests[$i]['last_name']=$array[0]['last_name'];
                $requests[$i]['friend_image']=url(Helper::getProfileImage($array[0]['id']));
            }
            if (!empty($requests)) {
                $json['type'] = 'success';
                $json['student_requests'] = $requests;
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

    /**
     * Get lis of people which I send request.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSentRequests()
    {
        $json = array();
        if (Auth::user()) {
            $requests=Offer::where([
                ['user_id', '=', Auth::user()->id],
                ['is_friend', '=', 0]
            ])->get()->toArray();

            for( $i=0; $i < sizeof($requests); $i++ ){
                $array = User::select('first_name', 'last_name', 'id')->where('id', $requests[$i]['freelancer_id'])->get()->toArray();
                $requests[$i]['first_name']=$array[0]['first_name'];
                $requests[$i]['last_name']=$array[0]['last_name'];
                $requests[$i]['friend_image']=url(Helper::getProfileImage($array[0]['id']));
            }
            if (!empty($requests)) {
                $json['type'] = 'success';
                $json['student_requests'] = $requests;
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

/**
     * Get lis of people which I received.
     *
     * @return \Illuminate\Http\Response
     */
    public function getReceivedRequests()
    {
        $json = array();
        if (Auth::user()) {
            $requests=Offer::where([
                ['freelancer_id', '=', Auth::user()->id],
                ['is_friend', '=', 0]
            ])->get()->toArray();

            for( $i=0; $i < sizeof($requests); $i++ ){
                $array = User::select('first_name', 'last_name', 'id')->where('id', $requests[$i]['user_id'])->get()->toArray();
                $requests[$i]['first_name']=$array[0]['first_name'];
                $requests[$i]['last_name']=$array[0]['last_name'];
                $requests[$i]['friend_image']=url(Helper::getProfileImage($array[0]['id']));
            }
            if (!empty($requests)) {
                $json['type'] = 'success';
                $json['student_requests'] = $requests;
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

    /**
     * Get lis of people which I send bill invoice.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSentInvoices()
    {
        $json = array();
        if (Auth::user()) {
            $requests=MeetingInvoice::where([
                ['sender_id', '=', Auth::user()->id],
            ])->get()->toArray();
            Log::info($requests);
            Log::info(sizeof($requests));
            for( $i=0; $i < sizeof($requests); $i++ ){
                $array = User::select('first_name', 'last_name', 'id')->where('id', $requests[$i]['receiver_Id'])->get()->toArray();
                Log::info("array");
                Log::info($array);
                $requests[$i]['first_name']=$array[0]['first_name'];
                $requests[$i]['last_name']=$array[0]['last_name'];
                $requests[$i]['friend_image']=url(Helper::getProfileImage($array[0]['id']));
            }
            if (!empty($requests)) {
                $json['type'] = 'success';
                $json['student_requests'] = $requests;
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

    /**
     * Get student friends list.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStudentFriends()
    {
        $json = array();
        if (Auth::user()) {
            $received=Offer::where([
                ['freelancer_id', '=', Auth::user()->id],
                ['is_friend', '=', 1]
            ])->get();
            $sent=Offer::where([
                ['user_id', '=', Auth::user()->id],
                ['is_friend', '=', 1]
            ])->get();

            $requests= $received->merge($sent)->toArray();
            for( $i=0; $i < sizeof($requests); $i++ ){
                if($requests[$i]['freelancer_id']==Auth::user()->id){
                    $array = User::select('first_name', 'last_name','id')
                    ->where('id', $requests[$i]['user_id'])
                    ->get()->toArray();
                    Log::info("1");
                    Log::info(Auth::user()->id);
                    Log::info($requests[$i]['freelancer_id']);
                }else{
                    $array = User::select('first_name', 'last_name','id')
                    ->where('id', $requests[$i]['freelancer_id'])
                    ->get()->toArray();
                    Log::info("2");
                    Log::info(Auth::user()->id);
                    Log::info($requests[$i]['freelancer_id']);
                }
                $requests[$i]['first_name']=$array[0]['first_name'];
                $requests[$i]['last_name']=$array[0]['last_name'];
                $requests[$i]['picture_id']=$array[0]['id'];
                $requests[$i]['friend_image']=url(Helper::getProfileImage($array[0]['id']));
            }
            Log::info($requests);

            if (!empty($requests)) {
                $json['type'] = 'success';
                $json['student_friends'] = $requests;
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
    
    /**
     * This function is used for deleting sent request, received request & friend.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteFromFriendsTable(Request $request)
    {
        Log::info("Here to delete!");
        Log::info($request);
        $json = array();
        if (Auth::user()) {
            $res=Offer::where([
                ['id', '=', $request['id']]
            ])->delete();
            Log::info($res);
            Log::info("Deleted!");
            Log::info($res);
            if($request['senderId']){
                $usecase1=Message::where([
                    ['user_id', '=', $request['senderId']],
                    ['receiver_id', '=', $request['receiverId']],
                ])->delete();
                $usecase2=Message::where([
                    ['user_id', '=', $request['receiverId']],
                    ['receiver_id', '=', $request['senderId']],
                ])->delete();                
            }
            if (!empty($res)) {
                $json['type'] = 'success';
                $json['request_delete_status'] = $res;
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


    public function rateResource(Request $request)
    {
        Log::info("I am going to rate a resource!");
        Log::info($request);
        $json = array();
        $resource = Resources::where([
            ['slug', '=', $request['resource_slug']]
        ])->first();
        if (Auth::user()) {
            $rating = Transactions::where([
                ['seller_id', '=', $request['seller_id']],
                ['buyer_id', '=', $request['buyer_id']],
                ['resource_slug', '=', $request['resource_slug']],
            ])->first();

            if (!empty($rating)) {
                $rating->comment = $request['comment'];
                $rating->buyers_rating = $request['buyers_rating'];
                $rating->is_feedback_given = 1;
                $resource->no_of_transactions = $resource->no_of_transactions + 1;
                $resource->average_rating = ($resource->average_rating + $request['buyers_rating']) / $resource->no_of_transactions;
                $resource->save();
                $rating->save();
                $json['type'] = 'success';
                $json['rating_given'] = $rating;
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
    
    // public function rateResource(Request $request)
    // {
    //     Log::info("I am going to rate a resource!");
    //     Log::info($request);
    //     $json = array();
    //     $resource = Resources::where([
    //         ['slug', '=', $request['resource_slug']]
    //     ])->first();
    //     if (Auth::user()) {
    //         // $rating = Transactions::where([
    //         //     ['seller_id', '=', $request['seller_id']],
    //         //     ['buyer_id', '=', $request['buyer_id']],
    //         //     ['resource_slug', '=', $request['resource_slug']],
    //         // ])->first();

    //             $rating=new Rating_resource();
    //             $rating->student_id = Auth::user()->id;
    //             $rating->resource_id = $resource->id;
    //             $rating->student_ratings = $request['buyers_rating'];
    //             $rating->comment = $request['comment'];
    //             $rating->resource_slug = $request['resource_slug'];
    //             //$rating->is_feedback_given = 1;
    //             //$resource->no_of_transactions = $resource->no_of_transactions + 1;
    //             //$resource->average_rating = ($resource->average_rating + $request['buyers_rating']) / $resource->no_of_transactions;
    //             //$resource->save();
    //             $rating->save();

    //             $json['type'] = 'success';
    //             $json['rating_given'] = $rating;
    //             return $json;
           
    //     } else {
    //         $json['type'] = 'error';
    //         return $json;
    //     }
    // }    

    /**
     * This function is used for dealing with invoice table.
     *
     * @return \Illuminate\Http\Response
     */
    public function invoiceForMeeting(Request $request)
    {
        Log::info("Here to send bill invoice!");
        $alreadySent=MeetingInvoice::where([
            ['meeting_slot', '=', $request['meetingSlot']],
        ])->first();

        if($alreadySent){
            if($alreadySent->meeting_slot == $request['meetingSlot']){
                $json['type'] = 'error';
                $json['message'] = 'You have already sent this meeting slot';
                return $json;
            }
        }

        $json = array();
        $json['type'] = "success";
        $profile =  $this->student->storeMeetingInvoice($request, Auth::user()->id);
        if ($profile = 'success') {
            $json['type'] = 'success';
            $json['message'] = 'Meeting Invoice sent to Student';
            return $json;
        }
        return $json;        
    }    
    /**
     * Get top student
     *
     * @return \Illuminate\Http\Response
     */
    public function getTopstudents()
    {
        $json = array();
        $students = User::getTopstudents();
        $top_students = array();
        if (!empty($students)) {
            foreach ($students as $key => $student) {
                $user = User::find($student->id);
                $top_students[$key]['id'] = $student->id;
                $top_students[$key]['name'] = Helper::getUserName($student->id);
                $top_students[$key]['slug'] = $user->slug;
                $top_students[$key]['image'] = asset(Helper::getProfileImage($student->id));
                $top_students[$key]['flag'] = !empty($user->location->flag) ? Helper::getLocationFlag($user->location->flag) :'';
                $top_students[$key]['location'] = !empty($user->location->title) ? $user->location->title :'';
                $top_students[$key]['tagline'] = !empty($user->profile->tagline) ? $user->profile->tagline :'';
                $top_students[$key]['hourly_rate'] = !empty($user->profile->hourly_rate) ? $user->profile->hourly_rate :'';
                $currency   = SiteManagement::getMetaValue('commision');
                $symbol = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
                $top_students[$key]['symbol'] = !empty($symbol['symbol']) ? $symbol['symbol'] : '$';
                $top_students[$key]['average_rating_count'] = !empty($student->total_reviews) ? $student->rating/$student->total_reviews : 0;
                $top_students[$key]['total_reviews'] = !empty($student->total_reviews) ? $student->total_reviews : 0;
                $top_students[$key]['save_students'] = !empty(auth()->user()->profile->saved_student) ? unserialize(auth()->user()->profile->saved_student) : array();
            }
        }
        if (!empty($top_students)) {
            $json['type'] = 'success';
            $json['students'] = $top_students;
            return $json;
        } else {
            $json['type'] = 'error';
            return $json;
        }
    }

    /**
     * Show the form for creating and updating experiance and education settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function experienceEducationSettings()
    {
        if (file_exists(resource_path('views/extend/back-end/student/profile-settings/experience-education/index.blade.php'))) {
            return view('extend.back-end.student.profile-settings.experience-education.index');
        } else {
            return view('back-end.student.profile-settings.experience-education.index');
        }
    }

    /**
     * Show the form for creating and updating projects & awards.
     *
     * @return \Illuminate\Http\Response
     */
    public function resourcesSettings()
    {
        if (file_exists(resource_path('views/extend/back-end/student/profile-settings/resources/index.blade.php'))) {
            return view('extend.back-end.student.profile-settings.resources.index');
        } else {
            return view('back-end.student.profile-settings.resources.index');
        }
    }

        /**
     * Show the form for creating and updating projects & awards.
     *
     * @return \Illuminate\Http\Response
     */
    public function purchasedResourcesSettings()
    {
        if (file_exists(resource_path('views/extend/back-end/student/profile-settings/purchased-resources/index.blade.php'))) {
            return view('extend.back-end.student.profile-settings.purchased-resources.index');
        } else {
            return view('back-end.student.profile-settings.purchased-resources.index');
        }
    }

        /**
     * Show the form for creating and updating projects & awards.
     *
     * @return \Illuminate\Http\Response
     */
    public function tutorsSettings()
    {
        $loc=DB::table('locations')->select('id','title')->where('id',Auth::user()->location_id)->first();
        $profile=DB::table('profiles')->select('university','degree_id')->where('user_id',Auth::user()->id)->first();
        if (file_exists(resource_path('views/extend/back-end/student/profile-settings/tutors/index.blade.php'))) {
            return view('extend.back-end.student.profile-settings.tutors.index');
        } else {
            return view('back-end.student.profile-settings.tutors.index',compact('loc','profile'));
        }
    }

        /**
     * Show the form for creating and updating projects & awards.
     *
     * @return \Illuminate\Http\Response
     */
    public function coursesSettings()
    {
        if (file_exists(resource_path('views/extend/back-end/student/profile-settings/courses/index.blade.php'))) {
            return view('extend.back-end.student.profile-settings.courses.index');
        } else {
            return view('back-end.student.profile-settings.courses.index');
        }
    }    

 
    
    /**
     * Show the details of requests received & requests sent.
     *
     * @return \Illuminate\Http\Response
     */
    public function friendRequestsSettings()
    {
        if (file_exists(resource_path('views/extend/back-end/student/profile-settings/requests/index.blade.php'))) {
            return view('extend.back-end.student.profile-settings.requests.index');
        } else {
            return view('back-end.student.profile-settings.requests.index');
        }
    }    

    /**
     * Show the details of book meetings.
     *
     * @return \Illuminate\Http\Response
     */
    public function bookMeetingsSettings()
    {
        $received=Offer::where([
            ['freelancer_id', '=', Auth::user()->id],
            ['is_friend', '=', 1]
        ])->get();
        $sent=Offer::where([
            ['user_id', '=', Auth::user()->id],
            ['is_friend', '=', 1]
        ])->get();

        $requests= $received->merge($sent)->toArray();
        Log::info("sending to hide save button");
        Log::info($requests);
        $is_friend = 0;
        if ( !$requests ){
            $is_friend = 0;
        } else{
            $is_friend = 1;
        }
        if (file_exists(resource_path('views/extend/back-end/student/profile-settings/book-meetings/index.blade.php'))) {
            return view('back-end.student.profile-settings.book-meetings.index',
            compact(
                'is_friend'
                )
            );
        } else {
            return view('back-end.student.profile-settings.book-meetings.index',
            compact(
                'is_friend'
                )
            );
        }
    }        

    /**
     * Show the details of my meeting rooms.
     *
     * @return \Illuminate\Http\Response
     */
    public function myMeetingRooms()
    {
        if (file_exists(resource_path('views/extend/back-end/student/profile-settings/my-rooms/index.blade.php'))) {
            return view('extend.back-end.student.profile-settings.my-rooms.index');
        } else {
            return view('back-end.student.profile-settings.my-rooms.index');
        }
    }

    /**
     * Show the details of invoices.
     *
     * @return \Illuminate\Http\Response
     */
    public function invoiceSettings()
    {
        if (file_exists(resource_path('views/extend/back-end/student/profile-settings/invoices/index.blade.php'))) {
            return view('extend.back-end.student.profile-settings.invoices.index');
        } else {
            return view('back-end.student.profile-settings.invoices.index');
        }
    }

    /**
     * Show the details of meeting invoice.
     *
     * @return \Illuminate\Http\Response
     */
    public function billingSettings()
    {
        if (file_exists(resource_path('views/extend/back-end/student/profile-settings/billings/index.blade.php'))) {
            return view('extend.back-end.student.profile-settings.billings.index');
        } else {
            return view('back-end.student.profile-settings.billings.index');
        }
    }            

    /**
     * Show the details of friends list.
     *
     * @return \Illuminate\Http\Response
     */
    public function friendsSettings()
    {
        if (file_exists(resource_path('views/extend/back-end/student/profile-settings/friends/index.blade.php'))) {
            return view('extend.back-end.student.profile-settings.friends.index');
        } else {
            return view('back-end.student.profile-settings.friends.index');
        }
    }    

    /**
     * Show the form for creating and updating experiance and education settings.
     *
     * @param mixed $request Request
     *
     * @return \Illuminate\Http\Response
     */
   public function storeExperienceEducationSettings(Request $request)
    {

        $server = Helper::worketicIsDemoSiteAjax();
        if (!empty($server)) {
            $response['type'] = 'error';
            $response['message'] = $server->getData()->message;
            return $response;
        }
        $json = array();
        $this->validate(
            $request,
            [
                'experience.*.job_title' => 'required',
                'experience.*.start_date' => 'required',
                //'experience.*.end_date' => 'required',
                'experience.*.company_title' => 'required',
               // 'education.*.degree_title' => 'required',
                'education.*.start_date' => 'required',
                //'education.*.end_date' => 'required',
                'education.*.institute_title' => 'required',
            ]
        );

        $user_id = Auth::user()->id;
        $update_experience_education = $this->student->updateExperienceEducation($request, $user_id);
        if ($update_experience_education['type'] == 'success') {
            $json['type'] = 'success';
            $json['message'] = trans('lang.saving_profile');
            $json['complete_message'] = trans('lang.profile_update_success');
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.empty_fields_not_allowed');
        }
        return $json;
    }

    /**
     * Show the form with saved values.
     *
     * @return \Illuminate\Http\Response
     */
    public function getstudentExperiences()
    {
        $json = array();
        $user_id = Auth::user()->id;
        if (Auth::user()) {
            $profile = $this->student::select('experience')
                ->where('user_id', $user_id)->get()->first();
            if (!empty($profile)) {
                $json['type'] = 'success';
                $json['experiences'] = unserialize($profile->experience);
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

    /**
     * Show the form with saved values.
     *
     * @return \Illuminate\Http\Response
     */
    public function getstudentEducations()
    {
        $json = array();
        $user_id = Auth::user()->id;
        if (Auth::user()) {
            $profile = $this->student::select('education')
                ->where('user_id', $user_id)->get()->first();
            if (!empty($profile)) {
                $json['type'] = 'success';
                $json['educations'] = unserialize($profile->education);
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


     public function getstudentDegree()
    {

        $json = array();
       $user_id = Auth::user()->id;
        if (Auth::user()) {
            $profile = Degree::select('id','title')->get();
            if (!empty($profile)) {
                $json['type'] = 'success';
               $json['degree'] = $profile; 
               // $json['educations'] = unserialize($profile->education);
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


    /**
     * Show the form for creating and updating projects and awards settings.
     *
     * @param mixed $request Request
     *
     * @return \Illuminate\Http\Response
     */
    public function storeProjectAwardSettings(Request $request)
    {
        $server = Helper::worketicIsDemoSiteAjax();
        if (!empty($server)) {
            $response['type'] = 'error';
            $response['message'] = $server->getData()->message;
            return $response;
        }
        $json = array();
        if (!empty($request)) {
            $user_id = Auth::user()->id;
            Log::info($request['skills']);
            Log::info($request['is_save_course']);
            if($request['skills']!==null){
                foreach($request['skills'] as $singleEntry){
                    if( $request['is_save_course'] == "0" ){
                        $response['type'] = 'error';
                        $response['message'] = 'Please save course before proceeding';
                        return $response;
                    }else if( $singleEntry['starting_date'] > $singleEntry['completing_date'] ){
                        $response['type'] = 'error';
                        $response['message'] = 'Starting year of course must be less than completing year';
                        return $response;
                    } else if( $singleEntry['starting_date'] == $singleEntry['completing_date'] ){
                        $response['type'] = 'error';
                        $response['message'] = 'Starting and completing date must be different';
                        return $response;
                    } else if( $singleEntry['starting_date'] > date("Y-m-d") || $singleEntry['completing_date'] > date("Y-m-d") ){
                        $response['type'] = 'error';
                        $response['message'] = 'You cannot enter future date';
                        return $response;
                    } else if( $singleEntry['starting_date'] < "1980-01-01" ){
                        $response['type'] = 'error';
                        $response['message'] = 'Starting date cannot be before 1980';
                        return $response;
                    }
                }    
            }
            $store_awards_projects = $this->student->updateAwardProjectSettings($request, $user_id);
            if ($store_awards_projects['type'] == 'success') {
                $json['type'] = 'success';
                $json['message'] = trans('lang.saving_profile');
                $json['complete_message'] = 'Courses Updated Successfully';
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.empty_fields_not_allowed');
            }
            return $json;
        }
    }

    /**
     * Upload Resources.
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadResources(Request $request)
    {
        Log::info("Resource Uplaoding function");
        Log::info($request);
        // $path = Helper::PublicPath() . '/uploads/users/temp/';
        $path = Helper::PublicPath() . '/uploads/users/'.Auth::user()->id.'/projects'.'/';
        $uploadedFile = $request['project_img'];
        $filename = $uploadedFile->getClientOriginalName();
        // $fileName = time().'.'.$uploadedFile->extension();     
        Log::info("Resource Uplaoded");
        return $uploadedFile->move($path, $filename);
                
    }
    
    /**
     * Upload Bank Details.
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function submitBankDetails(Request $request)
    {
        Log::info($request);
        $user_id = Auth::user()->id;
        $seller = Profile::where('user_id', $user_id)->first();        
        if($seller->payout_settings !== NULL){
            $profile=Profile::where([
                ['user_id', '=', $user_id]
            ])->first();
            $payrols = Helper::getPayoutsList();
            return view(
                    'back-end.student.payouts.payout_settings', compact(
                        'payrols', 
                        'profile'
                        )
                );
        }
        
        $stripe = Stripe::make(env('STRIPE_SECRET'));
        Log::info("Making connected User");
        Log::info($request);
        Log::info($request['code']);
        $response = \Stripe\OAuth::token([
            'grant_type' => 'authorization_code',
            'code' => $request['code'],
        ]);
        Log::info("User enabled");
        Log::info($response);
        Log::info("Auth::user()");
        Log::info(Auth::user());
        if (Auth::user()) {            
            $profile=Profile::where([
                ['user_id', '=', $user_id]
            ])->first();
            Log::info($profile->payout_settings);
            $profile->payout_settings = $response['stripe_user_id'];
            $profile->save();
            $payrols = Helper::getPayoutsList();
            $user = User::find(Auth::user()->id);
            return view(
                    'back-end.student.payouts.payout_settings', compact(
                        'payrols', 
                        'profile'
                        )
                );
        } else {
            abort(404);
        }

        return $response;
        
    }
     
    /**
     * Show the form for creating and updating resources.
     *
     * @param mixed $request Request
     *
     * @return \Illuminate\Http\Response
     */
    public function storeResourcesSettings(Request $request)
    {
      
        Log::info("Here");
        Log::info($request);
        $server = Helper::worketicIsDemoSiteAjax();
        if (!empty($server)) {
            $response['type'] = 'error';
            $response['message'] = $server->getData()->message;
            return $response;
        }
        $json = array();
        if (!empty($request)) {
            $user_id = Auth::user()->id;
            $store_awards_projects = $this->student->updateResourcesSettings($request, $user_id);
            if ($store_awards_projects['type'] == 'success') {
                $json['type'] = 'success';
                $json['message'] = trans('lang.saving_profile');
                $json['complete_message'] = 'Profile Updated Successfully';
            } else if ($store_awards_projects['type'] == "please_save"){
                $json['type'] = 'error';
//                  $json['message'] = "Please save fields before proceeding"; 
                $json['message'] = "Please upload resource document before proceeding";                
            } else if($store_awards_projects['type'] == "please_upload_resource"){
                $json['type'] = 'error';
                $json['message'] = "Please upload resource document before proceeding";                
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.empty_fields_not_allowed');
            }
            return $json;
        }
    }    

        /**
     * Show the form for booking a meeting.
     *
     * @param mixed $request Request
     *
     * @return \Illuminate\Http\Response
     */
    public function storeBookingMeeting(Request $request)
    {
        Log::info("Display meeting data");
        Log::info($request);
        Log::info("START!");
        Log::info($request['meetings']);

        $store_meeting_settings = $this->student->updateMeetingsSettings($request);
        if ($store_meeting_settings['type'] == 'success') {
            $json['type'] = 'success';
            $json['message'] = trans('lang.saving_profile');
            $json['complete_message'] = 'Courses Updated Successfully';
        } else if($store_meeting_settings['type']=='alreadyBookedThree'){
            $json['type'] = 'error';
            $json['message'] = trans('lang.already_booked_three');
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.empty_fields_not_allowed');
        }
    return $json;
    }

        /**
     * Show the form for booking a meeting.
     *
     * @param mixed $request Request
     *
     * @return \Illuminate\Http\Response
     */
    public function storeCallRequest(Request $request)
    {
        $user_id = Auth::user()->id;
        $list1=Rooms::where([
            ['receiver_id', '=', $request['user_id']],
        ])->pluck('room_name')->toArray();

        $list2=Rooms::where([
            ['sender_id', '=', $request['user_id']],
        ])->pluck('room_name')->toArray();
        
        $list3 = array_merge($list1,$list2);
        
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $twilio = new Client($sid, $token);
        $allRooms=[];

        set_time_limit(0);
        $twillioRequest = $twilio->video->v1->rooms
        ->read([
                   "Status" => "in-progress",
               ],
               1000
        );

        foreach ($twillioRequest as $record) {
            Log::info("START!");
            array_push($allRooms, $record->uniqueName);
        }

        Log::info($allRooms);
        Log::info($list3);
        if(array_intersect($allRooms, $list3)){
            $json['type'] = 'error';
            $json['message'] = "This member is already on another call. Please wait.";
            Log::info("ALREADY IN CALL");
            return $json;
        }else{
            //Storing roomname in Rooms (Only incoming and outgoing calls will be stored here)
            $rooms = new Rooms;
            $rooms->sender_Id = $user_id;
            $rooms->receiver_Id = $request['user_id'];
            $rooms->room_name = $request['room_name'];
            $rooms->save();
    
            //Sending call notification
            event(new callingFriend($user_id, $request['user_id'], $request['body'], Auth::user()->first_name." ".Auth::user()->last_name));

            $disk="custom";
            Log::info("KKK: ");
            $entryInFile = 'date_not_matters'.','.$request['room_name'].','."incoming call";
            Storage::disk($disk)->append("incoming-call-Ids.txt", $entryInFile);
            
            event(new callingFriend($user_id, $request['user_id'], $request['body'], Auth::user()->first_name." ".Auth::user()->last_name));            
            
            
            
            $json['type'] = 'success';
            $json['message'] = "Call invite sent";
            return $json;
        }
    }


    /**
     * Show the form for creating and updating projects and awards settings.
     *
     * @param mixed $request Request
     *
     * @return \Illuminate\Http\Response
     */
   public function storeTutorSettings(Request $request)
    {
       
        $path    = '../../';
        $files = scandir($path);
        Log::info("Hey Samee!");
        Log::info(storage_path('app')); 
        Log::info($files);
        $server = Helper::worketicIsDemoSiteAjax();
        if (!empty($server)) {
            $response['type'] = 'error';
            $response['message'] = $server->getData()->message;
            return $response;
        }
        $json = array();
        // if (!empty($request)) {
        //     echo "yes";die;
          
        //     Log::info($request['skills']);
        //     Log::info($request['is_save_course']);
        //     if($request['skills']!==null){
        //         foreach($request['skills'] as $singleEntry){
        //             if( $request['is_save_course'] == "0" ){
        //                 $response['type'] = 'error';
        //                 $response['message'] = 'Please save settings before proceeding';
        //                 return $response;
        //             }
        //         }    
        //     }
           
            $user_id = Auth::user()->id;
            $store_tutor_settings = $this->student->updateTutorSettings($request, $user_id);
            if ($store_tutor_settings['type'] == 'success') {
                $json['type'] = 'success';
                //$json['message'] = trans('lang.saving_tutor');
                $json['message'] = 'Tutor Information Save Successfully';
                $json['complete_message'] = 'Tutor Information Save Successfully';

            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.empty_fields_not_allowed');
            }
            return $json;
        
    } 
    
     /** This function is used to accept friend request.
     *
     * @param mixed $request Request
     *
     * @return \Illuminate\Http\Response
     */
    public function acceptFriendRequest(Request $request)
    {
        Log::info("Here to Accept!");
        Log::info($request);
        $json = array();
        if (Auth::user()) {
            $res=Offer::where([
                ['id', '=', $request['id']]
            ])->first();
            $res->is_friend = 1;
            $res->save();            

            Log::info("Accepted!");
            Log::info($res);
            $message = new Message();
            $message->user_id = $res['user_id'];
            $message->receiver_id = $res['freelancer_id'];
            $message->body = "";
            $message->status = 1;
            $message->save();
            
            Log::info("Conversation Created!");
            Log::info($message);

            if (!empty($res)) {
                $json['type'] = 'success';
                $json['request_accept_status'] = $res;
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

    /**
     * Get student's projects
     *
     * @return \Illuminate\Http\Response
     */
    public function getstudentProjects()
    {
        $user_id = Auth::user()->id;
        $json = array();
        if (Auth::user()) {
            $profile = $this->student::select('projects')
                ->where('user_id', $user_id)->get()->first();
            $profile_projects = array();
            if (!empty($profile)) {
                $projects = !empty($profile->projects) ? Helper::getUnserializeData($profile->projects) : array();
                if (!empty($projects)) {
                    foreach ($projects as $key => $project) {
                        $profile_projects[$key]['resource_title'] = !empty($project['resource_title']) ? $project['resource_title'] : '';
                        $profile_projects[$key]['resource_price'] = !empty($project['resource_price']) ? $project['resource_price'] : '';
                        $profile_projects[$key]['resource_description'] = !empty($project['resource_description']) ? $project['resource_description'] : '';
                        $profile_projects[$key]['project_hidden_image'] = !empty($project['project_hidden_image']) ? url('/uploads/users/'.$user_id.'/projects/'.$project['project_hidden_image']) : '';
                        $profile_projects[$key]['project_image'] = !empty($project['project_hidden_image']) ? $project['project_hidden_image'] : '';
                    }
                }
                $json['type'] = 'success';
                $json['projects'] = $profile_projects;
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

    /**
     * Get student's awards
     *
     * @return \Illuminate\Http\Response
     */
    public function getstudentAwards()
    {
        $user_id = Auth::user()->id;
        $json = array();
        if (Auth::user()) {
            $profile = $this->student::select('awards')
                ->where('user_id', $user_id)->get()->first();
            $profile_awards = array();
            if (!empty($profile)) {
                $awards = !empty($profile->awards) ? Helper::getUnserializeData($profile->awards) : array();
                if (!empty($awards)) {
                    foreach ($awards as $key => $award) {
                        $profile_awards[$key]['award_title'] = $award['award_title'];
                        $profile_awards[$key]['award_date'] = $award['award_date'];
                        $profile_awards[$key]['award_hidden_image'] = url('/uploads/users/'.$user_id.'/awards/'.$award['award_hidden_image']);
                        $profile_awards[$key]['award_image'] = !empty($award['award_hidden_image']) ? $award['award_hidden_image'] : '';
                    }
                }
                $json['type'] = 'success';
                $json['awards'] = $profile_awards;
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

    /**
     * Show student Jobs.
     *
     * @param string $status job status
     *
     * @return \Illuminate\Http\Response
     */
    public function showstudentJobs($status)
    {
        $ongoing_jobs = array();
        $freelancer_id = Auth::user()->id;
        $currency  = SiteManagement::getMetaValue('commision');
        $symbol    = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
        if (Auth::user()) {
            $ongoing_jobs = Proposal::select('job_id')->latest()->where('freelancer_id', $freelancer_id)->where('status', 'hired')->paginate(7);
            $completed_jobs = Proposal::select('job_id')->latest()->where('freelancer_id', $freelancer_id)->where('status', 'completed')->paginate(7);
            $cancelled_jobs = Proposal::select('job_id')->latest()->where('freelancer_id', $freelancer_id)->where('status', 'cancelled')->paginate(7);
            if (!empty($status) && $status === 'hired') {
                if (file_exists(resource_path('views/extend/back-end/student/jobs/ongoing.blade.php'))) {
                    return view(
                        'extend.back-end.student.jobs.ongoing',
                        compact(
                            'ongoing_jobs',
                            'symbol'
                        )
                    );
                } else {
                    return view(
                        'back-end.student.jobs.ongoing',
                        compact(
                            'ongoing_jobs',
                            'symbol'
                        )
                    );
                }
            } elseif (!empty($status) && $status === 'completed') {
                if (file_exists(resource_path('views/extend/back-end/student/jobs/completed.blade.php'))) {
                    return view(
                        'extend.back-end.student.jobs.completed',
                        compact(
                            'completed_jobs',
                            'symbol'
                        )
                    );
                } else {
                    return view(
                        'back-end.student.jobs.completed',
                        compact(
                            'completed_jobs',
                            'symbol'
                        )
                    );
                }
            } elseif (!empty($status) && $status === 'cancelled') {
                if (file_exists(resource_path('views/extend/back-end/student/jobs/cancelled.blade.php'))) {
                    return view(
                        'extend.back-end.student.jobs.cancelled',
                        compact(
                            'cancelled_jobs',
                            'symbol'
                        )
                    );
                } else {
                    return view(
                        'back-end.student.jobs.cancelled',
                        compact(
                            'cancelled_jobs',
                            'symbol'
                        )
                    );
                }
            }
        }
    }

    /**
     * Show student Job Details.
     *
     * @param string $slug job slug
     *
     * @return \Illuminate\Http\Response
     */
    public function showOnGoingJobDetail($slug)
    {
        $job = array();
        if (Auth::user()) {
            $job = Job::where('slug', $slug)->first();

            $proposal = Job::find($job->id)->proposals()->select('id', 'status')->where('status', '!=', 'pending')
                ->first();
            if ($proposal->status == 'cancelled') {
                $proposal_job = Job::find($job->id);
                $cancel_reason = $job->reports->first();
            } else {
                $cancel_reason = '';
            }
            $employer_name = Helper::getUserName($job->user_id);
            $duration = !empty($job->duration) ? Helper::getJobDurationList($job->duration) : '';
            $profile = User::find(Auth::user()->id)->profile;
            $employer_profile = User::find($job->user_id)->profile;
            $employer_avatar = !empty($employer_profile) ? $employer_profile->avater : '';
            $user_image = !empty($profile) ? $profile->avater : '';
            $profile_image = !empty($user_image) ? '/uploads/users/' . Auth::user()->id . '/' . $user_image : 'images/user-login.png';
            $employer_image = !empty($employer_avatar) ? '/uploads/users/' . $job->user_id . '/' . $employer_avatar : 'images/user-login.png';
            $currency   = SiteManagement::getMetaValue('commision');
            $symbol = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
            if (file_exists(resource_path('views/extend/back-end/student/jobs/show.blade.php'))) {
                return view(
                    'extend.back-end.student.jobs.show',
                    compact(
                        'job',
                        'employer_name',
                        'duration',
                        'profile_image',
                        'employer_image',
                        'proposal',
                        'symbol',
                        'cancel_reason'
                    )
                );
            } else {
                return view(
                    'back-end.student.jobs.show',
                    compact(
                        'job',
                        'employer_name',
                        'duration',
                        'profile_image',
                        'employer_image',
                        'proposal',
                        'symbol',
                        'cancel_reason'
                    )
                );
            }
        }
    }

    /**
     * Show student proposals.
     *
     * @return \Illuminate\Http\Response
     */
    public function showstudentProposals()
    {
        $proposals = Proposal::select('job_id', 'status', 'id')->where('freelancer_id', Auth::user()->id)->latest()->paginate(7);
        $currency  = SiteManagement::getMetaValue('commision');
        $symbol    = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
        if (file_exists(resource_path('views/extend/back-end/student/proposals/index.blade.php'))) {
            return view(
                'extend.back-end.student.proposals.index',
                compact(
                    'proposals',
                    'symbol'
                )
            );
        } else {
            return view(
                'back-end.student.proposals.index',
                compact(
                    'proposals',
                    'symbol'
                )
            );
        }
    }

    /**
     * Show student dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function studentDashboard()
    {
        if (Auth::user()) {
            $ongoing_jobs = array();
            $freelancer_id = Auth::user()->id;
            $ongoing_projects = Proposal::getProposalsByStatus($freelancer_id, 'hired');
            $cancelled_projects = Proposal::getProposalsByStatus($freelancer_id, 'cancelled');
            $package_item = Item::where('subscriber', $freelancer_id)->first();
            $package = !empty($package_item) ? Package::find($package_item->product_id) : array();
            $option = !empty($package) && !empty($package['options']) ? unserialize($package['options']) : '';
            $expiry = !empty($option) ? $package_item->updated_at->addDays($option['duration']) : '';
            $expiry_date = !empty($expiry) ? Carbon::parse($expiry)->toDateTimeString() : '';
            $message_status = Message::where('status', 0)->where('receiver_id', $freelancer_id)->count();
            $notify_class = $message_status > 0 ? 'wt-insightnoticon' : '';
            $completed_projects = Proposal::getProposalsByStatus($freelancer_id, 'completed');
            $completed_projects_history = Proposal::getProposalsByStatus($freelancer_id, 'completed', 'completed');
            $currency   = SiteManagement::getMetaValue('commision');
            $symbol     = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
            $trail      = !empty($package) && $package['trial'] == 1 ? 'true' : 'false';
            $icons      = SiteManagement::getMetaValue('icons');
            $enable_package = !empty($currency) && !empty($currency[0]['enable_packages']) ? $currency[0]['enable_packages'] : 'true';
            $latest_proposals_icon = !empty($icons['hidden_latest_proposal']) ? $icons['hidden_latest_proposal'] : 'img-20.png';
            $latest_package_expiry_icon = !empty($icons['hidden_package_expiry']) ? $icons['hidden_package_expiry'] : 'img-21.png';
            $latest_new_message_icon = !empty($icons['hidden_new_message']) ? $icons['hidden_new_message'] : 'img-19.png';
            $latest_saved_item_icon = !empty($icons['hidden_saved_item']) ? $icons['hidden_saved_item'] : 'img-22.png';
            $latest_cancel_project_icon = !empty($icons['hidden_cancel_project']) ? $icons['hidden_cancel_project'] : 'img-16.png';
            $latest_ongoing_project_icon = !empty($icons['hidden_ongoing_project']) ? $icons['hidden_ongoing_project'] : 'img-17.png';
            $latest_pending_balance_icon = !empty($icons['hidden_pending_balance']) ? $icons['hidden_pending_balance'] : 'icon-01.png';
            $latest_current_balance_icon = !empty($icons['hidden_current_balance']) ? $icons['hidden_current_balance'] : 'icon-02.png';
            $published_services_icon = !empty($icons['hidden_published_services']) ? $icons['hidden_published_services'] : 'payment-method.png';
            $cancelled_services_icon = !empty($icons['hidden_cancelled_services']) ? $icons['hidden_cancelled_services'] : 'decline.png';
            $completed_services_icon = !empty($icons['hidden_completed_services']) ? $icons['hidden_completed_services'] : 'completed-task.png';
            $ongoing_services_icon = !empty($icons['hidden_ongoing_services']) ? $icons['hidden_ongoing_services'] : 'onservice.png';
            $access_type = Helper::getAccessType();
            if (file_exists(resource_path('views/extend/back-end/student/dashboard.blade.php'))) {
                return view(
                    'extend.back-end.student.dashboard',
                    compact(
                        'freelancer_id',
                        'completed_projects_history',
                        'access_type',
                        'ongoing_projects',
                        'cancelled_projects',
                        'expiry_date',
                        'notify_class',
                        'completed_projects',
                        'symbol',
                        'trail',
                        'latest_proposals_icon',
                        'latest_package_expiry_icon',
                        'latest_new_message_icon',
                        'latest_saved_item_icon',
                        'latest_cancel_project_icon',
                        'latest_ongoing_project_icon',
                        'latest_pending_balance_icon',
                        'latest_current_balance_icon',
                        'published_services_icon',
                        'cancelled_services_icon',
                        'completed_services_icon',
                        'ongoing_services_icon',
                        'enable_package',
                        'package'
                    )
                );
            } else {
                return view(
                    'back-end.student.dashboard',
                    compact(
                        'freelancer_id',
                        'completed_projects_history',
                        'access_type',
                        'ongoing_projects',
                        'cancelled_projects',
                        'expiry_date',
                        'notify_class',
                        'completed_projects',
                        'symbol',
                        'trail',
                        'latest_proposals_icon',
                        'latest_package_expiry_icon',
                        'latest_new_message_icon',
                        'latest_saved_item_icon',
                        'latest_cancel_project_icon',
                        'latest_ongoing_project_icon',
                        'latest_pending_balance_icon',
                        'latest_current_balance_icon',
                        'published_services_icon',
                        'cancelled_services_icon',
                        'completed_services_icon',
                        'ongoing_services_icon',
                        'enable_package',
                        'package'
                    )
                );
            }
        }
    }

    /**
     * Show services.
     *
     * @param string $status job status
     *
     * @return \Illuminate\Http\Response
     */
    public function showServices($status)
    {
        $freelancer_id = Auth::user()->id;
        if (Auth::user()) {
            $student = User::find($freelancer_id);
            $currency   = SiteManagement::getMetaValue('commision');
            $symbol = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
            $status_list = array_pluck(Helper::getstudentServiceStatus(), 'title', 'value');
            if (!empty($status) && $status === 'posted') {
                $services = $student->services;
                if (file_exists(resource_path('views/extend/back-end/student/services/index.blade.php'))) {
                    return view(
                        'extend.back-end.student.services.index',
                        compact(
                            'services',
                            'symbol',
                            'status_list'
                        )
                    );
                } else {
                    return view(
                        'back-end.student.services.index',
                        compact(
                            'services',
                            'symbol',
                            'status_list'
                        )
                    );
                }
            } else if (!empty($status) && $status === 'hired') {
                $services = Helper::getstudentServices('hired', Auth::user()->id);
                if (file_exists(resource_path('views/extend/back-end/student/services/ongoing.blade.php'))) {
                    return view(
                        'extend.back-end.student.services.ongoing',
                        compact(
                            'services',
                            'symbol'
                        )
                    );
                } else {
                    return view(
                        'back-end.student.services.ongoing',
                        compact(
                            'services',
                            'symbol'
                        )
                    );
                }
            } elseif (!empty($status) && $status === 'completed') {
                $services = Helper::getstudentServices('completed', Auth::user()->id);
                if (file_exists(resource_path('views/extend/back-end/student/services/completed.blade.php'))) {
                    return view(
                        'extend.back-end.student.services.completed',
                        compact(
                            'services',
                            'symbol'
                        )
                    );
                } else {
                    return view(
                        'back-end.student.services.completed',
                        compact(
                            'services',
                            'symbol'
                        )
                    );
                }
            } elseif (!empty($status) && $status === 'cancelled') {
                $services = Helper::getstudentServices('cancelled', Auth::user()->id);
                if (file_exists(resource_path('views/extend/back-end/student/services/cancelled.blade.php'))) {
                    return view(
                        'extend.back-end.student.services.cancelled',
                        compact(
                            'services',
                            'symbol'
                        )
                    );
                } else {
                    return view(
                        'back-end.student.services.cancelled',
                        compact(
                            'services',
                            'symbol'
                        )
                    );
                }
            }
        }
    }

    /**
     * Service Details.
     *
     * @param int    $id     id
     * @param string $status status
     *
     * @return \Illuminate\Http\Response
     */
    public function showServiceDetail($id, $status)
    {
        if (Auth::user()) {
            $pivot_service = Helper::getPivotService($id);
            $pivot_id = $pivot_service->id;
            $service = Service::find($pivot_service->service_id);
            $seller = Helper::getServiceSeller($service->id);
            $purchaser = $service->purchaser->first();
            $student = !empty($seller) ? User::find($seller->user_id) : ''; 
            $service_status = Helper::getProjectStatus();
            $review_options = DB::table('review_options')->get()->all();
            $avg_rating = !empty($student) ? Review::where('receiver_id', $student->id)->sum('avg_rating') : '';
            $student_rating  = !empty($student) && !empty($student->profile->ratings) ? Helper::getUnserializeData($student->profile->ratings) : 0;
            $rating = !empty($student_rating) ? $student_rating[0] : 0;
            $stars  =  !empty($student_rating) ? $student_rating[0] / 5 * 100 : 0;
            $reviews = !empty($student) ? Review::where('receiver_id', $student->id)->where('job_id', $id)->where('project_type', 'service')->get() : '';
            $feedbacks = !empty($student) ? Review::select('feedback')->where('receiver_id', $student->id)->count() : '';
            $cancel_proposal_text = trans('lang.cancel_proposal_text');
            $cancel_proposal_button = trans('lang.send_request');
            $validation_error_text = trans('lang.field_required');
            $cancel_popup_title = trans('lang.reason');
            $attachment = Helper::getUnserializeData($service->attachments);
            $currency   = SiteManagement::getMetaValue('commision');
            $symbol = !empty($currency) && !empty($currency[0]['currency']) ? Helper::currencyList($currency[0]['currency']) : array();
            if (file_exists(resource_path('views/extend/back-end/employer/services/show.blade.php'))) {
                return view(
                    'extend.back-end.employer.services.show',
                    compact(
                        'pivot_service',
                        'id',
                        'service',
                        'student',
                        'service_status',
                        'attachment',
                        'review_options',
                        'stars',
                        'rating',
                        'feedbacks',
                        'cancel_proposal_text',
                        'cancel_proposal_button',
                        'validation_error_text',
                        'cancel_popup_title',
                        'pivot_id',
                        'purchaser',
                        'symbol'
                    )
                );
            } else {
                return view(
                    'back-end.employer.services.show',
                    compact(
                        'pivot_service',
                        'id',
                        'service',
                        'student',
                        'service_status',
                        'attachment',
                        'review_options',
                        'stars',
                        'rating',
                        'feedbacks',
                        'cancel_proposal_text',
                        'cancel_proposal_button',
                        'validation_error_text',
                        'cancel_popup_title',
                        'pivot_id',
                        'purchaser',
                        'symbol'
                    )
                );
            }
        } else {
            abort(404);
        }
    }

    /**
     * Get student payouts.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPayouts()
    {
        $payouts =  Payout::where('user_id', Auth::user()->id)->paginate(10);
        if (file_exists(resource_path('views/extend/back-end/student/payouts.blade.php'))) {
            return view(
                'extend.back-end.student.payouts.payouts',
                compact('payouts')
            );
        } else {
            return view(
                'back-end.student.payouts.payouts',
                compact('payouts')
            );
        }
    }


    /**
     * Get student's resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showSpecificResource($slug)
    {
        $is_login = Auth::user();
        Log::info("Hi there");
        Log::info($is_login);

        $ratingbox=DB::table('users')
        ->select('users.id','users.first_name','users.last_name','transactions.comment','transactions.buyers_rating')
        ->join('transactions','transactions.buyer_id','=','users.id')
        ->where('transactions.resource_slug',$slug)
        ->get();



        // $alreadyrate=Rating_resource::where('student_id',Auth::user()->id)->where('resource_slug',$slug)->first();

        $user_id = Resources::select('user_id')->where('slug', $slug)->first();
        $resource =  Resources::where('slug', $slug)->first();
        Log::info("Specific resource:");
        Log::info($resource);
        $user = User::find($user_id);
        $is_purchased = 0;
        $is_purchased = Transactions::where([
            ['resource_slug', '=', $slug],
            ['buyer_id', '=', Auth::user()->id]
        ])->first();

        

        if (file_exists(resource_path('views/extend/back-end/student/payouts.blade.php'))) {
            return view(
                'extend.back-end.student.payouts.payouts',
                compact('payouts')
            );
        } else {
            return view(
                'front-end.services.show',
                compact(
                    'payouts',
                    'is_login',
                    'user',
                    'is_purchased',
                    'resource',
                    'ratingbox'
                    )
            );
        }
    }

   


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function payoutSettings()
    {
        if (Auth::user()) {
            $user_id = Auth::user()->id;
            $payrols = Helper::getPayoutsList();
            $profile = Profile::where('user_id', $user_id)->first();
            $user = User::find($user_id);
            // $payout_settings = $user->profile->count() > 0 ? Helper::getUnserializeData($user->profile->payout_settings) : '';
            if (file_exists(resource_path('views/extend/back-end/student/payouts/payout_settings.blade.php'))) {
                return view(
                    'extend.back-end.student.payouts.payout_settings', compact('payrols', 'payout_settings')
                );
            } else {
                return view(
                    'back-end.student.payouts.payout_settings', compact(
                        'payrols', 
                        'profile')
                );
            }
        } else {
            abort(404);
        }
    }
}
