<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sm_exam_types', function (Blueprint $table) {
            if(!Schema::hasColumn('sm_exam_types', 'average_mark')){
                $table->float('average_mark')->default(0);
            }           
        });
        Schema::table('sm_exam_types', function (Blueprint $table) {
            if(!Schema::hasColumn('sm_exam_types', 'is_average')){
                $table->tinyInteger('is_average')->default(0);
            }           
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sm_exam_types_extension');
    }
};
