<?php

/**
 * Class Profile.
 *
 * @category Worketic
 *
 * @package Worketic
 * @author  Amentotech <theamentotech@gmail.com>
 * @license http://www.amentotech.com Amentotech
 * @link    http://www.amentotech.com
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;
use File;
use Storage;
use function Opis\Closure\serialize;
use function Opis\Closure\unserialize;
use Illuminate\Support\Facades\Log;
use DB;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

/**
 * Class Profile
 *
 */
class Profile extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'department_id', 'no_of_employees', 'student_type',
        'english_level', 'hourly_rate', 'experience', 'education', 'awards',
        'projects', 'saved_student', 'saved_jobs', 'saved_employers',
        'rating','starting_date', 'is_tutor', 'updating_date', 'address', 'longitude', 'latitude', 'avater', 'banner',
        'gender', 'tagline', 'description', 'delete_reason', 'delete_description',
        'profile_searchable', 'profile_blocked', 'weekly_alerts', 'message_alerts'
    ];

    /**
     * Get the department that owns the employer.
     *
     * @return relation
     */
    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    public static function getHourlyRate($user_id)
    {
        return DB::table('profiles')->where('user_id', $user_id)
            ->get()->toArray();
    }

    /**
     * Get the user that has the profile.
     *
     * @return relation
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }


    /**
     * Store Profile in database
     *
     * @param mixed   $request Request Attributes
     * @param integer $user_id User ID
     *
     * @return json response
     */
   public function storeProfile($request, $user_id)
    {
       
        $user = User::find($user_id);
        if ($user->first_name . '-' . $user->last_name != $request['first_name'] . '-' . $request['last_name']) {
            $user->slug = filter_var($request['first_name'], FILTER_SANITIZE_STRING) . '-' .
                filter_var($request['last_name'], FILTER_SANITIZE_STRING);
        }
        $user->first_name = filter_var($request['first_name'], FILTER_SANITIZE_STRING);
        $user->last_name = filter_var($request['last_name'], FILTER_SANITIZE_STRING);
        if (!empty($request['email'])) {
            $user->email = filter_var($request['email'], FILTER_SANITIZE_STRING);
        }
        $location = Location::find($request['location']);
        $user->location()->associate($location);
        
        $user->faculty=$request['faculty'];
        $user->save();
        //$user->skills()->detach();
        $data = array();
       // $sk=new SkillUser;
        if ($request['skills']) {
            $skills = $request['skills'];
            if (!empty($skills)) {
               foreach ($skills as $key => $value) {
                   
               
               
                $exist=DB::table('skill_user')->where('user_id',Auth::user()->id)->where('skill_id',$value['id'])->get();
                if(sizeof($exist)>0)
                {

                }
                else
                {
                    $skill=new SkillUser;
                    $skill->user_id=Auth::user()->id;
                    $skill->skill_id=$value['id'];
                    $skill->starting_date=$value['starting_date'];
                    $skill->completing_date=$value['completing_date'];
                    $skill->save();
                }
               } 
               
                 
            }
        }
        $user_profile = $this::select('id')->where('user_id', $user_id)
            ->get()->first();
        if (!empty($user_profile->id)) {
            $profile = Profile::find($user_profile->id);
        } else {
            $profile = $this;
        }
        $profile->user()->associate($user_id);
        $profile->student_type = 'Basic';
        $profile->hourly_rate = intval($request['hourly_rate']);
        $profile->cgpa= $request['cgpa'];
        $profile->degree_id= $request['degree'];
        $profile->gender = filter_var($request['gender'], FILTER_SANITIZE_STRING);
        $profile->tagline = filter_var($request['tagline'], FILTER_SANITIZE_STRING);
        $profile->description = filter_var($request['description'], FILTER_SANITIZE_STRING);
        $profile->address = filter_var($request['address'], FILTER_SANITIZE_STRING);
        $profile->longitude = filter_var($request['longitude'], FILTER_SANITIZE_STRING);
        $profile->latitude = filter_var($request['latitude'], FILTER_SANITIZE_STRING);
        $profile->university=filter_var($request['university'], FILTER_SANITIZE_STRING);
        
        if ($request['employees']) {
            $profile->no_of_employees = intval($request['employees']);
        }
        if ($request['department']) {
            $department = Department::find($request['department']);
            $profile->department()->associate($department);
        }
        $old_path = Helper::PublicPath() . '/uploads/users/temp';
        if (!empty($request['hidden_avater_image'])) {
            $filename = $request['hidden_avater_image'];
            if (file_exists($old_path . '/' . $request['hidden_avater_image'])) {
                $new_path = Helper::PublicPath() . '/uploads/users/' . $user_id;
                if (!file_exists($new_path)) {
                    File::makeDirectory($new_path, 0755, true, true);
                }
                $filename = time() . '-' . $request['hidden_avater_image'];
                rename($old_path . '/' . $request['hidden_avater_image'], $new_path . '/' . $filename);
                rename($old_path . '/small-' . $request['hidden_avater_image'], $new_path . '/small-' . $filename);
                rename($old_path . '/medium-small-' . $request['hidden_avater_image'], $new_path . '/medium-small-' . $filename);
                rename($old_path . '/medium-' . $request['hidden_avater_image'], $new_path . '/medium-' . $filename);
                if (file_exists($old_path . '/listing-' . $request['hidden_avater_image'])) {
                    rename($old_path . '/listing-' . $request['hidden_avater_image'], $new_path . '/listing-' . $filename);
                }
            }
            $profile->avater = filter_var($filename, FILTER_SANITIZE_STRING);
        } else {
            $profile->avater = null;
        }
        if (!empty($request['hidden_banner_image'])) {
            $filename = $request['hidden_banner_image'];
            if (file_exists($old_path . '/' . $request['hidden_banner_image'])) {
                $new_path = Helper::PublicPath() . '/uploads/users/' . $user_id;
                if (!file_exists($new_path)) {
                    File::makeDirectory($new_path, 0755, true, true);
                }
                $filename = time() . '-' . $request['hidden_banner_image'];
                rename($old_path . '/' . $request['hidden_banner_image'], $new_path . '/' . $filename);
                rename($old_path . '/small-' . $request['hidden_banner_image'], $new_path . '/small-' . $filename);
                rename($old_path . '/medium-' . $request['hidden_banner_image'], $new_path . '/medium-' . $filename);
            }
            $profile->banner = filter_var($filename, FILTER_SANITIZE_STRING);
        } else {
            $profile->banner = null;
        }
        $videos = !empty($request['video']) ? $request['video'] : array();
        if (!empty($videos)) {
            foreach ($videos as $key => $video) {
                $videos[$key]['url'] = $video['url'];
            }
            $profile->videos = serialize($videos);
        } else {
            $profile->videos = null;
        }
        return $profile->save();
    }
    /**
     * Store Meeting Invoices in table
     *
     * @param mixed   $request Request Attributes
     * @param integer $user_id User ID
     *
     * @return json response
     */
    public function storeMeetingInvoice($request, $user_id)
    {
        $json = array();
        $json['type'] = 'success';
        Log::info($request);
        if (Auth::user()) {
            $newInvoice = new MeetingInvoice;
            $newInvoice->sender_Id = $request['senderId'];
            $newInvoice->receiver_Id = $request['receiverId'];
            $newInvoice->amount = $request['finalPayment'];
            $newInvoice->description = $request['description'];
            $newInvoice->meeting_slot = $request['meetingSlot'];
            $newInvoice->status = $request['status'];
            $newInvoice->transaction_Id = $request['transactionId'];
            $newInvoice->type = $request['type'];
            $newInvoice->save();
            return $json;
        }
    }


    /**
     * Store Email Notifications in database
     *
     * @param mixed   $request Request Attributes
     * @param integer $user_id User ID
     *
     * @return save data
     */
    public function storeEmailNotification($request, $user_id)
    {
        $user_profile = $this::select('id')->where('user_id', $user_id)->get()->first();
        if (!empty($user_profile->id)) {
            $user = $this::find($user_profile->id);
        } else {
            $user = $this;
            $user->user()->associate($user_id);
        }
        $user->weekly_alerts = $request['weekly_alerts'];
        $user->message_alerts = $request['message_alerts'];
        return $user->save();
    }

    /**
     * Store Account Settings
     *
     * @param mixed   $request Request Attributes
     * @param integer $user_id User ID
     *
     * @return save data
     */
    public function storeAccountSettings($request, $user_id)
    {
        $user_profile = $this::select('id')->where('user_id', $user_id)->get()->first();
        if (!empty($user_profile->id)) {
            $profile = $this::find($user_profile->id);
        } else {
            $profile = $this;
            $profile->user()->associate($user_id);
        }
        // $profile->profile_searchable = $request['profile_searchable'];
        //$profile->profile_blocked = $request['profile_blocked'];
        $profile->english_level = $request['english_level'];
        $profile->save();
        $user = User::find($user_id);
        $requested_languages = $request['languages'];
        $user->languages()->sync($requested_languages);
        if (Schema::hasColumn('users', 'is_disabled')) {
            $user->is_disabled = $request['is_disabled'];
        }
        $user->save();
    }

    /**
     * Store Profile in database
     *
     * @param mixed   $request Request Attributes
     * @param integer $user_id User ID
     *
     * @return json response
     */
    public function updateAwardProjectSettings($request, $user_id)
    {
        $json = array();
        $json['type'] = 'success';

        $student_skills = Skill::getstudentSkills($user_id);
        $result = (array) json_encode($student_skills);

        $oldSkillRatings=[];
        $i=0;
        foreach($student_skills as $previousKey){
            $oldSkillRatings[$i]=$previousKey->skill_id;
            $i++;
        }
        Log::info($oldSkillRatings);
        if(!$request['skills']){
            Log::info("Remove them all!");
            foreach ($oldSkillRatings as $delArray => $deleteMe) {
                Log::info($deleteMe);
                $res=SkillUser::where([
                    ['user_id', '=', $user_id],
                    ['skill_id', '=', $deleteMe],
                ])->delete();
                $i++;                                    
            }
            return $json;
        }

        $missingSkill=[];
        $i=0;
        Log::info("asa");
        // Log::info($request);
        foreach ($request['skills'] as $newKey => $newSkill) {
            // Log::info($newSkill);
            $missingSkill[$i]=(int)$newSkill['id'];
            $i++;
        }

        $deletedSkillsArray=array_diff($oldSkillRatings,$missingSkill);
        $i=0;
        foreach ($deletedSkillsArray as $delArray => $deleteMe) {
            Log::info($deleteMe);
            $res=SkillUser::where([
                ['user_id', '=', $user_id],
                ['skill_id', '=', $deleteMe],
            ])->delete();
            $i++;
        }

        foreach ($request['skills'] as $newKey => $newSkill) {
            $existingskill=SkillUser::where([
                ['user_id', '=', $user_id],
                ['skill_id', '=', $newSkill['id']],
            ])->first();
            if(!$existingskill){
                $newskill = new SkillUser;
                $newskill->skill_id = $newSkill['id'];
                $newskill->starting_date = $newSkill['starting_date'];
                $newskill->completing_date = $newSkill['completing_date'];
                $newskill->user_id = $user_id;
                $newskill->save();
            }else if($existingskill){
                $existingskill->skill_id = $newSkill['id'];
                $existingskill->starting_date = $newSkill['starting_date'];
                $existingskill->completing_date = $newSkill['completing_date'];
                $existingskill->user_id = $user_id;
                $existingskill->save();
            }
        }
        return $json;
    }

    /**
     * Store Profile in database
     *
     * @param mixed   $request Request Attributes
     * @param integer $user_id User ID
     *
     * @return json response
     */
    public function updateResourcesSettings($request, $user_id)
    {
    //dd($request->all());
    
        Log::info("strange error");
        Log::info($request);
        $json = array();
        $user = User::find($user_id);
        $count = 0;
        $request_project = array();

        if($request['is_save_course'] == 0){
            $json['type'] = 'please_save';
            return $json;  
        }
        $old_path = Helper::PublicPath() . '/uploads/users/temp';
        Log::info("********************************************************************");
        Log::info("********************************************************************");
        //Storing all randomIds from the resources table of backend 
        $already_stored_resources = Resources::getstudentResources($user_id);
        Log::info("catering delete scenario");
        Log::info($already_stored_resources);
        $storedRandomIds=[];
        $clientOldRandomIds=[];
        $clientNewRandomIds=[];

        $i=0;
        foreach($already_stored_resources as $key){
            Log::info($key->random_key);
            $storedRandomIds[$i]=$key->random_key;
            $i++;
        }
        Log::info("DEBUGG");
        Log::info($request['student_skills']);
        
        //Saving new resources
        if (!empty($request['skills'])) {
            //Cannot proceed without saving any resource
            foreach ($request['skills'] as $key => $resource) {
                if(!array_key_exists('resource_file', $resource)){
                    $json['type'] = 'please_upload_resource';
                    return $json;  
                }                
            }

            //Getting random Ids from freshly created resources
            $i=0;
            foreach($request['skills'] as $key => $val){
                Log::info("***");
                Log::info($val);
                Log::info($val['random_key']);
                $clientNewRandomIds[$i]=$val['random_key'];
                $i++;
            }
    
            //ADD and EDIT Scenario
            foreach ($request['skills'] as $key => $resource) {
                
                $existingresource=Resources::where([
                    ['user_id', '=', $user_id],
                    ['random_key', '=', $resource['random_key']],
                ])->first();
                if(!$existingresource){
                   
                    $filename=str_replace(" ", "_", $resource['resource_file_name']);
                    $imagepreview=str_replace(" ", "_", $resource['resource_file_image_name']);
                    $filename1 = md5($resource['resource_file']->getFilename().time()).'.'.$resource['resource_file']->getClientOriginalExtension();
                    $imagepreview1 = md5($resource['resource_file_image']->getFilename().time()).'.'.$resource['resource_file_image']->getClientOriginalExtension();
                    $newresource = new Resources;
                    $newresource->user_id = $user_id;
                    $newresource->user_name = $user->first_name." ".$user->last_name;
                    $newresource->course_id = $resource['course'];
                    $newresource->course_name = $resource['courseName'];
                    $newresource->title = $resource['resource_title'];
                    $newresource->description= $resource['resource_description'];
                    $newresource->price = $resource['resource_price'];
                    $newresource->name_of_file = $resource['random_key'].'-'.$resource['resource_file_name'];
                    $newresource->url_of_file = 'http://127.0.0.1:8000'.'/uploads/users/'.Auth::user()->id.'/projects'.'/'.$resource['random_key'].'-'.$filename1;
                    $newresource->imagepreview = 'http://127.0.0.1:8000'.'/uploads/users/'.Auth::user()->id.'/projects'.'/'.$resource['random_key'].'-'.$imagepreview1;
                    $newresource->random_key = $resource['random_key'];
                    $newresource->slug = $resource['random_key'];
                    $newresource->average_rating = 0;
                    $newresource->no_of_transactions = 0;
                    $newresource->save();
                }else if ($existingresource){
                    $existingresource->user_id = $user_id;
                    $existingresource->user_name = $user->first_name." ".$user->last_name;
                    $existingresource->course_id = $resource['course'];
                    $existingresource->course_name = $resource['courseName'];
                    $existingresource->title = $resource['resource_title'];
                    $existingresource->description= $resource['resource_description'];
                    $existingresource->price = $resource['resource_price'];
                    $existingresource->name_of_file = $resource['random_key'].'-'.$resource['resource_file_name'];
                    $existingresource->url_of_file = 'http://127.0.0.1:8000'.'/uploads/users/'.Auth::user()->id.'/projects'.'/'.$resource['random_key'].'-'.$filename1;
                    $existingresource->imagepreview = 'http://127.0.0.1:8000'.'/uploads/users/'.Auth::user()->id.'/projects'.'/'.$resource['random_key'].'-'.$imagepreview1;
                   
                    $existingresource->random_key = $resource['random_key'];
                    $existingresource->slug = $resource['random_key'];
                    $existingresource->save();
                }
                $path = Helper::PublicPath() . '/uploads/users/'.Auth::user()->id.'/projects'.'/';
                $uploadedFile = $resource['resource_file'];
                $uploadedFile->move($path, $resource['random_key'].'-'.$filename1);
                $uploadedFile2 = $resource['resource_file_image'];
                $uploadedFile2->move($path, $resource['random_key'].'-'.$imagepreview1);

                
            }
        }
        //Saving existing resources
        if (!empty($request['student_skills'])) {

            //Getting random Ids from previously created resources
            $i=0;
            foreach ($request['student_skills'] as $key => $val){

                Log::info($key);
                Log::info($val['random_key']);
                $clientOldRandomIds[$i]=$val['random_key'];
                $i++;
            }
            //EDIT Scenario
            foreach ($request['student_skills'] as $key => $resource) {
                $existingresource=Resources::where([
                    ['user_id', '=', $user_id],
                    ['random_key', '=', $resource['random_key']],
                ])->first();
                $existingresource->user_id = $user_id;
                $existingresource->title = $resource['title'];
                $existingresource->description= $resource['description'];
                $existingresource->price = $resource['price'];
                if(array_key_exists('name_of_file', $resource)){
                  
                    $existingresource->name_of_file = $resource['random_key'].'-'.$resource['resource_file_name'];
                    $existingresource->url_of_file = 'http://127.0.0.1:8000'. '/uploads/users/'.Auth::user()->id.'/projects'.'/'.$resource['random_key'].'-'.$resource['resource_file_name'];
                    $existingresource->save();

                    $path = Helper::PublicPath() . '/uploads/users/'.Auth::user()->id.'/projects'.'/';
                    $uploadedFile = $resource['name_of_file'];
                    $uploadedFile->move($path, $resource['random_key'].'-'.$resource['resource_file_name']);
                }

               
               
                if(array_key_exists('imagepreview', $resource)){  
                    $existingresource->imagepreview = $resource['imagepreview'];
                    $existingresource->save();
                    $path = Helper::PublicPath() . '/uploads/users/'.Auth::user()->id.'/projects'.'/ters/';
                    $uploadedFile2 = $resource['imagepreview'];
                    $uploadedFile2->move($path, $resource['random_key'].'-'.$resource['resource_file_image_name']);
                }


                Log::info("SAVED!");
                $existingresource->save();
            }
        }
        $mergedRandomIds = array_merge($clientNewRandomIds,$clientOldRandomIds);
        Log::info("TWO ARRAYS");
        Log::info($mergedRandomIds);
        Log::info($storedRandomIds);
        Log::info('FOLLOWING NEEDS DELETION');
        $deletedResourcesArray=array_diff($storedRandomIds,$mergedRandomIds);

        Log::info($deletedResourcesArray);
        foreach ($deletedResourcesArray as $delArray => $deleteMe) {
            Log::info("CRITICAL DELETION");
            Log::info($deleteMe);
            $res=Resources::where([
                ['user_id', '=', $user_id],
                ['random_key', '=', $deleteMe],
            ])->delete();            
        }        
        $json['type'] = 'success';
        return $json;
    }

    /**
     * Store Profile in database
     *
     * @param mixed   $request Request Attributes
     * @param integer $user_id User ID
     *
     * @return json response
     */
  public function updateTutorSettings($request, $user_id)
    {
        
        $json = array();
        $json['type'] = 'success';
        
        //dd($request['skills']);
      
        foreach ($request['skills'] as $newKey => $newSkill) {
                //
                if(!isset($newSkill['file'])) continue;
                //
                $existingskill=SkillUser::where([
                ['user_id', '=', $user_id],
                ['skill_id', '=', $newSkill['id'],
                ],
                ])->first();
            
                
            if($existingskill){
                
                $existingskill->is_tutor = $newSkill['is_tutor']==="true"?1:0;
                $existingskill->cgpa = $newSkill['cgpa'];
                $existingskill->save();
                $coursename=DB::table('skills')->select('title')->where('id',$newSkill['id'])->first();
              
                 $existingresource=Resources::where([
                    ['user_id', '=', $user_id],
                    ['course_id', '=', $newSkill['id']],
                ])->first();
                    

                if($existingresource==null)
                {
                    
                $res=new Resources;
                $res->title=$newSkill['title'];
                $res->user_id=$user_id;
                $res->course_name=$coursename->title;
                $res->user_name=Auth::user()->first_name.' '.Auth::user()->last_name;
                $res->slug=str_random(6);
                $res->course_id=$newSkill['id'];
                $res->description=$newSkill['description'];
                $res->price=$newSkill['price'];
                if(isset($newSkill['file'])  && $newSkill['file']!= '' && isset($newSkill['image_preview'])  && $newSkill['image_preview']!= '')
                {

                 $path = Helper::PublicPath() . '/uploads/users/'.Auth::user()->id.'/projects'.'/';
                 $filename=str_replace(" ", "_", $newSkill['file']);
                $filename1 = md5($newSkill['file']->getFilename().time()).'.'.$newSkill['file']->getClientOriginalExtension();
                $res->url_of_file = 'https://chuta.network/dev/public'.'/uploads/users/'.Auth::user()->id.'/projects'.'/'.$res->slug.'-'.$filename1;
                $res->name_of_file=$filename1;
                $uploadedFile = $newSkill['file'];
                $uploadedFile->move($path,$res->slug.'-'.$filename1);
                
                
                 $imagepreview=str_replace(" ", "_", $newSkill['image_preview']);

                $imagepreview1 = md5($newSkill['image_preview']->getFilename().time()).'.'.$newSkill['image_preview']->getClientOriginalExtension();
                $res->imagepreview = 'https://chuta.network/dev/public'.'/uploads/users/'.Auth::user()->id.'/projects'.'/'.$res->slug.'-'.$imagepreview1;
                $uploadedFile2 = $newSkill['image_preview'];
                $uploadedFile2->move($path, $res->slug.'-'.$imagepreview1);
                
               
                }

                else
                {
                $uploadedFile = '';   
                $uploadedFile2='';                
                }
                $res->save();

                
             }

             }
              
          
        }
       
          return $json; 
          
    }     

    /**
     * Store Profile in database
     *
     * @param mixed   $request Request Attributes
     * @param integer $user_id User ID
     *
     * @return json response
     */
    public function updateMeetingsSettings($request)
    {
        $json = array();
        $json['type'] = 'success';

        $user_id = Auth::user()->id;
        Log::info("Samee is testing");
        Log::info($request);
        $all_meetings_of_this_user = Meeting::getMeetingsSent($user_id);
        Log::info(sizeof($all_meetings_of_this_user));
        Log::info("Browser TIME");
        Log::info($request['browserTime']);
        $activeRoomsCount = 0;
 
        if($request['browserTime']){//This is because there is possibility that no meeting is booked. 1 expired on database, user click save
            foreach($all_meetings_of_this_user as $previousKey){
                if($request['browserTime'] < $previousKey->date_time){
                    Log::info("ONE EXPIRED ");
                    Log::info($request['browserTime']);
                    Log::info($previousKey->date_time);
                    $activeRoomsCount = $activeRoomsCount + 1;
                }
            }
        }



        if($activeRoomsCount == 3){
            $json['type']='alreadyBookedThree';
            return $json;
        }
        if(!$request['meetings']){
            $json['type']='success';
            return $json;
        }
        foreach ($request['meetings'] as $newKey => $newSkill) {
            $existingskill=Meeting::where([
                ['first_person', '=', $user_id],
                ['description', '=', $newSkill['description']],
                ['room_name', '=', $newSkill['roomName']],
            ])->first();
            if(!$existingskill){
                $newskill = new Meeting;
                Log::info("I am for testing purpose");
                // Log::info($newSkill);
                $newskill->date_time = $newSkill['date_time'];
                $newskill->description = $newSkill['description'];
                $newskill->room_name = $newSkill['roomName'];
                $newskill->first_person = $user_id;
                $newskill->link_url="https://chuta.network/video-calling/public/?roomName=".$newSkill['roomName'];
                $newskill->second_person = $newSkill['receiverId'];
                $newskill->save();

                $disk="custom";
                Log::info("KKK: ");
                Log::info($newSkill['date_time']);
                $entryInFile = $newSkill['date_time'].','.$newSkill['roomName'].','.$newSkill['description'];
                Storage::disk($disk)->append("video-call-ids.txt", $entryInFile);

            }else if($existingskill){
                // TODO: Remove this code:
                // $existingskill->date_time = $newSkill['date_time'];
                // $existingskill->description = $newSkill['description'];
                // $existingskill->room_name = $newSkill['roomName'];
                // $existingskill->first_person = $user_id;
                // $existingskill->second_person = $newSkill['receiverId'];
                // $existingskill->save();
            }

        }
        
        return $json;
    }
    /**
     * Add to whish list
     *
     * @param mixed   $column  Request Attributes
     * @param integer $id      ID
     * @param integer $user_id UserID
     *
     * @return json response
     */
    public function addWishlist($column, $id, $user_id)
    {
        $wishlist = array();
        $user_profile = $this::select('id')->where('user_id', $user_id)->get()->first();
        if (!empty($user_profile->id)) {
            $profile = $this::find($user_profile->id);
        } else {
            $profile = $this;
            $profile->user()->associate($user_id);
        }
        $wishlist = unserialize($profile[$column]);
        $wishlist = !empty($wishlist) && is_array($wishlist) ? $wishlist : array();
        $wishlist[] = $id;
        $wishlist = array_unique($wishlist);
        $profile->$column = serialize($wishlist);
        $profile->save();
        if (!empty($column) && ($column === 'saved_employers' || $column === 'saved_student')) {
            DB::table('followers')->insert(
                [
                    'follower' => $user_id, 'following' => $id,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ]
            );
        }

        return "success";
    }

    /**
     * Update experienceEducation
     *
     * @param mixed   $request Request Attributes
     * @param integer $user_id User ID
     *
     * @return json response
     */
    public function updateExperienceEducation($request, $user_id)
    {

        $json = array();
        $count = 0;
        $count2 = 0;
        $request_experiance = array();
        $request_education = array();
        if ($request['experience']) {
            foreach ($request['experience'] as $key => $experinence) {
                // if ($experinence['job_title'] == 'Job title' || $experinence['start_date'] == 'Start Date'
                //     || $experinence['end_date'] == 'End Date'
                // ) {
                //     $json['type'] = 'error';
                //     $json['message'] = trans('lang.empty_fields_not_allowed');
                //     return $json;
                // }
                $request_experiance[$count] = $experinence;
                $count++;
            }
        }
        if ($request['education']) {
            foreach ($request['education'] as $key => $education) {
                // if ($education['degree_title'] == 'Degree title' || $education['start_date'] == 'Start Date'
                //     || $education['end_date'] == 'End Date'
                // ) {
                //     $json['type'] = 'error';
                //     $json['message'] = trans('lang.empty_fields_not_allowed');
                //     return $json;
                // }
                $request_education[$count2] = $education;
                $count2++;
            }
        }
        $experience = !empty($request['experience']) ? serialize($request_experiance) : '';
        $education = !empty($request['education']) ? serialize($request_education) : '';
      //dd($education);
        $user_profile = $this::select('id')->where('user_id', $user_id)
            ->get()->first();
        if (!empty($user_profile->id)) {
            $profile = Profile::find($user_profile->id);
        } else {
            $profile = $this;
        }
        $profile->user()->associate($user_id);
        $profile->experience = $experience;
        $profile->education = $education;
        $profile->save();
        $json['type'] = 'success';
        return $json;
    }

    /**
     * Save Experiences.
     *
     * @param string $request req->attr
     * @param string $user_id user ID
     *
     * @return \Illuminate\Http\Response
     */
    public function savePayoutDetail($request, $user_id)
    {
        $json = array();
        $count = 0;
        $payouts = array();
        $user_profile = $this::select('id')->where('user_id', $user_id)
            ->get()->first();
        if (!empty($user_profile->id)) {
            $profile = Profile::find($user_profile->id);
        } else {
            $profile = $this;
        }
        $profile->user()->associate($user_id);
        if (!empty($request['payout_settings'])) {
            $payout_setting = $request['payout_settings'];
            $payouts['type'] = $payout_setting['type'];
            if ($payout_setting['type'] == 'paypal') {
                $payouts['paypal_email'] = $payout_setting['paypal_email'];
            } elseif ($payout_setting['type'] == 'bacs') {
                $payouts['bank_account_name'] = $payout_setting['bank_account_name'];
                $payouts['bank_account_number'] = $payout_setting['bank_account_number'];
                $payouts['bank_name'] = $payout_setting['bank_name'];
                $payouts['bank_routing_number'] = $payout_setting['bank_routing_number'];
                $payouts['bank_iban'] = $payout_setting['bank_iban'];
                $payouts['bank_bic_swift'] = $payout_setting['bank_bic_swift'];
            }
        }
        $profile->payout_settings  = serialize($payouts);
        $profile->save();
    }
}
