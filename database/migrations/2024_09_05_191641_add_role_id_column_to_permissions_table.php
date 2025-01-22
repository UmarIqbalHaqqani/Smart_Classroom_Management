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
        Schema::table('permissions', function (Blueprint $table) {
            if(!Schema::hasColumn($table->getTable(), 'role_id')){
                $table->integer('role_id')->nullable();

            }
            if(!Schema::hasColumn($table->getTable(), 'custom_menu_id')){
                $table->integer('custom_menu_id')->nullable();

            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('role_id');
            $table->dropColumn('custom_menu_id');
        });
    }
};
