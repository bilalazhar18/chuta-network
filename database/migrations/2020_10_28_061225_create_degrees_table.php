<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDegreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('degrees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->timestamps();
        });

         DB::table('degrees')->insert([
            ['title' => 'Bachelor of Accounting', 'slug' => 'C10235'],
            ['title' => 'Bachelor of Accounting', 'slug' => 'C09062'],
            ['title' => 'Bachelor of Advanced Science', 'slug' => 'C10347'],
            ['title' => 'Bachelor of Advanced Science Bachelor of Creative Intelligence and Innovation', 'slug' => 'C10352'],
            ['title' => 'Bachelor of Arts Bachelor of Education', 'slug' => 'C10350'],
            ['title' => 'Bachelor of Arts Bachelor of Education (Honours)', 'slug' => 'C09082'],
            ['title' => 'Bachelor of Arts in Educational Studies ', 'slug' => 'C10209'],
            ['title' => 'Bachelor of Arts in Educational Studies Bachelor of Arts in International Studies', 'slug' => 'C10392'],
            ['title' => 'Bachelor of Biomedical Physics', 'slug' => 'C10346'],
            ['title' => 'Bachelor of Biomedical Physics (Honours) ', 'slug' => 'C09078'],
            ['title' => 'Bachelor of Biomedical Physics Bachelor of Creative Intelligence and Innovation', 'slug' => 'C10353'],
            ['title' => 'Bachelor of Biomedical Science', 'slug' => 'C10115'],
            ['title' => 'Bachelor of Biotechnology', 'slug' => 'C10172'],
            ['title' => 'Bachelor of Biotechnology (Honours)', 'slug' => 'C09022'],
            ['title' => 'Bachelor of Biotechnology Bachelor of Business', 'slug' => 'C10169'],
            ['title' => 'Bachelor of Business', 'slug' => 'C10026'],
            ['title' => 'Bachelor of Business (Honours)', 'slug' => 'C09004'],
            ['title' => 'Bachelor of Business Administration', 'slug' => 'C10340'],
            ['title' => 'Bachelor of Business Bachelor of Arts in International Studies', 'slug' => 'C10020'],
            ['title' => 'Bachelor of Business Bachelor of Creative Intelligence and Innovation', 'slug' => 'C10326'],
            ['title' => 'Bachelor of Business Bachelor of Laws', 'slug' => 'C10125'],
            ['title' => 'Bachelor of Business Bachelor of Laws (Honours)', 'slug' => 'C09084'],
            ['title' => 'Bachelor of Business Bachelor of Science in Information Technology', 'slug' => 'C10219'],
            ['title' => 'Bachelor of Communication (Creative Writing)', 'slug' => 'C10369'],
            ['title' => 'Bachelor of Communication (Creative Writing) Bachelor of Arts in International Studies', 'slug' => 'C10370'],
            ['title' => 'Bachelor of Communication (Creative Writing) Bachelor of Creative Intelligence and Innovation', 'slug' => 'C10377'],
            ['title' => 'Bachelor of Communication (Creative Writing) Bachelor of Laws', 'slug' => 'C10378'],
            ['title' => 'Bachelor of Communication (Creative Writing) Bachelor of Laws (Honours)', 'slug' => 'C09089'],
            ['title' => 'Bachelor of Communication (Digital and Social Media)', 'slug' => 'C10371'],
            ['title' => 'Bachelor of Communication (Digital and Social Media) Bachelor of Arts in International Studies', 'slug' => 'C10372'],
            ['title' => 'Bachelor of Communication (Digital and Social Media) Bachelor of Creative Intelligence and Innovation', 'slug' => 'C10359'],
            ['title' => 'Bachelor of Communication (Digital and Social Media) Bachelor of Laws ', 'slug' => 'C10379'],
            ['title' => 'Bachelor of Communication (Digital and Social Media) Bachelor of Laws (Honours)', 'slug' => 'C09091'],
            ['title' => 'Bachelor of Communication (Honours)', 'slug' => 'C09047'],
            ['title' => 'Bachelor of Communication (Journalism)', 'slug' => 'C10361'],
            ['title' => 'Bachelor of Communication (Journalism) Bachelor of Arts in International Studies', 'slug' => 'C10365'],
            ['title' => 'Bachelor of Communication (Journalism) Bachelor of Creative Intelligence and Innovation', 'slug' => 'C10376'],
            ['title' => 'Bachelor of Communication (Journalism) Bachelor of Laws (Honours)', 'slug' => 'C10380'],
            ['title' => 'Bachelor of Communication (Media Arts and Production)', 'slug' => 'C10362'],
            ['title' => 'Bachelor of Communication (Media Arts and Production) Bachelor of Arts in International Studies', 'slug' => 'C10366'],
            ['title' => 'Bachelor of Communication (Media Arts and Production) Bachelor of Creative Intelligence and Innovation ', 'slug' => 'C10373'],
            ['title' => 'Bachelor of Communication (Media Arts and Production) Bachelor of Laws', 'slug' => 'C10381'],
            ['title' => 'Bachelor of Communication (Media Arts and Production) Bachelor of Laws (Honours)', 'slug' => 'C09094'],
            ['title' => 'Bachelor of Communication (Public Communication)', 'slug' => 'C10363'],
            ['title' => 'Bachelor of Communication (Public Communication) Bachelor of Arts in International Studies', 'slug' => 'C10367'],
            ['title' => 'Bachelor of Communication (Public Communication) Bachelor of Creative Intelligence and Innovation', 'slug' => 'C10374'],
            ['title' => 'Bachelor of Communication (Public Communication) Bachelor of Laws', 'slug' => 'C10382'],
            ['title' => 'Bachelor of Communication (Public Communication) Bachelor of Laws (Honours)', 'slug' => 'C09095'],
            ['title' => 'Bachelor of Communication (Social and Political Sciences)', 'slug' => 'C10364'],
            ['title' => 'Bachelor of Communication (Social and Political Sciences) Bachelor of Arts in International Studies', 'slug' => 'C10368'],
            

















            ['title' => 'Bachelor of Communication (Social and Political Sciences) Bachelor of Creative Intelligence and Innovation', 'slug' => 'C10375'],
            ['title' => 'Bachelor of Communication (Social and Political Sciences) Bachelor of Laws', 'slug' => 'C10383'],
            ['title' => 'Bachelor of Communication (Social and Political Sciences) Bachelor of Laws (Honours)', 'slug' => 'C09096'],
            ['title' => 'Bachelor of Computing Science (Honours)', 'slug' => 'C09119'],
            ['title' => 'Bachelor of Construction Project Management', 'slug' => 'C10214'],
            ['title' => 'Bachelor of Construction Project Management Bachelor of Arts in International Studies', 'slug' => 'C10215'],
            ['title' => 'Bachelor of Creative Intelligence and Innovation (Honours)', 'slug' => 'C09122'],
            ['title' => 'Bachelor of Design (Honours) in Animation', 'slug' => 'C09056'],
            ['title' => 'Bachelor of Design (Honours) in Architecture', 'slug' => 'C09048'],
            ['title' => 'Bachelor of Design (Honours) in Fashion and Textiles', 'slug' => 'C09060'],
            ['title' => 'Bachelor of Design (Honours) in Interior Architecture', 'slug' => 'C09055'],
            ['title' => 'Bachelor of Design (Honours) in Photography', 'slug' => 'C09052'],
            ['title' => 'Bachelor of Design (Honours) in Product Design', 'slug' => 'C09059'],
            ['title' => 'Bachelor of Design (Honours) in Visual Communication', 'slug' => 'C09061'],
            ['title' => 'Bachelor of Design in Animation', 'slug' => 'C10273'],
            ['title' => 'Bachelor of Design in Animation Bachelor of Arts in International Studies', 'slug' => 'C10274'],
            ['title' => 'Bachelor of Design in Animation Bachelor of Creative Intelligence and Innovation', 'slug' => 'C10356'],
            ['title' => 'Bachelor of Design in Architecture', 'slug' => 'C10004'],
            ['title' => 'Bachelor of Design in Architecture Bachelor of Creative Intelligence and Innovation', 'slug' => 'C10325'],
            ['title' => 'Bachelor of Design in Fashion and Textiles', 'slug' => 'C10306'],
            ['title' => 'Bachelor of Design in Fashion and Textiles Bachelor of Arts in International Studies', 'slug' => 'C10307'],
            ['title' => 'Bachelor of Design in Fashion and Textiles Bachelor of Creative Intelligence and Innovation', 'slug' => 'C10321'],
            ['title' => 'Bachelor of Design in Interior Architecture', 'slug' => 'C10271'],
            ['title' => 'Bachelor of Design in Interior Architecture Bachelor of Arts in International Studies', 'slug' => 'C10272'],
            ['title' => 'Bachelor of Design in Interior Architecture Bachelor of Creative Intelligence and Innovation', 'slug' => 'C10322'],
            ['title' => 'Bachelor of Design in Photography', 'slug' => 'C10265'],
            ['title' => 'Bachelor of Design in Product Design', 'slug' => 'C10304'],
            ['title' => 'Bachelor of Design in Product Design Bachelor of Arts in International Studies', 'slug' => 'C10305'],
            ['title' => 'Bachelor of Design in Product Design Bachelor of Creative Intelligence and Innovation ', 'slug' => 'C10323'],
            ['title' => 'Bachelor of Design in Visual Communication', 'slug' => 'C10308'],
            ['title' => 'Bachelor of Design in Visual Communication Bachelor of Arts in International Studies ', 'slug' => 'C10309'],
            ['title' => 'Bachelor of Design in Visual Communication Bachelor of Creative Intelligence and Innovation', 'slug' => 'C10324'],
            ['title' => 'Bachelor of Economics', 'slug' => 'C10348'],
            ['title' => 'Bachelor of Economics Bachelor of Laws', 'slug' => 'C10386'],
            ['title' => 'Bachelor of Economics Bachelor of Laws (Honours)', 'slug' => 'C09120'],
            ['title' => 'Bachelor of Education Bachelor of Arts in International Studies', 'slug' => 'C10349'],
            ['title' => 'Bachelor of Engineering (Honours)', 'slug' => 'C09066'],
            ['title' => 'Bachelor of Engineering (Honours) Bachelor of Arts in International Studies', 'slug' => 'C09123'],
            ['title' => 'Bachelor of Engineering (Honours) Bachelor of Arts in International Studies Diploma in Professional Engineering Practice', 'slug' => 'C09124'],
            ['title' => 'Bachelor of Engineering (Honours) Bachelor of Business', 'slug' => 'C09070'],
            ['title' => 'Bachelor of Engineering (Honours) Bachelor of Business Diploma in Professional Engineering Practice', 'slug' => 'C09071'],
            ['title' => 'Bachelor of Engineering (Honours) Bachelor of Creative Intelligence and Innovation', 'slug' => 'C09076'],
            ['title' => 'Bachelor of Engineering (Honours) Bachelor of Medical Science', 'slug' => 'C09074'],
            ['title' => 'Bachelor of Engineering (Honours) Bachelor of Medical Science Diploma in Professional Engineering Practice', 'slug' => 'C09075'],
            ['title' => 'Bachelor of Engineering (Honours) Bachelor of Science', 'slug' => 'C09072'],
            ['title' => 'Bachelor of Engineering (Honours) Bachelor of Science Diploma in Professional Engineering Practice', 'slug' => 'C09073'],
            ['title' => 'Bachelor of Engineering (Honours) Diploma in Professional Engineering Practice', 'slug' => 'C09067'],
            ['title' => 'Bachelor of Engineering Science', 'slug' => 'C10066'],
            ['title' => 'Bachelor of Engineering Science Bachelor of Laws', 'slug' => 'C10136'],
            ['title' => 'Bachelor of Engineering Science Bachelor of Laws (Honours)', 'slug' => 'C09087'],
            ['title' => 'Bachelor of Entrepreneurship (Honours)', 'slug' => 'C09101'],
            ['title' => 'Bachelor of Environmental Biology', 'slug' => 'C10223'],
            ['title' => 'Bachelor of Forensic Science', 'slug' => 'C10387'],
            





            ['title' => 'Bachelor of Forensic Science', 'slug' => 'C09100'],
            ['title' => 'Bachelor of Forensic Science Bachelor of Arts in International Studies', 'slug' => 'C10388'],
            ['title' => 'Bachelor of Forensic Science Bachelor of Creative Intelligence and Innovation', 'slug' => 'C10389'],
            ['title' => 'Bachelor of Forensic Science Bachelor of Laws', 'slug' => 'C10391'],
            ['title' => 'Bachelor of Forensic Science Bachelor of Laws (Honours)', 'slug' => 'C09121'],
            ['title' => 'Bachelor of Global Studies', 'slug' => 'C10264'],
            ['title' => 'Bachelor of Health Science', 'slug' => 'C10360'],
            ['title' => 'Bachelor of Health Science (Honours)', 'slug' => 'C09049'],
            ['title' => 'Bachelor of Information Systems', 'slug' => 'C10395'],
            ['title' => 'Bachelor of Information Systems Bachelor of Business', 'slug' => 'C10278'],
            ['title' => 'Bachelor of Information Technology', 'slug' => 'C10143'],
            ['title' => 'Bachelor of Landscape Architecture (Honours)', 'slug' => 'C09079'],
            ['title' => 'Bachelor of Laws', 'slug' => 'C10124'],
            ['title' => 'Bachelor of Laws (Honours)', 'slug' => 'C09083'],
            ['title' => 'Bachelor of Laws (Honours) Bachelor of Arts in International Studies', 'slug' => 'C09097'],
            ['title' => 'Bachelor of Laws (Honours) Bachelor of Creative Intelligence and Innovation', 'slug' => 'C09098'],
            ['title' => 'Bachelor of Laws Bachelor of Arts in International Studies', 'slug' => 'C10129'],
            ['title' => 'Bachelor of Laws Bachelor of Creative Intelligence and Innovation', 'slug' => 'C10338'],
            ['title' => 'Bachelor of Management', 'slug' => 'C10342'],
            ['title' => 'Bachelor of Management (Honours)', 'slug' => 'C09081'],
            ['title' => 'Bachelor of Management Bachelor of Arts in International Studies', 'slug' => 'C10343'],
            ['title' => 'Bachelor of Management Bachelor of Creative Intelligence and Innovation', 'slug' => 'C10355'],
            ['title' => 'Bachelor of Marine Biology', 'slug' => 'C10228'],
            ['title' => 'Bachelor of Medical Science', 'slug' => 'C10184'],
            ['title' => 'Bachelor of Medical Science (Honours)', 'slug' => 'C09031'],
            ['title' => 'Bachelor of Medical Science Bachelor of Arts in International Studies', 'slug' => 'C10167'],
            ['title' => 'Bachelor of Medical Science Bachelor of Business', 'slug' => 'C10163'],
            ['title' => 'Bachelor of Medical Science Bachelor of Laws', 'slug' => 'C10131'],
            ['title' => 'Bachelor of Medical Science Bachelor of Laws (Honours)', 'slug' => 'C09086'],
            ['title' => 'Bachelor of Medicinal Chemistry', 'slug' => 'C10275'],
            ['title' => 'Bachelor of Medicinal Chemistry (Honours)', 'slug' => 'C09077'],
            ['title' => 'Bachelor of Medicinal Chemistry Bachelor of Creative Intelligence and Innovation', 'slug' => 'C10354'],
            ['title' => 'Bachelor of Midwifery', 'slug' => 'C10225'],
            ['title' => 'Bachelor of Midwifery (Honours)', 'slug' => 'C09051'],
            ['title' => 'Bachelor of Midwifery Bachelor of Creative Intelligence and Innovation', 'slug' => 'C10329'],
            ['title' => 'Bachelor of Music and Sound Design', 'slug' => 'C10276'],
            ['title' => 'Bachelor of Music and Sound Design Bachelor of Arts in International Studies', 'slug' => 'C10277'],
            ['title' => 'Bachelor of Nursing', 'slug' => 'C10122'],
            ['title' => 'Bachelor of Nursing (Honours)', 'slug' => 'C09018'],
            ['title' => 'Bachelor of Nursing Bachelor of Arts in International Studies', 'slug' => 'C10123'],
            ['title' => 'Bachelor of Nursing Bachelor of Creative Intelligence and Innovation', 'slug' => 'C10351'],
            ['title' => 'Bachelor of Property Economics', 'slug' => 'C10310'],
            ['title' => 'Bachelor of Property Economics (Honours)', 'slug' => 'C09063'],
            ['title' => 'Bachelor of Property Economics Bachelor of Arts in International Studies', 'slug' => 'C10320'],
            ['title' => 'Bachelor of Science', 'slug' => 'C10242'],
            ['title' => 'Bachelor of Science (Honours) in Analytics', 'slug' => 'C09099'],
            ['title' => 'Bachelor of Science (Honours) in Applied Chemistry', 'slug' => 'C09026'],
            ['title' => 'Bachelor of Science (Honours) in Applied Physics', 'slug' => 'C09035'],
            ['title' => 'Bachelor of Science (Honours) in Biomedical Science', 'slug' => 'C09023'],
            ['title' => 'Bachelor of Science (Honours) in Environmental Science', 'slug' => 'C09029'],
            ['title' => 'Bachelor of Science (Honours) in Information Technology', 'slug' => 'C09019'],
            ['title' => 'Bachelor of Science (Honours) in Mathematics', 'slug' => 'C09020'],
            ['title' => 'Bachelor of Science (Honours) in Nanotechnology', 'slug' => 'C09046'],
            ['title' => 'Bachelor of Science Bachelor of Arts in International Studies', 'slug' => 'C10243'],
            ['title' => 'Bachelor of Science Bachelor of Business', 'slug' => 'C10162'],
            ['title' => 'Bachelor of Science Bachelor of Creative Intelligence and Innovation', 'slug' => 'C10330'],
            ['title' => 'Bachelor of Science Bachelor of Laws', 'slug' => 'C10126'],
            ['title' => 'Bachelor of Science Bachelor of Laws (Honours)', 'slug' => 'C09085'],
            ['title' => 'Bachelor of Science in Analytics', 'slug' => 'C10384'],
            ['title' => 'Bachelor of Science in Analytics Bachelor of Arts in International Studies', 'slug' => 'C10385'],
           
            ['title' => 'Bachelor of Science in Games Development', 'slug' => 'C10229'],
            ['title' => 'Bachelor of Science in Information Technology', 'slug' => 'C10148'],
            ['title' => 'Bachelor of Science in Information Technology Bachelor of Arts in International Studies', 'slug' => 'C10239'],
            ['title' => 'Bachelor of Science in Information Technology Bachelor of Creative Intelligence and Innovation', 'slug' => 'C10327'],
            ['title' => 'Bachelor of Science in Information Technology Bachelor of Laws', 'slug' => 'C10245'],
            ['title' => 'Bachelor of Science in Information Technology Bachelor of Laws (Honours)', 'slug' => 'C09088'],
            ['title' => 'Bachelor of Science in Information Technology Diploma in Information Technology Professional Practice', 'slug' => 'C10345'],
            ['title' => 'Bachelor of Sport and Exercise Management', 'slug' => 'C10301'],
            ['title' => 'Bachelor of Sport and Exercise Management Bachelor of Arts in International Studies', 'slug' => 'C10303'],
            ['title' => 'Bachelor of Sport and Exercise Science', 'slug' => 'C10300'],
            ['title' => 'Bachelor of Sport and Exercise Science (Honours)', 'slug' => 'C09057'],
            ['title' => 'Bachelor of Sport and Exercise Science Bachelor of Arts in International Studies', 'slug' => 'C10302'],
            ['title' => 'Bachelor of Sport and Exercise Science Bachelor of Creative Intelligence and Innovation', 'slug' => 'C10328'],
            ['title' => 'Bachelor of Technology and Innovation ', 'slug' => 'C10390'],
            
            

             ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('degrees');
    }
}
