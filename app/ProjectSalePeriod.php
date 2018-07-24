<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectSalePeriod extends Model
{
    protected $fillable = ['project_id','sale_start','sale_end','discount','period_name','status'];
    protected $dates = ['sale_start','sale_end'];
}
