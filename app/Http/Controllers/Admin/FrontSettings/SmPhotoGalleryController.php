<?php

namespace App\Http\Controllers\Admin\FrontSettings;

use Illuminate\Http\Request;
use App\Models\SmPhotoGallery;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;

class SmPhotoGalleryController extends Controller
{

    public function index()
    {
        try {
            $photoGalleries = SmPhotoGallery::where('parent_id', '=', null)->where('school_id', app('school')->id)->orderBy('position', 'asc')->get();
            return view('backEnd.frontSettings.photo_gallery.photo_gallery', compact('photoGalleries'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        $maxFileSize = generalSetting()->file_size * 1024;
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => "required",
            'description' => "required",
            'feature_image' => "required|mimes:jpg,jpeg,png|max:" . $maxFileSize,
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $destination =  'public/uploads/theme/edulia/photo_gallery/';
            $feature_image = fileUpload($request->feature_image, $destination);
            $mainGallery = new SmPhotoGallery();
            $mainGallery->name = $request->name;
            $mainGallery->description = $request->description;
            $mainGallery->feature_image = $feature_image;
            $mainGallery->school_id = app('school')->id;
            $result = $mainGallery->save();
            if ($result && $request->gallery_image) {
                foreach ($request->gallery_image as $gImage) {
                    $galleryImage = fileUpload(gv($gImage, 'image'), $destination);
                    $photoGallery = new SmPhotoGallery();
                    $photoGallery->parent_id = $mainGallery->id;
                    $photoGallery->gallery_image = $galleryImage;
                    $photoGallery->school_id = app('school')->id;
                    $photoGallery->save();
                }
            }
            Toastr::success('Operation successful', 'Success');
            return redirect()->route('photo-gallery');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function edit($id)
    {
        try {
            $photoGalleries = SmPhotoGallery::where('parent_id', '=', null)->where('school_id', app('school')->id)->orderBy('position', 'desc')->get();
            $add_photo_galleries = SmPhotoGallery::where('parent_id', '!=', null)->where('parent_id', $id)->where('school_id', app('school')->id)->get();
            $add_photo_gallery = SmPhotoGallery::find($id);
            return view('backEnd.frontSettings.photo_gallery.photo_gallery', compact('photoGalleries', 'add_photo_gallery', 'add_photo_galleries'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function update(Request $request)
    {
        $maxFileSize = generalSetting()->file_size * 1024;
        $input = $request->all();
        if ($input['id']) {
            $validator = Validator::make($input, [
                'feature_image' => "sometimes|nullable|mimes:jpg,jpeg,png|max:" . $maxFileSize,
            ]);
        } else {
            $validator = Validator::make($input, [
                'name' => "required",
                'description' => "required",
                'feature_image' => "required|mimes:jpg,jpeg,png|max:" . $maxFileSize,
            ]);
        }
        if ($validator->fails()) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $destination =  'public/uploads/theme/edulia/photo_gallery/';
            $mainGallery = SmPhotoGallery::find($request->id);
            $mainGallery->name = $request->name;
            $mainGallery->description = $request->description;
            $mainGallery->feature_image = fileUpdate($mainGallery->feature_image, $request->feature_image, $destination);
            $mainGallery->school_id = app('school')->id;
            $result = $mainGallery->save();
            $deleteNonRequestGalleryImage = SmPhotoGallery::where('parent_id', $mainGallery->id)
                ->when($request->gallery_image, function ($q) use ($request) {
                    $q->whereNotIn('id', array_keys($request->gallery_image));
                })
                ->delete();
            if ($result && $request->gallery_image) {
                foreach ($request->gallery_image as $key => $gImage) {
                    $photoGalleryId = SmPhotoGallery::where('parent_id', $mainGallery->id)->where('id', $key)->first();
                    if ($photoGalleryId != null) {
                        $photoGallery = SmPhotoGallery::find($key);
                        $updatePhotoGalleryImage = !is_string($gImage['image']) ? fileUpdate($photoGallery->gallery_image, $gImage['image'], $destination) : $photoGallery->gallery_image;
                        $photoGallery->parent_id = $mainGallery->id;
                        $photoGallery->gallery_image = $updatePhotoGalleryImage;
                        $photoGallery->school_id = app('school')->id;
                        $photoGallery->save();
                    }
                    if ($photoGalleryId == null) {
                        $photoGallery = new SmPhotoGallery();
                        $photoGallery->parent_id = $mainGallery->id;
                        $photoGallery->gallery_image = fileUpload($gImage['image'], $destination);
                        $photoGallery->school_id = app('school')->id;
                        $photoGallery->save();
                    }
                }
            }
            Toastr::success('Operation successful', 'Success');
            return redirect()->route('photo-gallery');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function deleteModal($id)
    {
        try {
            $photoGallery = SmPhotoGallery::find($id);
            return view('backEnd.frontSettings.photo_gallery.photo_gallery_delete_modal', compact('photoGallery'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function delete($id)
    {
        try {
            $photoGallery = SmPhotoGallery::find($id);
            $galleryImages = SmPhotoGallery::where('parent_id', $photoGallery->id)->get();
            foreach($galleryImages as $img){
                if($img && file_exists($img->gallery_image)){
                    unlink($img->gallery_image);
                }
                $img->delete();
            }
            if($photoGallery && file_exists($photoGallery->feature_image)){
                unlink($photoGallery->feature_image);
            }
            $photoGallery->delete();
            Toastr::success('Deleted successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function viewModal($id)
    {
        try {
            $photoGalleries = SmPhotoGallery::where('parent_id', '!=', null)->where('parent_id', $id)->where('school_id', app('school')->id)->get();
            return view('backEnd.frontSettings.photo_gallery.photo_gallery_view_modal', compact('photoGalleries'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function photoGalleryImageDelete($id)
    {
        try {
            $galleryImage = SmPhotoGallery::find($id);
            $photoGalleries = SmPhotoGallery::where('parent_id', '!=', null)->where('parent_id', $galleryImage->parent_id)->where('school_id', app('school')->id)->orderBy('position', 'desc')->get();
            $html = view('backEnd.frontSettings.photo_gallery.photo_gallery_view_modal', compact('photoGalleries'))->render();
            if($galleryImage && file_exists($galleryImage->gallery_image)){
                unlink($galleryImage->gallery_image);
            }
            $galleryImage->delete();
            return response()->json(['message' => 'Successful', 'html' => $html]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
