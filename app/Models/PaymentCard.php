<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCard extends Model
{
    use HasFactory;

    protected $table = 'payment_cards';

    protected $fillable = ['type', 'holder', 'number', 'valid_to_month', 'valid_to_year', 'cvv'];

    protected $casts = [
        'number' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
