<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sm_general_settings', function (Blueprint $table) {
            if(!Schema::hasColumn($table->getTable(), 'role_based_sidebar')){
                $table->boolean('role_based_sidebar')->default(false)->comment('0 for user based sidebar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sm_general_settings', function (Blueprint $table) {
            $table->dropColumn('role_based_sidebar');
        });
    }
};
