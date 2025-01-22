<?php

namespace App\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\PDF\Entities\PDF;
use Modules\PDF\Entities\PDFCategory;

class UserPDFController extends Controller
{
    private function paginateCollection(Collection $items, int $perPage = 9, $path = null, $query = [])
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->all();
    
        $paginatedItems = new LengthAwarePaginator($currentItems, $items->count(), $perPage, $currentPage, [
            'path' => $path ?: LengthAwarePaginator::resolveCurrentPath(),
            'query' => $query,
        ]);
    
        return $paginatedItems;
    }

    public function index() 
    {
        $pdf_categories = PDFCategory::select('id','title')->get();
        $user = Auth::user();
        $role_id = $user->role_id;
        $school_id = $user->school_id;
        
        $pdfs = PDF::with('pdfCategory')->orderBy('id', 'desc')->get();
    
        $filtered_pdf_items = $pdfs->filter(function ($item) use ($role_id, $school_id) {
            $available_for  = json_decode($item->available_for, true);
            $school_ids     = json_decode($item->school_id, true);
            return in_array($role_id, $available_for) && in_array($school_id, $school_ids);
        });
    
        $pdf_items = $this->paginateCollection($filtered_pdf_items, 9, route('user-pdf.index'), []);
    
        return view('backEnd.userPdf.index', compact('pdf_categories', 'pdf_items'));
    }

    public function userPdfSearch(Request $request)
    {
        $user = Auth::user();
        $role_id = $user->role_id;
        $school_id = $user->school_id;
        $date = $request->date ? date('Y-m-d', strtotime($request->date)) : null;
        
        $pdf = PDF::with('pdfCategory')->get();
        
        $filtered_pdf_items = $pdf->filter(function ($item) use ($role_id, $school_id, $request, $date) {
            $available_for  = json_decode($item->available_for, true);
            $school_ids     = json_decode($item->school_id, true);
    
            $role_condition     = in_array($role_id, $available_for);
            $school_condition   = in_array($school_id, $school_ids);
            $category_condition = !$request->pdf_category_id || $item->pdf_category_id == $request->pdf_category_id;
            $date_condition     = !$request->date || $item->publish_date == $date;
    
            return $role_condition && $school_condition && $category_condition && $date_condition;
        });
    
        $query = [
            'pdf_category_id' => $request->pdf_category_id,
            'date' => $request->date
        ];
        
        $pdf_items = $this->paginateCollection($filtered_pdf_items, 9, route('user-pdf-search'), $query);
        $pdf_categories = PDFCategory::select('id', 'title')->get();
    
        return view('backEnd.userPdf.index', compact('pdf_items', 'pdf_categories'));
    }

    public function pdfView($id)
    {
        $user = Auth::user();
        $role_id = $user->role_id;
        $school_id = $user->school_id;
    
        $pdf = PDF::with('pdfCategory')->where('id', $id)->first();
    
        if (!$pdf) {
            Toastr::error('No Data Found', 'Failed');
            return redirect()->back();
        }
    
        $available_for  = json_decode($pdf->available_for, true);
        $school_ids     = json_decode($pdf->school_id, true);
    
        if (!in_array($role_id, $available_for) || !in_array($school_id, $school_ids)) {
            Toastr::error('No Data Found', 'Failed');
            return redirect()->back();
        }
    
        $data['pdf']    = $pdf->pdf;
        $data['image']  = $pdf->image;
    
        return view('pdf::pdf.showbook')->with($data);
    }
    
}
