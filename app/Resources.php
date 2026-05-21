<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Resources extends Model
{
        /**
     * Fillables for the database
     *
     * @access protected
     * @var    array $fillable
     */
    protected $fillable = [
        'user_id', 'title', 'description', 'price', 'name_of_file', 'url_of_file', 'slug'
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

    public static function getStudentResources($user_id)
    {
        return DB::table('resources')->where('user_id', $user_id)
            ->get()->toArray();
    }

    public static function getStudentResourcesWithTransactionDetails($user_id)
    {
        return DB::table('resources')
        ->selectRaw('resources.*, transactions.id as transaction_id')
        ->leftJoin('transactions', function($join) use ($user_id)
        {
            $join->on('resources.slug', '=', 'transactions.resource_slug');
        })
        ->where('transactions.buyer_id', $user_id)
            ->get();
    }

    public static function getpurchasedResources($slug)
    {
        return DB::table('resources')->where('slug', $slug)
            ->get()->toArray();
    }
    public static function getAllResources()
    {
        return DB::table('resources')
            ->get()->toArray();
    }
    
    public static function getSearchResult(
        $keyword,
        $search_categories,
        $search_locations,
        $search_languages,
        $search_delivery_time,
        $search_response_time
    ) {
        $json = array();
        Log::info("QUERY");
        Log::info($keyword);
        Log::info($search_categories);
        $services = Resources::select('*');
        $courses_names = array();
        $filters = array();
        $filters['type'] = 'service';
        if (!empty($keyword)) {
            $filters['s'] = $keyword;
            $services->where('title', 'like', '%' . $keyword . '%');
        };
        if (!empty($search_categories)) {
            $filters['category'] = $search_categories;
            foreach ($search_categories as $key => $search_category) {
                $categor_obj = Skill::where('slug', $search_category)->first();
                Log::info($categor_obj);
                array_push($courses_names, $categor_obj->title);
                // Log::info($services);
                // foreach ($category_services as $id) {
                //     $service_id[] = $id;
                // }
                Log::info("I am courses name");
                Log::info($courses_names);
            }
            $services->whereIn('course_name', $courses_names);
        }




        $services = $services->orderByRaw("updated_at DESC")->paginate(7)->setPath('');
        Log::info("Result:");
        Log::info($services);
        $json['services'] = $services;
        return $json;
    }



    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $table = 'resources';
}
