<?php

namespace App\Http\Controllers\api\v2\Admin;

use App\SmBook;
use App\SmStudent;
use App\LibrarySubject;

use App\SmAcademicYear;
use App\SmBookCategory;
use Illuminate\Http\Request;
use App\Scopes\AcademicSchoolScope;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Scopes\StatusAcademicSchoolScope;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\v2\Admin\BookListResource;
use App\Http\Requests\Admin\Library\SmBookRequest;

class BookController extends Controller
{
    public function bookList()
    {
        $books = SmBook::where('school_id', auth()->user()->school_id)->get();
        $bookList = BookListResource::collection($books);

        if (!$bookList) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $bookList,
                'message' => 'The book list successful'
            ];
        }
        return response()->json($response);
    }

    public function addBookDropdowns()
    {
        $categories = SmBookCategory::withoutGlobalScope(AcademicSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->select('id', 'category_name')->get();
        $subjects = LibrarySubject::withoutGlobalScope(StatusAcademicSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->select('id', 'subject_name')->get();

        $dropdownItems = [
            'categories'    => $categories,
            'subjects'      => $subjects
        ];

        if (!$dropdownItems) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $dropdownItems,
                'message' => 'Category & subject list'
            ];
        }
        return response()->json($response);
    }

    public function storeBook(Request $request)
    {
        $this->validate($request, [
            'book_title' => "required|max:200",
            'book_category_id' => "required",
            'subject_id' => "required",
            'quantity' => "sometimes|nullable|integer|min:0",
            'book_number' => "sometimes|nullable",
            'isbn_no' => "sometimes|nullable|different:book_number",
            'publisher_name' => "sometimes|nullable",
            'author_name' => "sometimes|nullable",
            'details' => "sometimes|nullable",
            'book_price' => "sometimes|nullable|integer|min:0",
            'rack_number' => "sometimes|nullable",
            'post_date' => 'required|date_format:Y-m-d'
        ]);

        $books = new SmBook();
        $books->book_title = $request->book_title;
        $books->book_category_id = $request->book_category_id;
        $books->book_number = $request->book_number;
        $books->isbn_no = $request->isbn_no;
        $books->publisher_name = $request->publisher_name;
        $books->author_name = $request->author_name;
        $books->book_subject_id = $request->subject_id;
        $books->rack_number = $request->rack_number;
        $books->quantity = $request->quantity;
        $books->book_price = $request->book_price;
        $books->details = $request->details;
        $books->post_date = date('Y-m-d', strtotime($request->post_date));
        $books->created_by = Auth::user()->id;
        $books->school_id = Auth::user()->school_id;
        $books->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();

        $books->save();

        // $data = SmBook::select('id','book_title','book_category_id','book_number','isbn_no','publisher_name','author_name','book_subject_id','rack_number','quantity','book_price','details','post_date')->find($books->id);

        if (!$books) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => null,
                'message' => 'The book created successfully'
            ];
        }
        return response()->json($response);
    }
}
