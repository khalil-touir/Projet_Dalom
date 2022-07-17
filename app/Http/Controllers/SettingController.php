<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReclamationRequest;
use App\Http\Requests\SettingRequest;
use App\Models\Reclamation;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function getData()
    {
        $setting = auth()->user()->setting;
        $data = json_decode($setting->value);
        return $this->success('', $data);
    }

    public function create(SettingRequest $request)
    {
        $values = $request->validated();
        $valueToStore = new \stdClass();
        foreach ($values as $key => $value) {
            $valueToStore->{$key} = $value;
        }

        $setting = Setting::create([
            'value' => json_encode($valueToStore)
        ]);
        $setting->user()->associate(auth()->user());
        $setting->save();

        return $this->success('', $setting);
    }
}
