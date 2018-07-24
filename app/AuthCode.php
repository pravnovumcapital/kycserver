<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthCode extends Model
{
    protected $fillable = ['user_id','auth_code','device_id','platform'];
}
