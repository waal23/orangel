<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use HasFactory;
    public $table = "user_notification";

    public function data_user()
    {
        return $this->hasOne(Users::class, "id", 'data_user_id');
    }
}
