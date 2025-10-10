<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'payment_method',
        'amount',
        'shipping_postal_code',
        'shipping_address',
        'shipping_building',
        'payment_date',
        'stripe_transaction_id',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    // one-to-one relationship (child)
    public function item() {
        return $this->belongsTo(Item::class);
    }

    public function transaction() {
        return $this->hasOne(Transaction::class, 'purchase_id');
    }

    // one-to-many relationship
    public function user() {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}
