<?php

use Larabuild\Pagebuilder\Facades\PageSettings;
use Illuminate\Support\Facades\Config;


/**
 *return pagination select options
 *
 * @return response()
 */
if (!function_exists('perPageOpt')) {

    function perPageOpt() {

        return [10, 20, 30, 50, 100, 200];
    }
}



if (!function_exists('getDefaultValues')) {
    function getDefaultValues($directory, $key) {
        if (file_exists(resource_path('views/pagebuilder/' . $directory . '/settings.php'))) {
            $currentSettings = include resource_path('views/pagebuilder/' . $directory . '/settings.php');
            if (!empty($currentSettings['fields'])) {
                foreach ($currentSettings['fields'] as $item) {
                    if ($item['id'] == $key)
                        return !empty($item['value']) ?  $item['value'] : '';
                }
            }
        }
        return '';
    }
}

if (!function_exists('pagesetting')) {
    function pagesetting($key) {
        $setting = PageSettings::getPageSectionSettings(getCurrentPageId(), getSectionId(), $key);
        if (empty($setting) && !empty(getCurrentDirectory())) {
            $directory = getCurrentDirectory();
            return getDefaultValues($directory, $key);
        }
        return $setting;
    }
}

if (!function_exists('getCurrentPageId')) {
    function getCurrentPageId() {
        return config('pagebuilder.page_id');
    }
}

if (!function_exists('setCurrentPageId')) {
    function setCurrentPageId($id) {
        Config::set('pagebuilder.page_id', $id);
    }
}

if (!function_exists('getPageIdFromSlug')) {
    function getPageIdFromSlug($slug) {
        $page = PageSettings::getPageBySlug($slug);
        return $page ? $page->id : 0;
    }
}

if (!function_exists('setGridId')) {
    function setGridId($id) {
        Config::set('pagebuilder.current_grid_id', $id);
    }
}

if (!function_exists('getGridId')) {
    function getGridId() {
        return config('pagebuilder.current_grid_id');
    }
}

if (!function_exists('setSectionId')) {
    function setSectionId($id) {
        Config::set('pagebuilder.current_section_id', $id);
    }
}

if (!function_exists('getSectionId')) {
    function getSectionId() {
        return config('pagebuilder.current_section_id');
    }
}

if (!function_exists('setCurrentDirectory')) {
    function setCurrentDirectory($id) {
        Config::set('pagebuilder.current_directory', $id);
    }
}

if (!function_exists('getCurrentDirectory')) {
    function getCurrentDirectory() {
        return config('pagebuilder.current_directory');
    }
}

if (!function_exists('getCss')) {
    function getCss() {
        return getComponentStyles(getGridId());
    }
}

if (!function_exists('getClasses')) {
    function getClasses($gridId = null) {
        $pageSettings = PageSettings::getPageSettings(getCurrentPageId());
        $gridId = ($gridId ?? getGridId());
        return $pageSettings['section_data'][$gridId]['styles']['classes'] ?? '';
    }
}

if (!function_exists('getCustomAttributes')) {
    function getCustomAttributes($gridId = null) {

        $pageSettings = PageSettings::getPageSettings(getCurrentPageId());
        $gridId = ($gridId ?? getGridId());
        return $pageSettings['section_data'][$gridId]['styles']['custom_attributes'] ?? '';
    }
}

if (!function_exists('getStyleElements')) {
    function getStyleAttributes() {
        return [
            "width" => "width-height-type",
            "height" => "width-height-type",
            "min-width" => "width-height-type",
            "min-height" => "width-height-type",
            "max-width" => "width-height-type",
            "max-height" => "width-height-type",
            "margin-top" => "margin-type",
            "margin-right" => "margin-type",
            "margin-bottom" => "margin-type",
            "margin-left" => "margin-type",
            "padding-top" => "padding-type",
            "padding-right" => "padding-type",
            "padding-bottom" => "padding-type",
            "padding-left" => "padding-type",
            "z-index" => "",
            "background-size" => "",
            "background-position" => "",
            "background-color" => "",
            "image" => "",
        ];
    }
}

if (!function_exists('getComponentStyles')) {
    function getComponentStyles($gridId = null) {
        $css = '';
        if (!empty($gridId)) {
            $pageId = getCurrentPageId();
            $page = PageSettings::getPage($pageId);
            $styleSettings = $page->settings['section_data'][$gridId]['styles'] ?? [];
            if ($styleSettings) {
                foreach (getStyleAttributes() as $attribute => $valueType) {
                    if (isset($styleSettings[$attribute]) && $styleSettings[$attribute] != "") {
                        if ($attribute == 'image') {
                            $bg = json_decode($styleSettings[$attribute][0], true);
                            $css .= 'background-image:url(\'' . $bg['thumbnail'] . '\');';
                        } else {
                            $css .= $attribute . ':' . $styleSettings[$attribute] . ($styleSettings[$valueType] ?? '') . ';';
                        }
                    }
                }
            }
        }
        return $css;
    }
}

if (!function_exists('getBgOverlay')) {
    function getBgOverlay($gridId = null) {
        $pageSettings = PageSettings::getPageSettings(getCurrentPageId());
        $gridId = ($gridId ?? getGridId());
        if (!empty($pageSettings['section_data'][$gridId]['styles']['background-overlay-color']))
            return
                ' <div class="pb-bg-overlay" style="background-color: ' . $pageSettings['section_data'][$gridId]['styles']['background-overlay-color'] . ';
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;">
                </div>';
        return '';
    }
}

