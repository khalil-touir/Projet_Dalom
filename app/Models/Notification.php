<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = ['value'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('is_seen');
    }

}
