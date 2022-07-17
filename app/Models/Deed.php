<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deed extends Model
{
    use HasFactory;

    protected $table = 'deeds';

    protected $fillable = ['reservation_id', 'duration', 'confirmed_by_client'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'deed_id');
    }
}
