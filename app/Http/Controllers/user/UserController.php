<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankAccountRequest;
use App\Http\Requests\PaymentCardRequest;
use App\Http\Requests\PayRequest;
use App\Http\Requests\ReservationRequest;
use App\Models\BankAccount;
use App\Models\Certification;
use App\Models\Deed;
use App\Models\Notification;
use App\Models\PaymentCard;
use App\Models\Reservation;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    /** to ask for supplier role (upload certification) */
    public function demandSupplier(Request $request)
    {
        if (!$request->hasFile('certification')) {
            return $this->failure('certification needed');
        }
        $certification = $request->file('certification');
        $extension = $certification->getClientOriginalExtension();
        if (!in_array($extension, config('global.allowed_file_extensions'))) {
            return $this->failure('invalid file format');
        }

        $path = $certification->store('public/certifications');
        $name = $certification->getClientOriginalName();

        $certification = new Certification();
        $certification->path = $path;
        $certification->name = $name;
        $certification->save();

        $user = auth()->user();
        $user->certification()->delete();
        $user->certification()->save($certification);
        return $this->success('');
    }

    /** to reserve a supplier */
    public function reserve(ReservationRequest $request)
    {
        $values = $request->validated();

        $this->validateId();
        $supplier = User::where('role', config('global.roles.supplier', 'supplier'))
            ->findOrFail($this->model_id);

        $alreadyReserved = Reservation::where('supplier_user_id', $supplier->id)
            ->where('date', Carbon::parse($values['date']))
            ->first();

        if ($alreadyReserved) {
            return $this->failure('supplier already reserved for the given date & time');
        }

        auth()->user()->addresses()->findOrFail($values['address_id']);

        $reservation = new Reservation();
        $reservation->client_user_id = auth()->user()->id;
        $reservation->supplier_user_id = $this->model_id;
        $reservation->address_id = $values['address_id'];
        $reservation->date = Carbon::parse($values['date']);
        if (isset($values['promo_code'])) {
            $reservation->promo_code = $values['promo_code'];
            /** @todo handle promo code */
        }
        $reservation->save();

        return $this->success('', $reservation);
    }

    public function confirmDeed()
    {
        $this->validateId();
        $deed = Deed::with('reservation')->findOrFail($this->model_id);
        if ($deed->reservation->client->id != auth()->user()->id) {
            return $this->failure('this deed doesn\'t belong to the current user');
        }
        $deed->confirmed_by_client = true;
        $deed->save();

        return $this->success('', $deed);
    }

    public function getRemainingDeeds()
    {
        $user = auth()->user();
        $userTransactionsDeedsIds = $user->transactions->pluck('deed_id');
        $userRemainingDeeds =
            Deed::with(['reservation' => function ($query) use ($user) {
                return $query->where('client_user_id', $user->id);
            }])
                ->where('confirmed_by_client', true)
                ->whereNotIn('id', $userTransactionsDeedsIds)
                ->get();

        $res = new Collection();
        foreach ($userRemainingDeeds as $userRemainingDeed) {
            $reservation = $userRemainingDeed->reservation;
            $supplier = $reservation->supplier;
            $category = $supplier->category;
            $price = $category->price;
            $total = $price * $userRemainingDeed->duration;
            $res->push([
                'id' => $userRemainingDeed->id,
                'duration' => $userRemainingDeed->duration,
                'price' => $price,
                'total' => $total,
                'reservation' => $reservation,
                'supplier' => $supplier
            ]);
        }

        return $this->success('', $userRemainingDeeds);
    }

    public function payDeed(PayRequest $request)
    {
        $values = $request->validated();

        $paymentCard = PaymentCard::findOrFail($values['payment_card_id']);
        if ($paymentCard->user->id != auth()->user()->id) {
            return $this->failure('Payment card id provided doesn\'t belong to the user');
        }

        $deed = Deed::with('reservation')->findOrFail($values['deed_id']);
        if ($deed->transaction) {
            return $this->failure('deed already payed');
        }

        $reservation = $deed->reservation;
        $supplier = $reservation->supplier;
        $category = $supplier->category;
        $price = $category->price;
        $total = $price * $deed->duration;

        /** @todo implement payment api */

        $transaction = Transaction::create([
            'date' => now(),
            'amount' => $total,
            'description' => $values['description'] ?? null
        ]);
        $transaction->client()->associate(auth()->user());
        $transaction->supplier()->associate($reservation->supplier);
        $transaction->deed()->associate($deed);
        $transaction->save();

        return $this->success('', $transaction);
    }

    public function getPaymentCards()
    {
        $data = PaymentCard::where('user_id', auth()->user()->id)->get();
        return $this->success('', $data);
    }

    public function storePaymentCard(PaymentCardRequest $request)
    {
        $values = $request->validated();
        $paymentCard = PaymentCard::create([
            'type' => $values['type'],
            'holder' => $values['holder'],
            'number' => $values['number'],
            'valid_to_month' => $values['valid_to_month'],
            'valid_to_year' => $values['valid_to_year'],
            'cvv' => $values['cvv']
        ]);
        auth()->user()->payment_cards()->save($paymentCard);

        return $this->success('', $paymentCard);
    }

    public function getNotifications()
    {
        $notifications = auth()->user()->notifications()
            ->wherePivot('is_seen', false)
            ->get();

        auth()->user()->notifications()
            ->updateExistingPivot($notifications->pluck('id'), ['is_seen' => true]);

        return $this->success('', $notifications);
    }
}
