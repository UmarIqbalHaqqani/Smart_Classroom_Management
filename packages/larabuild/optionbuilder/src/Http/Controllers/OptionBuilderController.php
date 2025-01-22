<?php

namespace Larabuild\Optionbuilder\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Larabuild\Optionbuilder\Facades\Settings;

class OptionBuilderController extends Controller {


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $sections = [];

        $dir_files = glob(app_path() . '/Optionbuilder/*');
        if (!empty($dir_files)) {
            foreach ($dir_files as $file) {

                $setting = include  $file;
                if (
                    !empty($setting['section'])
                    && !empty($setting['section']['id'])
                    && empty($setting['section']['tabs'])
                    && !array_key_exists($setting['section']['id'], $sections)
                ) {
                    $sections[$setting['section']['id']] = $setting['section'];
                    $sections[$setting['section']['id']]['fields'] = $setting['fields'] ?? array();
                } elseif (
                    !empty($setting['section'])
                    && !empty($setting['section']['id'])
                    && !empty($setting['section']['tabs'])
                    && !array_key_exists($setting['section']['id'], $sections)
                ) {
                    $sections[$setting['section']['id']] = $setting['section'];
                    if (!empty($setting['fields'])) {
                        foreach ($setting['fields'] as $field) {
                            $sections[$setting['section']['id']]['fields'][$field['tab_id'] ?? __('option_builder.tab')][] = $field;
                        }
                    }
                }
            }
        }

        return view('optionbuilder::index', compact('sections'));
    }

    /**
     * Update key values for particular Group
     *
     * @return \Illuminate\Http\Response
     */
    public function updateSettings(Request $request) {

        $data = $request->all();
        parse_str($data['data'], $form_data);
        parse_str($data['env_data'], $env_data);
        if (!empty($form_data) && !empty($data['section_key'])) {
            unset($form_data['_method']);
            unset($form_data['_token']);
            Settings::resetSection($data['section_key']);
            if (!empty($env_data))
                setEnvironmentValue($env_data);
            foreach ($form_data as $field => $value) {
                if (isset($value) && !is_null($value)) {
                    Settings::set($data['section_key'], $field, $value);
                }
            }
            return response()->json(['success' => [
                'title'         => __('optionbuilder::option_builder.success_title'),
                'type'          => 'success',
                'message'       => __('optionbuilder::option_builder.setting_updated'),
            ]]);
        }
    }

    /**
     * reset section settings
     *
     * @return \Illuminate\Http\Response
     */
    public function resetSettings(Request $request) {

        $data = $request->all();
        if (!empty($data) && !empty($data['section_key'])) {

            if (!empty($data['reset_all'])) {
                Settings::resetSection();
            } else {
                Settings::resetSection($data['section_key']);
            }

            return response()->json(['success' => [
                'type'          => 'success',
                'title'         => __('optionbuilder::option_builder.success_title'),
                'message'       => __('optionbuilder::option_builder.reset_settings'),
            ]]);
        }
    }

    /**
     * Update file upload
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function uploadFiles(Request $request) {

        $data = $request->all();
        $extensions = !empty($data['extensions']) ? $data['extensions'] : '.*';

        if ($extensions != '*') {

            $validator = Validator::make($request->all(), [
                'files.*'       => 'mimes:' . $extensions,
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'title'         => __('optionbuilder::option_builder.error_title'),
                    'type'          => 'error',
                    'message'       => $validator->errors()->all(),
                ]);
            }
        }

        if (!empty($data['files'])) {
            $arr = [];
            foreach ($data['files'] as $file) {
                if (is_file($file)) {
                    $ext        = $file->guessExtension();
                    $type       = 'file';
                    $thumbnail  = 'public/vendor/optionbuilder/images/file-preview.png';
                    $orgName    = $file->getClientOriginalName();
                    $size       = filesize($file);
                    $fileName   = rand(1, 9999) . date('m-d-Y_hia') . $file->getClientOriginalName();
                    $filepath   = $file->storeAs('optionbuilder/uploads', $fileName, 'public');

                    if (substr($file->getMimeType(), 0, 5) == 'image') {
                        $type       = 'image';
                        $thumbnail  = 'public/storage/' . $filepath;
                    }

                    array_push($arr, [
                        'type'      => $type,
                        'name'      => $orgName,
                        'path'      => $filepath,
                        'mime'      => $ext,
                        'size'      => $size,
                        'thumbnail' => asset($thumbnail),
                    ]);
                }
            }
        }
        return response()->json([
            'type'          => 'success',
            'files'         => $arr,
        ]);
    }
}
