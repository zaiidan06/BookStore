<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Delivery extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'phone_number',
        'shipping_address',
        'delivery_courier',
        'shipping_option',
        'shipping_cost',
        'receipt_code',
        'status_delivery',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($delivery) {
            if (!$delivery->receipt_code) {
                $delivery->receipt_code = fake('id_ID')->uuid;
            }
        });
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
