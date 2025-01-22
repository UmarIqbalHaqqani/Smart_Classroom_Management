<?php

namespace App\Http\Controllers\api\v2\Language;

use App\Language;
use App\SmLanguage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LanguageController extends Controller
{
    public function allList(Request $request)
    {
        $languages = SmLanguage::orderBy('id', 'ASC')
            ->get()->map(function ($language) use ($request) {
                $active = $request->user() ? ($request->user()->language == $language->language_universal) : boolval($language->active_status);
                $rtl = false;
                if($active){
                    $lang = Language::find($language->lang_id);
                    if($lang && $lang->rtl){
                        $rtl = true;
                    }
                }
                return [
                    'id' => $language->id,
                    'locale' => $active ? 'active' : $language->language_universal,
                    'default_locale' => $language->language_universal,
                    'lang_name' => $language->native,
                    'rtl' => $rtl,
                    'active_status' => $active,
                ];
            });

        $data = [];
        $activeLanguage = $languages->where('active_status', 1)->first();

        $langName = $activeLanguage ? $activeLanguage['default_locale'] : 'en';
        $en = $active = __('mobile_app', [], 'en');
        if ($langName != 'en') {
            $active = __('mobile_app', [], $langName);

            if (count($en) != count($active)) {
                $active = array_merge($active, $en);
            }
        }

        $data['lang_list'] = $languages;
        $data['lang'] =  $active;

        return response()->json($data);
    }

    public function myLanguages(Request $request)
    {
        $this->validate($request, [
            'lang_id' => 'required'
        ]);

        $languageId = SmLanguage::where('school_id', auth()->user()->school_id)->find($request->lang_id);
        $user = auth()->user();
        if ($languageId) {
            $user->language = $languageId->language_universal;
            $user->save();
        }


        $languages = SmLanguage::where('school_id', auth()->user()->school_id)
            ->get()->map(function ($language) use ($user) {
                $active = $user->language == $language->language_universal;
                $rtl = false;
                if($active){
                    $lang = Language::find($language->lang_id);
                    if($lang && $lang->rtl){
                        $rtl = true;
                    }
                }
                return [
                    'id' => $language->id,
                    'locale' => $active ? 'active': $language->language_universal,
                    'default_locale' => $language->language_universal,
                    'lang_name' => $language->native,
                    'rtl' => $rtl,
                    'active_status' => $active,
                ];
            });

        $data = [];
        $activeLanguage = $languages->where('active_status', 1)->first();

        $langName = $activeLanguage ? $activeLanguage['default_locale'] : 'en';
        $en = $active = __('mobile_app', [], 'en');
        if ($langName != 'en') {
            $active = __('mobile_app', [], $langName);

            if (count($en) != count($active)) {
                $active = array_merge($active, $en);
            }
        }

        $data['lang_list'] = $languages;
        $data['lang'] =  $active;

        return response()->json($data);
    }
}
