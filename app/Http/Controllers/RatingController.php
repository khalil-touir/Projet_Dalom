<?php

namespace App\Http\Controllers;

use App\Enums\HTTPHeader;
use App\Http\Requests\RatingRequest;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function getSupplierRating()
    {
        $this->validateId();
        $supplier = User::findOrFail($this->model_id);
        if (!$supplier->isSupplier()) {
            return $this->failure('Supplier not found', HTTPHeader::NOT_FOUND);
        }

        $supplierRatings = Rating::where('supplier_user_id', $supplier->id)->get();
        $amount = 0;
        foreach ($supplierRatings as $supplierRating) {
            $amount += $supplierRating->amount;
        }
        $amount = round($amount / count($supplierRatings), 2);

        $data = [
            'amount' => $amount
        ];
        return $this->success('', $data);
    }

    public function rateSupplier(RatingRequest $request)
    {
        $this->validateId();
        $supplier = User::findOrFail($this->model_id);
        if (!$supplier->isSupplier()) {
            return $this->failure('Supplier not found', HTTPHeader::NOT_FOUND);
        }

        $userRating = Rating::where('client_user_id', auth()->user()->id)
            ->where('supplier_user_id', $supplier->id)
            ->first();
        if ($userRating) {
            $userRating->delete();
        }

        $values = $request->validated();
        if ($request->hasFile('picture')) {
            $picture = $request->file('picture');
            $extension = $picture->getClientOriginalExtension();
            if (!in_array($extension, config('global.allowed_file_extensions'))) {
                return $this->failure('invalid file format');
            }

            $path = $picture->store('public/ratings');
        }

        $rating = Rating::create([
            'amount' => $values['amount'],
            'comment' => $values['comment'] ?? null,
            'picture' => $path ?? null
        ]);

        /** @todo image upload (picture attribute) */

        $rating->client()->associate(auth()->user());
        $rating->supplier()->associate($supplier);
        $rating->save();

        return $this->success('', $rating);
    }

    /*public function getUserRatingForSupplier()
    {
        $this->validateId();
        $supplier = User::findOrFail($this->model_id);
        if (!$supplier->isSupplier()) {
            return $this->failure('Supplier not found', HTTPHeader::NOT_FOUND);
        }

        $userRating = Rating::where('client_user_id', auth()->user()->id)
            ->where('supplier_user_id', $supplier->id)
            ->first();
        if (!$userRating) {
            return $this->failure('No rating found for the given user and supplier', HTTPHeader::NOT_FOUND);
        }

        return $this->success('', $userRating);

    }*/
}
