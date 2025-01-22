<?php

namespace App\Http\Controllers\api\v2\Teacher;

use App\SmBook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Scopes\ActiveStatusSchoolScope;
use App\Http\Resources\v2\Teacher\Book\BooklistResource;


class BookController extends Controller
{
    public function bookList()
    {
        $bookSearch = SmBook::withoutGlobalScope(ActiveStatusSchoolScope::class)->with('bookSubject')->where('school_id', auth()->user()->school_id)->get();
        $books = BooklistResource::collection($bookSearch);
        if (!$books) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $books,
                'message' => 'Book list'
            ];
        }
        return response()->json($response);
    }
}
