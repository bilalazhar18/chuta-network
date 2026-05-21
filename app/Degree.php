<?php

/**
 * Class Degree and all of its functions.
 *
 * @category Worketic
 *
 * @package Worketic
 * @author  Amentotech <theamentotech@gmail.com>
 * @license http://www.amentotech.com Amentotech
 * @link    http://www.amentotech.com
 */
namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;
use File;
use Storage;

/**
 * Class Degree
 *
 */
class Degree extends Model
{
    /**
     * Fillables for the database
     *
     * @access protected
     * @var    array $fillable
     */
    protected $fillable = array(
        'title', 'slug', 'relation_type', 'flag', 'description'
    );

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
     * Get the users for the Degree.
     *
     * @return relation
     */
    public function users()
    {
        return $this->hasMany('App\User');
    }

    /**
     * Get the job for the Degree.
     *
     * @return relation
     */
    public function jobs()
    {
        return $this->hasMany('App\Job');
    }

    /**
     * Get the services for the Degree.
     *
     * @return relation
     */
    public function services()
    {
        return $this->hasMany('App\Service');
    }

    /**
     * Set slug before saving in DB
     *
     * @param string $value value
     *
     * @access public
     *
     * @return string
     */
    public function setSlugAttribute($value)
    {
        if (!empty($value)) {
            $temp = str_slug($value, '-');
            if (!Degree::all()->where('slug', $temp)->isEmpty()) {
                $i = 1;
                $new_slug = $temp . '-' . $i;
                while (!Degree::all()->where('slug', $new_slug)->isEmpty()) {
                    $i++;
                    $new_slug = $temp . '-' . $i;
                }
                $temp = $new_slug;
            }
            $this->attributes['slug'] = $temp;
        }
    }

    /**
     * For saving degrees in Database
     *
     * @param mixed $request get file
     *
     * @return \Illuminate\Http\Response
     */
    public function storeDegree($request)
    {
        $parent_cat = filter_var($request['parent_degree'], FILTER_VALIDATE_INT);
        $this->title = filter_var($request['title'], FILTER_SANITIZE_STRING);
        $this->slug = filter_var($request['title'], FILTER_SANITIZE_STRING);
        $this->description = filter_var($request['description'], FILTER_SANITIZE_STRING);
        $this->parent = $parent_cat;

        $old_path = Helper::PublicPath() . '/uploads/degrees/temp';
        if (!empty($request['uploaded_image'])) {
            $filename = $request['uploaded_image'];
            if (file_exists($old_path . '/' . $request['uploaded_image'])) {
                $new_path = Helper::PublicPath().'/uploads/degrees/';
                if (!file_exists($new_path)) {
                    File::makeDirectory($new_path, 0755, true, true);
                }
                $filename = time() . '-' . $request['uploaded_image'];
                rename($old_path . '/' . $request['uploaded_image'], $new_path . '/' . $filename);
                rename($old_path . '/small-' . $request['uploaded_image'], $new_path . '/small-' . $filename);
                rename($old_path . '/medium-' . $request['uploaded_image'], $new_path . '/medium-' . $filename);
            }
            $this->flag = filter_var($filename, FILTER_SANITIZE_STRING);
        } else {
            $this->flag = null;
        }
        $this->save();
    }


    /**
     * Update Degree in database
     *
     * @param mixed   $request get req attributes
     * @param integer $id      get Degree ID
     *
     * @return \Illuminate\Http\Response
     */
    public function updateDegree($request, $id)
    {
        $degree = self::find($id);
        if ($degree->title != $request['title']) {
            $degree->slug = filter_var($request['title'], FILTER_SANITIZE_STRING);
        }
        $degree->title = filter_var($request['title'], FILTER_SANITIZE_STRING);
        $degree->description = filter_var($request['abstract'], FILTER_SANITIZE_STRING);
        $parent_cat = filter_var($request['parent_degree'], FILTER_VALIDATE_INT);
        $degree->parent = $parent_cat;
        $old_path = Helper::PublicPath() . '/uploads/degrees/temp';
        if (!empty($request['uploaded_image'])) {
            $new_path = Helper::PublicPath().'/uploads/degrees/';
            $filename = $request['uploaded_image'];
            if (file_exists($old_path . '/' . $request['uploaded_image'])) {
                if (!file_exists($new_path)) {
                    File::makeDirectory($new_path, 0755, true, true);
                }
                $filename = time() . '-' . $request['uploaded_image'];
                rename($old_path . '/' . $request['uploaded_image'], $new_path . '/' . $filename);
                rename($old_path . '/small-' . $request['uploaded_image'], $new_path . '/small-' . $filename);
                rename($old_path . '/medium-' . $request['uploaded_image'], $new_path . '/medium-' . $filename);
            }
            $degree->flag = filter_var($filename, FILTER_SANITIZE_STRING);

        } else {
            $degree->flag = null;
        }
        $degree->save();
    }

    /**
     * Get Degree ancestors
     *
     * @param integer $parent_id  get req attributes
     * @param integer $marginleft get Degree ID
     *
     * @return output
     */
    public function getAncestors($parent_id = 0, $marginleft = 0)
    {
        $query = DB::table('degrees')->select('*')->where('parent', '=', $parent_id)->get();
        $output = '';

        if ($parent_id == 0) {
            $marginleft = 0;
        } else {
            $marginleft = $marginleft + 48;
        }
        if ($query->count() > 0) {
            foreach ($query as $child) {
                $output .= $this->getAncestors($child->id, $marginleft);
            }
        }
        return $output;
    }

    /**
     * Degree Ancestors
     *
     * @param integer $id get req ID
     *
     * @return $ancestors
     */
    public function ancestors($id)
    {
        $ancestors = DB::table('degrees')->select('*')->where('id', '=', $id)->get();
        while ($ancestors->last() && $ancestors->last()->parent !== null) {
            $parent = DB::table('degrees')->select('*')->where('id', '=', $ancestors->last()->parent)->get();
            $ancestors = $ancestors->merge($parent);
            dd($ancestors);
        }

        return $ancestors;
    }
}
