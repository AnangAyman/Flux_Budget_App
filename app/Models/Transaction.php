<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'amount', 'type', 'description', 'receipt_image_url'
    ];

    // A Transaction belongs to one User
    public function user() {
        return $this->belongsTo(User::class);
    }
}
