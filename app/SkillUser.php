<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class SkillUser extends Model
{
      protected $table = 'skill_user';
        /**
     * Fillables for the database
     *
     * @access protected
     * @var    array $fillable
     */
    protected $fillable = [
         'user_id', 'skill_id', 'cgpa', 'skill_rating', 'starting_date', 'completing_date','is_tutor'
        // add all other fields
    ];

        /**
     * Protected Date
     *
     * @access protected
     * @var    array $dates
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

        /**
     * For saving skills in Database
     *
     * @param mixed $request get req attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function scopeSkillsUsers($request)
    {
        // Log::info("Request");
        // Log::info($request);
        if (!empty($request)) {
            $this->skill_rating = $request['rating'];
            // $this->starting_date = filter_var($request['skill_title'], FILTER_SANITIZE_STRING);
            // $this->completing_date = filter_var($request['skill_desc'], FILTER_SANITIZE_STRING);
            return $this->save();
        }
    }

    public function tutorget()
    {
        $this->belongsTo('App/Users','id');
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
  
}
