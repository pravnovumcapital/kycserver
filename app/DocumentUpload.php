<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentUpload extends Model
{
    protected $fillable = ['user_id','type','orginal_path','thumbnail_path','file_name','extention','org_size','thumb_size'];
}
