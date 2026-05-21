<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class MeetingInvoice extends Model
{
        /**
     * Fillables for the database
     *
     * @access protected
     * @var    array $fillable
     */
    protected $fillable = [
        'sender_Id', 'receiver_Id', 'amount', 'description', 'meeting_slot','status', 'transaction_Id'
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
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $table = 'meeting_invoice';
}
