<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSmStudentCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        return ;
        if (!Schema::hasColumn('sm_student_certificates', 'layout')) {
            Schema::table('sm_student_certificates', function (Blueprint $table) {
                $table->integer('layout')->nullable()->comment('1 = Portrait, 2 =  Landscape');
            });
        }
        if (!Schema::hasColumn('sm_student_certificates', 'body_font_family')) {
            Schema::table('sm_student_certificates', function (Blueprint $table) {
                $table->string('body_font_family')->nullable()->default('Arial')->comment('body_font_family');
            });
        }
        if (!Schema::hasColumn('sm_student_certificates', 'body_font_size')) {
            Schema::table('sm_student_certificates', function (Blueprint $table) {
                $table->string('body_font_size')->nullable()->default('2em')->comment('');
            });
        }
        if (!Schema::hasColumn('sm_student_certificates', 'height')) {
            Schema::table('sm_student_certificates', function (Blueprint $table) {
                $table->string('height', 50)->nullable()->comment('Height in mm');
            });
        }
        if (!Schema::hasColumn('sm_student_certificates', 'width')) {
            Schema::table('sm_student_certificates', function (Blueprint $table) {
                $table->string('width', 50)->nullable()->comment('width in mm');
            });
        }
        if (!Schema::hasColumn('sm_student_certificates', 'default_for')) {
            Schema::table('sm_student_certificates', function (Blueprint $table) {
                $table->string('default_for', 50)->nullable()->comment('default_for course');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
