<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReclamationRequest;
use App\Models\Reclamation;
use Illuminate\Http\Request;

class ReclamationController extends Controller
{
    public function getData()
    {
        $user = auth()->user();
        if ($user->isAdmin()) {
            $data = Reclamation::all();
        } else {
            $data = $user->reclamations;
        }
        return $this->success('', $data);
    }

    public function create(ReclamationRequest $request)
    {
        $values = $request->validated();
        $reclamation = Reclamation::create([
            'header' => $values['header'],
            'content' => $values['content']
        ]);
        $reclamation->user()->associate(auth()->user());
        $reclamation->save();

        return $this->success('', $reclamation);
    }
}
