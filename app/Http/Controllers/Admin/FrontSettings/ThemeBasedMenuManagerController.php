<?php

namespace App\Http\Controllers\Admin\FrontSettings;

use App\SmPage;
use App\SmHeaderMenuManager;
use App\Http\Controllers\Controller;
use Larabuild\Pagebuilder\Models\Page;

class ThemeBasedMenuManagerController extends Controller
{
    public $themeName;

    public function __construct($themeName = 'edulia')
    {
        $this->themeName = activeTheme();
    }

    public function index()
    {
        $data = $this->indexData();
        return view('backEnd.frontSettings.headerMenuManager', $data);
    }

    public function store($request)
    {
        if($request->type == "sPages"){
            foreach ($request->element_id as $data) {
                $spage = Page::findOrFail($data);
                SmHeaderMenuManager::create([
                    'title' => $spage->title,
                    'type' => $request->type,
                    'element_id' => $data,
                    'link' => '/'.$spage->slug,
                    'position' => 387437,
                    'theme' => $this->themeName,
                    'school_id' => app('school')->id,
                ]);
            }
        } elseif ($request->type == "customLink") {
            SmHeaderMenuManager::create([
                'title' => $request->title,
                'link' => $request->link,
                'type' => $request->type,
                'position' => 387437,
                'theme' => $this->themeName,
                'school_id' => app('school')->id,
            ]);
        }
    }

    public function update($request)
    {
        if($request->type == "sPages"){
            $headerMenu = SmHeaderMenuManager::where('id', $request->id)->first();
            $builderPage = Page::find($headerMenu->element_id, ['slug']);
            $headerMenu->update([
                'title' => $request->title,
                'link' => '/'.$builderPage->slug,
                'type' => $request->type,
                'theme' => $this->themeName,
                'school_id' => app('school')->id,
            ]);
        } elseif ($request->type == "customLink") {
            SmHeaderMenuManager::where('id', $request->id)->update([
                'title' => $request->title,
                'link' => $request->link,
                'type' => $request->type,
                'show' => $request->content_show,
                'is_newtab' => $request->is_newtab,
                'school_id' => app('school')->id,
            ]);
        }
    }


    public function renderData()
    {
        $data = $this->indexData();
        return view('backEnd.frontSettings.headerSubmenuList', $data);
    }

    private function indexData(){
        $data['pages'] = SmPage::where('is_dynamic', 1)->where('school_id', app('school')->id)->get();
        $data['static_pages'] = Page::whereNotIn('name', ['header_menu', 'footer_menu'])->where('school_id', app('school')->id)->get();
        $data['menus'] = SmHeaderMenuManager::with('childs')
                    ->where('school_id', app('school')->id)
                    ->where('theme', activeTheme())
                    ->where('parent_id', null)
                    ->orderBy('position')
                    ->get();
        return $data;
    }
}
