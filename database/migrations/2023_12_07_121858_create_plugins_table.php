<?php

use App\Models\Plugin;
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
        Schema::create('plugins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_enable')->default(false);
            $table->string('availability')->default('both');
            $table->boolean('show_admin_panel')->default(false);
            $table->boolean('show_website')->default(true);
            $table->string('showing_page')->default('all');
            $table->string('applicable_for')->nullable();
            $table->string('position')->nullable();
            $table->string('short_code',50)->nullable();
            $table->integer('school_id')->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            $table->timestamps();
        });


        $defaults = ['tawk','messenger'];
        foreach($defaults as $default){
            $plugin = new Plugin();
            $plugin->name = $default;
            $plugin->school_id = 1;
            $plugin->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plugins');
    }
};
