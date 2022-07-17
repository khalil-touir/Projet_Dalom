<?php

namespace App\Http\Controllers\user;

use App\Enums\HTTPHeader;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Models\Address;

class AddressController extends Controller
{
    public function getData(): \Illuminate\Http\JsonResponse
    {
        $data = auth()->user()->addresses;

        return $this->success('', $data);
    }

    public function create(AddressRequest $request): \Illuminate\Http\JsonResponse
    {
        $address = new Address();
        $address->fill($request->validated());
        $address->save();
        auth()->user()->addresses()->save($address);

        return $this->success('', $address);
    }

    public function update(AddressRequest $request)
    {
        $this->validateId();
        $address = auth()->user()->addresses()->findOrFail($this->model_id);
        $address->fill($request->validated());
        $address->save();
        return $this->success('', $address);
    }

    public function delete()
    {
        $address = auth()->user()->addresses()->findOrFail($this->model_id);
        $address->delete();
        return $this->success();
    }

}
