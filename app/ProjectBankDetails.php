<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectBankDetails extends Model
{
    protected $fillable = ['project_id','payment_method_id','payment_method_name','account_name','holder_address','account_number','swift_code','bank_name','bank_address'];
}
