<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Book extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'book_categories_id',
        'book_name',
        'book_image',
        'book_description',
        'book_stock',
        'book_price',
    ];

    public function bookCategory(){
        return $this->belongsTo(BookCategory::class,'book_categories_id');
    }
}
