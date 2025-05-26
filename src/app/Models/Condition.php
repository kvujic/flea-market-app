<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;

    protected $fillable = [
        'condition',
    ];

    // one-to-many relationship
    public function item() {
        return $this->hasMany(Item::class);
    }

}
