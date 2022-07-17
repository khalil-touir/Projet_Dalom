<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = ['date', 'amount', 'description'];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_user_id');
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_user_id');
    }

    public function deed()
    {
        return $this->belongsTo(Deed::class, 'deed_id');
    }
}