if (!function_exists('getContainerStyles')) {
    function getContainerStyles() {
        $gridId = getGridId();
        if (!empty($gridId)) {
            $pageId = getCurrentPageId();
            $page = PageSettings::getPage($pageId);
            $styleSettings = $page->settings['section_data'][$gridId]['styles'] ?? [];
            if (!empty($styleSettings['content_width']) && $styleSettings['content_width'] == 'full_width') {
                return 'class="container-fluid"';
            }

            if (!empty($styleSettings['content_width']) && $styleSettings['content_width'] == 'boxed') {
                $containerStyles =  'class="container"';
                if (!empty($styleSettings['boxed_slider_input']))
                    $containerStyles .= ' style="max-width: ' . $styleSettings['boxed_slider_input'] . 'px"';
                return $containerStyles;
            }
        }
        return 'class="container"';
    }
}

if (!function_exists('getGrides')) {
    function getGrids() {
        return  [
            '12x1',
            '6x2',
            '4x3',
            '3x4',
            '5',
            '6',
            '3x9',
            '9x3',
            '3x3x6',
            '6x3x3',
            '3x6x3',
            '2x8x2',
        ];
    }
}

if (!function_exists('getColumnInfo')) {

    function getColumnInfo($grid) {

        switch ((string) $grid) {
            case '2x8x2':
                return ['col-2', 'col-8', 'col-2'];
                break;

            case '3x3x6':
                return ['col-3', 'col-3', 'col-6'];
                break;

            case '3x4':
                return ['col-3', 'col-3', 'col-3', 'col-3'];
                break;

            case '3x6x3':
                return ['col-3', 'col-6', 'col-3'];
                break;

            case '3x9':
                return ['col-3', 'col-9'];
                break;

            case '4x3':
                return ['col-lg-4', 'col-lg-4', 'col-lg-4'];
                break;

            case '5':
                return ['col-2-4', 'col-2-4', 'col-2-4', 'col-2-4', 'col-2-4',];
                break;

            case '6':
                return ['col-2', 'col-2', 'col-2', 'col-2', 'col-2', 'col-2',];
                break;

            case '6x2':
                return ['col-md-6', 'col-md-6'];
                break;

            case '6x3x3':
                return ['col-md-6 col-12', 'col-md-3 col-6', 'col-md-3 col-6'];
                break;

            case '9x3':
                return ['col-lg-9', 'col-lg-3'];
                break;

            case '12x1':
                return ['col-12'];
                break;

            default:
                return ['col'];
                break;
        }
    }
}

/**
 * Helper function to sanitize a string value from user input
 *
 * @param string    $string          String to sanitize.
 * @param bool      $keep_linebreak  Not compulsory Whether to keep newlines or not. Default: false.
 * @return string   Sanitized string.
 */

if (!function_exists('sanitizeTextField')) {
    function sanitizeTextField($string, $keep_linebreak = false) {
        if (is_object($string) || is_array($string)) {
            return '';
        }
        $filtered = stripAllTags($string, $keep_linebreak);
        return clean($filtered, ['Attr.EnableID' => true]);
    }
}

/**
 * @param string $string        String containing HTML tags
 * @param bool   $remove_breaks Optional. Whether to remove left over line breaks and white space chars
 * @return string The processed string.
 */
if (!function_exists('stripAllTags')) {

    function stripAllTags($string, $remove_breaks_tag = false) {

        $string = preg_replace('@<(script|style)[^>]*?>.*?</\\1>@si', '', $string);
        $string = strip_tags($string, '<h1><h2><h3><h4><h5><h6><div><b><strong><i><em><a><ul><ol><li><p><br><span><figure><sup><sub><table><tr><th><td><tbody><iframe><form><capture><label><fieldset><section>');

        if ($remove_breaks_tag) {
            $string = preg_replace('/[\r\n\t ]+/', ' ', $string);
        }
        return trim($string);
    }
}

/**
 * @return array The processed array.
 */
if (!function_exists('sanitizeArray')) {

    function sanitizeArray(&$arr) {

        foreach ($arr as $key => &$el) {

            if (is_array($el)) {
                sanitizeArray($el);
            } else {
                $el = sanitizeTextField($el, true);
            }
        }
        return  $arr;
    }
}

if (!function_exists('createPageSlug')) {
    function createPageSlug($string) {
        $string =  ltrim(strtolower(trim(preg_replace('/[^A-Za-z0-9-\/]+/', '-', $string))), '/');
        if ($string == '')
            return '/';
        return $string;
    }
}

if (!function_exists('getComponentSettings')) {
    function getComponentSettings($directory) {
        if (file_exists(resource_path('views/themes/'.activeTheme().'/pagebuilder/'. $directory . '/settings.php'))) {
            $settings = include resource_path('views/themes/'.activeTheme().'/pagebuilder/'. $directory . '/settings.php');
            return $settings;
        }
        return [];
    }
}

if (!function_exists('isDemoSite')) {

    function isDemoSite() {
        return false;
        if (isset($_SERVER["SERVER_NAME"]) && in_array($_SERVER["SERVER_NAME"], array('larab.wp-guppy.com'))) {
            return true;
        } else {
            return false;
        }
    }
}
