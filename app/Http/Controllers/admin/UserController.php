<?php

namespace App\Http\Controllers\admin;

use App\Enums\HTTPHeader;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /*public function showUserCertification()
    {
        $this->validateId();
        $user = User::findOrFail($this->model_id);
        $certification = $user->certification;
        if (!$certification) {
            return $this->failure('no certification found', HTTPHeader::NOT_FOUND);
        }
        return $this->success(env('APP_URL' . ':8000/' . 'storage/certifications/' . $certification->path)); //wrong, just keep the last part of the path
    }*/

    /** to set a user as a supplier (after checking their certification) */
    public function setUserAsSupplier()
    {
        $this->validateId();
        $user = User::findOrFail($this->model_id);
        if ($user->role == config('global.roles.admin', 'admin')) {
            $this->abort('the user is an admin', HTTPHeader::FORBIDDEN);
        }
        $user->role = config('global.roles.supplier', 'supplier');
        $user->save();
        return $this->success();
    }
}
