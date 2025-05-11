<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Transaction extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'user_id',
        'book_id',
        'delivery_id',
        'quantity',
        'total_price',
        'total_payment',
        'payment_type',
        'payment_status',
    ];

    public function book(){
    return $this->belongsTo(related: Book::class);
    }

    public function user(){
    return $this->belongsTo(User::class);
    }

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function cartItem()
    {
        return $this->hasOne(CartItem::class);
    }
}
