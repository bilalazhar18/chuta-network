<?php

/**
 * Class CreateEmailTypesTable
 *
 * @category Worketic
 *
 * @package Worketic
 * @author  Amentotech <theamentotech@gmail.com>
 * @license http://www.amentotech.com Amentotech
 * @version <1.0.0>
 * @link    http://www.amentotech.com
 */
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateEmailTypesTable
 */
class CreateEmailTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'email_types',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('role_id')->nullable();
                $table->enum(
                    'email_type',
                    [
                        'new_user', 'verification_code', 'lost_password', 'account_verification', 'invitation', 'contact_form_received',
                        'admin_email_registration', 'admin_email_delete_account', 'admin_email_report_employer', 'admin_email_report_project',
                        'admin_email_report_student', 'admin_email_new_job_posted', 'admin_email_job_completed', 'employer_email_proposal_received',
                        'employer_email_new_job_posted', 'employer_email_proposal_message', 'employer_email_package_subscribed',
                        'student_email_new_proposal_submitted', 'student_email_hire_student', 'student_email_send_offer',
                         'student_email_cancel_job', 'student_email_proposal_message', 'student_email_package_subscribed',
                         'student_email_job_completed', 'admin_email_dispute_raised', 'reset_password_email','admin_email_cancel_job'
                    ]
                );
                $table->text('variables');
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_types');
    }
}
