<?php

namespace App;
use Storage;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function logo($type='logo')
    {
        if($type=='thumb')
        $path = "admin/project/".$this->id."/thumb/".$this->thumbnail_logo;
        else
        $path = "admin/project/".$this->id."/logo/".$this->project_logo;
        //dd(url(Storage::url($path)));
        $exists = Storage::disk('public')->exists($path);
        if($exists)
        {
            return url(Storage::url($path));
        }
        else{
            return url('/images/default-logo.png');
        }
    }
    public function getSalesPeriods()
    {
        return $this->hasMany('App\ProjectSalePeriod');
    }
    public function getPaymentModes()
    {
        return $this->hasMany('App\ProjectPaymentMethod');
    }
}
