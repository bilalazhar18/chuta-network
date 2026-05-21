<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Transactions extends Model
{
        /**
     * Fillables for the database
     *
     * @access protected
     * @var    array $fillable
     */
    protected $fillable = [
        'seller_id', 'seller_name', 'buyer_id', 'buyer_name', 'buyers_rating', 'resource_slug', 'comment'
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
    public static function getPurchasedTransactions($user_id)
    {
        return DB::table('transactions')->where('buyer_id', $user_id)
            ->get()->toArray();
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $table = 'transactions';
}
