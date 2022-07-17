<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ApiResponse;

    protected $model_id = null;

    public function __construct(Request $request)
    {
        if ($request->route('id')) {
            $this->model_id = $request->route('id');
        }
    }

    protected function validateId()
    {
        if (!$this->model_id) {
            $this->abort('model id is not set');
        }
    }
}
