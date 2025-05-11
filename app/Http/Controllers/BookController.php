<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCategory;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $booksQuery = Book::with('bookCategory')
            ->when($search, function($query) use ($search) {
                return $query->where('book_name', 'like', '%'.$search.'%');
            });

        $groupedBooks = $booksQuery->get()->groupBy(function ($book) {
            return $book->bookCategory ? $book->bookCategory->name : 'Lainnya';
        });

        return view('books', compact('groupedBooks', 'search'));
    }

}
