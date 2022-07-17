<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;

class NotificationHelper
{
    public static function notifyAll($model, $excludeIds = [])
    {
        $value = new \stdClass();
        $value->class = get_class($model);
        $value->model_id = $model->id;
        $value = json_encode($value);
        $notification = Notification::create([
            'value' => $value
        ]);
        $notification->users()->sync(User::whereNotIn('id', $excludeIds)->get());
    }

    public static function notifyUser($user, $model)
    {
        $value = new \stdClass();
        $value->class = get_class($model);
        $value->model_id = $model->id;
        $value = json_encode($value);
        $notification = Notification::create([
            'value' => $value
        ]);
        $notification->users()->sync($user);
    }
}
