<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectPaymentMethod extends Model
{
    protected $fillable = ['project_id','method_name','method_id','type','project_bank_detail_id','price_per_token','wallet_address','status'];

    public function bank()
    {
        return $this->hasOne('App\ProjectBankDetails','id','project_bank_detail_id');
    }
}
