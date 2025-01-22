<?php

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
        Schema::create('sm_photo_galleries', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('feature_image')->nullable();
            $table->string('gallery_image')->nullable();
            $table->boolean('is_publish')->default(true);
            $table->integer('position')->default(0);
            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            $table->timestamps();
        });

        DB::table('sm_photo_galleries')->insert([
            [
                'parent_id' => Null,
                'name' => 'Pre-Primary',
                'description' => "Fusce semper, nibh eu sollicitudin imperdiet, dolo",
                'feature_image' => "public/uploads/theme/edulia/photo_gallery/gallery-1.jpg",
                'gallery_image' => Null,
            ],
            [
                'parent_id' => Null,
                'name' => 'Kindergarden',
                'description' => "Fusce semper, nibh eu sollicitudin imperdiet, dolo",
                'feature_image' => "public/uploads/theme/edulia/photo_gallery/gallery-1.jpg",
                'gallery_image' => Null,
            ],
            [
                'parent_id' => Null,
                'name' => 'Celebration',
                'description' => "Fusce semper, nibh eu sollicitudin imperdiet, dolo",
                'feature_image' => "public/uploads/theme/edulia/photo_gallery/gallery-1.jpg",
                'gallery_image' => Null,
            ],
            [
                'parent_id' => Null,
                'name' => 'Recreation Centre',
                'description' => "Fusce semper, nibh eu sollicitudin imperdiet, dolo",
                'feature_image' => "public/uploads/theme/edulia/photo_gallery/gallery-1.jpg",
                'gallery_image' => Null,
            ],
            [
                'parent_id' => Null,
                'name' => 'Facilities',
                'description' => "Fusce semper, nibh eu sollicitudin imperdiet, dolo",
                'feature_image' => "public/uploads/theme/edulia/photo_gallery/gallery-1.jpg",
                'gallery_image' => Null,
            ],
            [
                'parent_id' => Null,
                'name' => 'Activities',
                'description' => "Fusce semper, nibh eu sollicitudin imperdiet, dolo",
                'feature_image' => "public/uploads/theme/edulia/photo_gallery/gallery-1.jpg",
                'gallery_image' => Null,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sm_photo_galleries');
    }
};
