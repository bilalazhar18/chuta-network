<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Rooms extends Model
{
        /**
     * Fillables for the database
     *
     * @access protected
     * @var    array $fillable
     */
    protected $fillable = [
        'room_name', 'sender_id', 'receiver_id'
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
    public function getUserRooms($request)
    {
        // Log::info("Request");
        // Log::info($request);
        // if (!empty($request)) {
        //     $this->skill_rating = $request['rating'];
        //     // $this->starting_date = filter_var($request['skill_title'], FILTER_SANITIZE_STRING);
        //     // $this->completing_date = filter_var($request['skill_desc'], FILTER_SANITIZE_STRING);
        //     return $this->save();
        // }
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $table = 'rooms';
}
