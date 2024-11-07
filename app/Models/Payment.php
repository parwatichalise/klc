<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'product_id', 'amount', 'transaction_id', 'status', 'is_active' // Added 'is_active'
    ];
    
}
