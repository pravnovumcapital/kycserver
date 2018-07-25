<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Nationality;
use App\DocumentUpload;

use Storage;
use Carbon\Carbon;
use Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Intervention\Image\ImageManager;

class UserController extends Controller
{
    public function userlist($total_number=10)
    {
    	$users = User::paginate($total_number);
        return view('admin.user.index', ['users' => $users]);
    	//dd(User::get()->toArray());

    }
    public function userVerifiedList($total_number=10)
    {
    	$users = User::where('status','completed')->paginate($total_number);
        return view('admin.user.index', ['users' => $users]);
    	//dd(User::get()->toArray());

    }
    public function userPendingList($total_number=10)
    {
    	$users = User::where('status','pending')->paginate($total_number);
        return view('admin.user.index', ['users' => $users]);
    	//dd(User::get()->toArray());

    }
    public function deleteUser($userId)
    {
    	User::find($userId)->delete();
    	return response()->json(['url' => url()->previous(),"code"=>200], 200);
    	
    }
    public function edit($userId)
    {
       $nationalities = Nationality::orderBy('nationality','ASC')->pluck('nationality','nation_id')->toArray();
       $user = User::find($userId);
       if($user)
       {
           return view('admin.user.edit',compact('user','nationalities'));
       }
    }
    public function update(Request $request,$userId)
    {
        $data = $request->all();
        //dd($data);
        $data['date_of_birth'] = Carbon::createFromFormat('d/m/Y',$request->date_of_birth);
        $user= User::find($userId)->update($data);
        if ($request->hasFile('passport_photo')) { 
            $orgFile=$request->passport_photo; 
            $extension = $orgFile->getClientOriginalExtension();
            $validator = Validator::make($request->all(), [
            'passport_photo' => "mimes:jpeg,bmp,png,gif,svg,pdf|max:4000",
            ]);
            // if ($validator->fails()) {
            //      return response()->json($validator->messages(), 200);
            // }
            // $extension = $org_extension;
            // if($org_extension == 'pdf')
            // {
            //     $extension = $org_extension;
            // }
            $this->uploadDocument($orgFile,$userId,'passport',$extension);
            }

            if ($request->hasFile('selfie_photo')) { 
        
                $validator = Validator::make($request->all(), [
                    'selfie_photo' => "mimes:jpeg,bmp,png,gif,svg|max:10000",
                ]);
                if ($validator->fails()) {
                    return response()->json($validator->messages(), 200);
                }
                $extension = $request->selfie_photo->getClientOriginalExtension();
                $this->uploadDocument($request->selfie_photo,$userId,'avatar',$extension);
            }
            return redirect()->back()->with('success','Added Successfully!');
            // return redirect()->back()->with('error','Please add payment method!');
    }
    public function uploadDocument($orgFile,$userId,$type,$extension)
    {
         $destinationPath = $userId.'/'.$type;
         $originalFileName = Storage::disk('public')->put($destinationPath.'/original',$orgFile); 
         if($extension == 'pdf'){
            $originalFileName = Storage::disk('public')->put($destinationPath.'/pdf',$orgFile); 
         }
         $filenameArray = explode('/', $originalFileName);
         $filename = $filenameArray[3];
         $origial_image_size = '0';
         if($extension != 'pdf'){

             $image = Image::make($orgFile);
             // $image->resize(400, 400, function ($constraint) {
             //    $constraint->aspectRatio();
             // })->encode($extension)->orientate(); 
             $image->resize(500, 400)->orientate()->encode($extension);
             $origial_image_size = $image->filesize();
             Storage::disk('public')->put($destinationPath.'/'.$filename,$image->__toString()); 
         }
         $input['user_id'] = $userId;
         $input['type'] = $type;
         $input['orginal_path'] = $destinationPath.'/original/'.$filename;
         $input['thumbnail_path'] = $destinationPath.'/'.$filename;;
         $input['file_name'] = $filename;
         $input['extention'] = $extension;
         $input['org_size'] = $origial_image_size;
         DocumentUpload::create($input);

    }
}
