<?php

use App\SmGeneralSettings;
use App\InfixModuleManager;
use App\SmHeaderMenuManager;
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
        $exist = InfixModuleManager::where('name', 'Certificate')->first();
        if (!$exist) {
            $s3 = new InfixModuleManager();
            $s3->name = "Certificate";
            $s3->email = 'support@spondonit.com';
            $s3->notes = "This is the module to generate Certificate's for students and employees.";
            $s3->version = "1.0";
            $s3->update_url = "https://spondonit.com/contact";
            $s3->is_default = 0;
            $s3->addon_url = "maito:support@spondonit.com";
            $s3->installed_domain = url('/');
            $s3->activated_date = date('Y-m-d');
            $s3->save();
        }

        $extraContactPage = SmHeaderMenuManager::where([
            ['type', 'sPages'],
            ['title', 'Contact'],
            ['link', '/contact-us'],
            ['parent_id', null],
        ])->latest()->first();
        if ($extraContactPage) {
            $extraContactPage->delete();
        }

        $generalSettings = SmGeneralSettings::first();
        if ($generalSettings) {
            $generalSettings->software_version = '8.1.2';
            $generalSettings->update();
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
