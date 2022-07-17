<?php

namespace App\Helpers;

use App\Models\User;

class UserHelper
{
    public static function notReservedSuppliers()
    {
        return User::where('role', config('global.roles.supplier', 'supplier'))
            ->where('is_available', true)
            ->get();
    }

    public static function setSupplierAvailability(&$supplier, $availability)
    {
        $supplier->is_available = $availability;
        $supplier->save();
    }
}
