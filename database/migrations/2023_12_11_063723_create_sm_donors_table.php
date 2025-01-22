<?php

use App\SmBaseSetup;
use App\Models\SmDonor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sm_donors', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 200)->nullable();
            $table->string('profession', 200)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('email', 200)->nullable();
            $table->string('mobile', 200)->nullable();
            $table->string('photo')->nullable();
            $table->string('age', 200)->nullable();
            $table->string('current_address', 500)->nullable();
            $table->string('permanent_address', 500)->nullable();
            $table->tinyInteger('show_public')->default(1);
            $table->text('custom_field')->nullable();
            $table->string('custom_field_form_name')->nullable();
            $table->timestamps();

            $table->integer('bloodgroup_id')->nullable()->unsigned();
            $table->foreign('bloodgroup_id')->references('id')->on('sm_base_setups')->onDelete('set null');

            $table->integer('religion_id')->nullable()->unsigned();
            $table->foreign('religion_id')->references('id')->on('sm_base_setups')->onDelete('set null');

            $table->integer('gender_id')->nullable()->unsigned();
            $table->foreign('gender_id')->references('id')->on('sm_base_setups')->onDelete('set null');

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
        });

        // Content For Demo Data Start
        $bloodgroup = SmBaseSetup::where('base_group_id', '=', '3')->where('base_setup_name', 'A+')->first();
        $religion = SmBaseSetup::where('base_group_id', '=', '2')->where('base_setup_name', 'Islam')->first();
        $gender = SmBaseSetup::where('base_group_id', '=', '1')->where('base_setup_name', 'Male')->first();
        $datas = [
            ['Abdur Rahman', 'Doctor', date("Y-m-d", strtotime('1990-12-12')), "abdurrahman@infixedu.com", '+881235854', $bloodgroup->id, $religion->id, $gender->id],
            [' Md Rahim ', 'Farmer', date("Y-m-d", strtotime('1993-08-05')), "rahim@infixedu.com", '+8855525412', $bloodgroup->id, $religion->id, $gender->id],
            ['Md Malek', 'Engineer', date("Y-m-d", strtotime('1990-12-12')), "malek@infixedu.com", '+8852526698', $bloodgroup->id, $religion->id, $gender->id],
        ];

        foreach($datas as $key => $data){
            $key++;
            $storeData = new SmDonor();
            $storeData->full_name = $data[0];
            $storeData->profession = $data[1];
            $storeData->date_of_birth = $data[2];
            $storeData->email = $data[3];
            $storeData->mobile = $data[4];
            $storeData->bloodgroup_id = $data[5];
            $storeData->religion_id = $data[6];
            $storeData->gender_id = $data[7];
            $storeData->photo = "public/uploads/theme/edulia/donor/default_donor.jpg";
            $storeData->current_address = "Dhaka, Bangladesh";
            $storeData->permanent_address = "Dhaka, Bangladesh";
            $storeData->school_id = 1;
            $storeData->save();
        }
        // Content For Demo Data End
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sm_donors');
    }
};
