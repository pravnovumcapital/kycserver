<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\DocumentUpload;
use Carbon\Carbon;
use Storage;

class User extends Authenticatable
{
    use Notifiable;

    protected $dates = [
        'date_of_birth'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'email', 'password','last_name','date_of_birth','phone_number','country_code','country_of_residence','device_security_enable','device_id','type_of_security','security_token','status','citizenship_id','citizenship','passport_number','passport_photo','selfie_photo','erc20_address'
    ];

    public function getDateOfBirthAttribute($date)
    {
        return Carbon::parse($date)->format('d/m/Y');
    }
    public function getSelfiePhotoAttribute($value)
    {
        $avatar = DocumentUpload::where('type','avatar')->where('user_id',$this->id)->latest()->first();
        if($avatar){
            if(isset($avatar->file_name))
            {
                $file=storage_path('app/public/'.$this->id.'/avatar/'.$avatar->file_name); 
                if (file_exists($file)) {              
                    $file=Storage::url($this->id.'/avatar/'.$avatar->file_name.'?').str_random(10);
                    return url($file);
                } else {
                    //$file='images/profilePhoto.png';
                    return NULL;
                }
            }
        }
        return NULL;
    }
    public function getPassportPhotoAttribute($value)
    {
        $avatar = DocumentUpload::where('type','passport')->where('user_id',$this->id)->latest()->first();
        if($avatar){
            if(isset($avatar->file_name))
            {
                $file=storage_path('app/public/'.$this->id.'/passport/'.$avatar->file_name); 
                if (file_exists($file)) {              
                    $file=Storage::url($this->id.'/passport/'.$avatar->file_name.'?').str_random(10);
                    return url($file);
                } else {
                    //$file='images/profilePhoto.png';
                    return NULL;
                }
            }
        }
        return NULL;
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    
}
