<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin()
    {
        return $this->role == 'admin';
    }

    public function isSupplier()
    {
        return $this->role == 'supplier';
    }

    public function supplier_detail()
    {
        return $this->hasOne(SupplierDetail::class, 'user_id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(category::class, 'category_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'client_user_id');
    }

    //as a supplier (another user reserved me)
    public function supplier_reservations()
    {
        return $this->hasMany(Reservation::class, 'supplier_user_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'client_user_id');
    }

    //as a supplier (another user made a transaction with me)
    public function supplier_transactions()
    {
        return $this->hasMany(Transaction::class, 'supplier_user_id');
    }

    public function certification()
    {
        return $this->hasOne(Certification::class, 'user_id');
    }

    public function ratings()
    {
        return $this->hasOne(Rating::class, 'client_user_id');
    }

    //as a supplier (another user rated me)
    public function supplier_ratings()
    {
        return $this->hasMany(Rating::class, 'supplier_user_id');
    }

    public function bank_account()
    {
        return $this->hasOne(BankAccount::class, 'user_id');
    }

    public function payment_cards()
    {
        return $this->hasMany(PaymentCard::class, 'user_id');
    }

    public function reclamations()
    {
        return $this->hasMany(Reclamation::class, 'user_id');
    }

    public function setting()
    {
        return $this->hasOne(Setting::class, 'user_id');
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class)->withPivot('is_seen');
    }
}
