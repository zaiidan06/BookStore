<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Delivery;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function mainPage(){
        $books = Book::with('bookCategory')->get();

        return view('main', compact('books'));
    }
    public function aboutPage(){
        return view('about');
    }
    public function profilePage(){
        $users = User::all();
        $transactions = Transaction::all();
        $delivery = Delivery::all();
        return view('profile', compact('users','transactions','delivery'));
    }
}
