<?php


use App\SmGeneralSettings;
use App\InfixModuleManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;
use App\Http\Controllers\Admin\Inventory\SmItemSellController;
use App\Http\Controllers\Admin\Inventory\SmItemReceiveController;


return new class extends Migration
{
    function replace_array_recursive(string $needle, string $replace, array &$haystack)
    {
        array_walk_recursive(
            $haystack,
            function (&$item, $key, $data) {
                $item = str_replace($data['needle'], $data['replace'], $item);
                return $item;
            },
            ['needle' => $needle, 'replace' => $replace]
        );
    }

    public function up(): void
    {
        $exist = InfixModuleManager::where('name','InAppLiveClass')->count();
        if($exist > 1){
            $del =  InfixModuleManager::where('name','InAppLiveClass')->first();
            $del->delete();
        }
        
        $schools = App\SmSchool::get();
        foreach ($schools as $school) {
            $filesInFolder = Illuminate\Support\Facades\File::files(resource_path('/views/themes/edulia/demo/'));
            foreach ($filesInFolder as $path) {
                $file = pathinfo($path);
                if (file_exists($file['dirname'] . '/' . $file['basename'])) {
                    $file_content =  file_get_contents(($file['dirname'] . '/' . $file['basename']));
                    $file_data = json_decode($file_content, true);
                    $this->replace_array_recursive("[App_url]", (url('/')), $file_data);
                    if ($file_data) {
                        $check_exist  = DB::table(config('pagebuilder.db_prefix', 'infixedu__') . 'pages')->where('school_id', $school->id)->where('slug', $file_data['slug'])->first();
                        if (!$check_exist) {
                            DB::table(config('pagebuilder.db_prefix', 'infixedu__') . 'pages')->insert(
                                [
                                    'name' => $file_data['name'],
                                    'title' => $file_data['title'],
                                    'description' => $file_data['description'],
                                    'slug' => $file_data['slug'],
                                    'settings' => json_encode($file_data['settings']),
                                    'home_page' => $file_data['home_page'],
                                    'status' => 'published',
                                    'is_default' => 1,
                                    'school_id' => $school->id
                                ]
                            );
                        }
                    }
                }
            }
        }

        $beh = Permission::where('sidebar_menu','behaviour_records')->whereNull('parent_route')->first();
        $beh_com = Permission::where('route','behaviour_records.incident_comment')->first();
        if($beh){
            $beh->is_student = 0; 
            $beh->is_parent = 0; 
            $beh->save(); 
        }
        if($beh_com){
            $beh_com->is_student = 0; 
            $beh_com->is_parent = 0; 
            $beh_com->save(); 
        }

        
        $generalSettings = SmGeneralSettings::first();
        if($generalSettings){
            $generalSettings->software_version = '8.1.1';
        }
        
        $downloadCenter = InfixModuleManager::where('name', 'DownloadCenter')->where('is_default', 0)->first();
        if($downloadCenter){
            $downloadCenter->delete();
        }

        SmItemReceiveController::updateSmItemReceiveDatabase();
        SmItemReceiveController::updateSmItemReceiveChildrenDatabase();

        SmItemSellController::updateSmItemSellDatabase();
        SmItemSellController::updateSmItemSellChildrenDatabase();
        
        $tawk_to_chat = Permission::where('route', 'tawkSetting')->where('lang_name', 'system_settings.tawk_to_chat')->first();
        if($tawk_to_chat){
            $tawk_to_chat->delete();
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
