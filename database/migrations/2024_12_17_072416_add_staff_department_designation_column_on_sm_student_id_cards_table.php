<?php

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
        if (!Schema::hasColumn('sm_student_id_cards', 'staff_department')) {
            Schema::table('sm_student_id_cards',function($table){
                $table->integer('staff_department')->default(0);
            }); 
        }

        if (!Schema::hasColumn('sm_student_id_cards', 'staff_designation')) {
            Schema::table('sm_student_id_cards',function($table){
                $table->integer('staff_designation')->default(0);
            }); 
        }
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasColumn('sm_student_id_cards', 'staff_department')) {
            Schema::table('sm_student_id_cards',function($table){
                $table->dropColumn('staff_department');
            }); 
        }

        if(Schema::hasColumn('sm_student_id_cards', 'staff_designation')) {
            Schema::table('sm_student_id_cards',function($table){
                $table->dropColumn('staff_designation');
            }); 
        }
    }
};
