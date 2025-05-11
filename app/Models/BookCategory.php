<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class BookCategory extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'description',
    ];

    public function books(){
        return $this->hasMany(Book::class);
    }

}
