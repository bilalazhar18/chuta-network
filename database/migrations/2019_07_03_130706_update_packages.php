<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Package;

class UpdatePackages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $student_trial_package = Package::select('id')->where('slug', 'trial-student')->first();
        if (!empty($student_trial_package)) {
            $package = Package::find($student_trial_package->id);
            $package->options = 'a:7:{s:14:"no_of_connects";s:2:"10";s:12:"no_of_skills";s:1:"3";s:14:"no_of_services";s:2:"10";s:23:"no_of_featured_services";s:1:"3";s:8:"duration";s:2:"10";s:13:"banner_option";s:4:"true";s:12:"private_chat";s:4:"true";}';
            $package->save();
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
