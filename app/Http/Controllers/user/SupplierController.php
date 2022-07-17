<?php

namespace App\Http\Controllers\user;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\BankAccountRequest;
use App\Http\Requests\DeedRequest;
use App\Http\Requests\ReservationRequest;
use App\Models\BankAccount;
use App\Models\Deed;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SupplierController extends Controller
{
    /** get Not Reserved Suppliers */
    public function getData()
    {
        $data = UserHelper::notReservedSuppliers();

        return $this->success('', $data);
    }

    /** for the supplier to set themselves as available */
    public function setAvailable()
    {
        $supplier = auth()->user();
        UserHelper::setSupplierAvailability($supplier, true);

        return $this->success('', $supplier);
    }

    /** for the supplier to set themselves as unavailable */
    public function setUnavailable()
    {
        $supplier = auth()->user();
        UserHelper::setSupplierAvailability($supplier, false);

        return $this->success('', $supplier);
    }

    /** after the supplier finished their deed */
    public function createDeed(DeedRequest $request)
    {
        $supplier = auth()->user();

        $values = $request->validated();
        $reservation = Reservation::with('supplier')->findOrFail($values['reservation_id']);
        if ($supplier->id != $reservation->supplier->id) {
            return $this->failure('this reservation doesn\'t belong to the current supplier');
        }
        $deed = Deed::create([
            'reservation_id' => $reservation->id,
            'duration' => $values['duration'],
            'confirmed_by_client' => false
        ]);

        return $this->success('', $deed);
    }

    public function getBankAccount()
    {
        $data = BankAccount::where('user_id', auth()->user()->id)->get();
        return $this->success('', $data);
    }

    public function storeBankAccount(BankAccountRequest $request)
    {
        $user = auth()->user();
        $user->bank_account()->delete();
        $values = $request->validated();
        $bankAccount = BankAccount::create([
            'bank_name' => $values['bank_name'],
            'rib' => $values['rib'],
            'bic' => $values['bic'],
        ]);
        $user->bank_account()->save($bankAccount);
        return $this->success('', $bankAccount);
    }
}
