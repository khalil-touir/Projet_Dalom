<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierDetail extends Model
{
    use HasFactory;

    protected $table = 'supplier_details';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
