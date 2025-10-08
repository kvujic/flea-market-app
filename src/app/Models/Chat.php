<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'sender_id',
        'message',
        'image',
        'is_read',
    ];

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
