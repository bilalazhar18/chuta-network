<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Meeting extends Model
{
        /**
     * Fillables for the database
     *
     * @access protected
     * @var    array $fillable
     */
    protected $fillable = [
        'date_time', 'room_name', 'description'
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

    public static function getMeetingsSent($user_id)
    {
        return DB::table('meetings')->where('first_person', $user_id)
            ->get()->toArray();
    }

    public static function getMeetingsReceived($user_id)
    {
        return DB::table('meetings')->where('second_person', $user_id)
            ->get()->toArray();
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $table = 'meetings';
}
