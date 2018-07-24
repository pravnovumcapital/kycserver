<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Twilio\Rest\Client;
use Authy\AuthyApi;

use App\User;
use App\AuthCode;
use App\DocumentUpload;
use App\ArtemisRequest;

use Validator;
use Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Intervention\Image\ImageManager;
//use Image;
use Carbon\Carbon;

class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $authyApi;

    public function __construct()
    {
        
    }


    public function create(Request $request)
    {


    	$validator = Validator::make($request->all(), [
    	    'email' => 'required|email|max:255|unique:users',
            'password' => 'required',
    	    'first_name' => 'required',
            'last_name'=>'required',
    	    'date_of_birth'=>'required',
    	    'phone_number'=>'required|unique:users',
            'country_code'=>'required',
    	    'platform'=>'required',
    	    'device_security_enable'=>'required',
    	    'device_id'=>'required',
            'type_of_security'=>'required',
    	    'erc20_address'=>['required','regex:/^0x[a-fA-F0-9]{40}$/'],
    	]);
    	if ($validator->fails()) {             
             $validator->getMessageBag()->add('status', 'error');
             foreach ($validator->errors()->all() as $key => $value) {
                 return response()->json(["code"=>404,'message'=>$value], 200);
             }
        }
        elseif ($request->validation == 1) {
            return response()->json(['status'=>'success','code'=>200], 200);
        }
        else{
            $secure_token  = $this->generateSecurityToken();
            $date_of_birth = Carbon::createFromFormat('d/m/Y',$request->date_of_birth);
           
            if(!$date_of_birth)
            {
               return response()->json(['message'=>'incorrect date of birth',"code"=>404], 400);
            }
            
        	$user = User::create([
                'email' => $request->email,
        	    'password' => bcrypt($request->password),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
        	    'date_of_birth' => $date_of_birth,
                'phone_number' => trim(str_replace(' ', '',$request->phone_number)),
                'country_code' => (int)$request->country_code,
        	    'device_security_enable' => $request->device_security_enable,
                'device_id' => $request->device_id,
                'type_of_security' => $request->type_of_security,
                'security_token' => $secure_token,
                'status' => 'pending',
        	    'erc20_address' => $request->erc20_address,
        	]);
            $dataArray['user_id'] = $user->id;
            $dataArray['device_id'] = $request->device_id;
            $dataArray['platform'] = $request->platform;

            $userToken =  $this->generateUserToken($dataArray);
            $user->token = $userToken;
            // if($user)
            // Mail::to($user->email)->queue(new SignUpMail());
        	return response()->json(['user'=>$user,'status'=>'success','code'=>200], 200);
        }
    }
    /**
     * Get a validator for an incoming verification request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function verificationRequestValidator(array $data)
    {
        return Validator::make($data, [
            'country_code' => 'required|string|max:3',
            'phone_number' => 'required|string|max:10',
            'via' => 'required|string|max:4',
        ]);
    }

    /**
     * Get a validator for an code verification request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function verificationCodeValidator(array $data)
    {
        return Validator::make($data, [
            'country_code' => 'required|string|max:3',
            'phone_number' => 'required|string|max:10',
            'otp_code' => 'required|string|max:10'
        ]);
    }

    public function startVerification(Request $request)
    {
        //$authyApi = new AuthyApi('NIwY3GkfBe1C2wPYf6gdPjLySenzozZ1');
        //dd($this->authyApi);
        $authyApi = new AuthyApi('NIwY3GkfBe1C2wPYf6gdPjLySenzozZ1');
        $data = $request->all();
    	$validator = $this->verificationRequestValidator($data);
        extract($data);

        if ($validator->passes()) {
            $response = $authyApi->phoneVerificationStart($phone_number, $country_code, $via);
            if ($response->ok()) {
                return response()->json(['message' => "success","code"=>200,"sucess_message"=>$response->message()],200);
                //return response()->json($response->message(), 200);
            } else {
                return response()->json((array)$response->errors(), 400);
            }
        }

        return response()->json(['errors'=>$validator->errors()], 403);

    }

    protected function verifyCode(Request $request) {
        $authyApi = new AuthyApi('NIwY3GkfBe1C2wPYf6gdPjLySenzozZ1');
        $data = $request->all();
        $validator = $this->verificationCodeValidator($data);
        extract($data);

        if ($validator->passes()) {
            try {
                $result = $authyApi->phoneVerificationCheck($phone_number, $country_code, $otp_code);
                //dd($result->getMessage());
                //dd($result);
                if(!$result->ok())
                { 
                    foreach ($result->errors() as $key => $value) {
                         return response()->json(["code"=>404,"message"=>$value], 400);
                    }
                }
                else{
                    return response()->json(['message' => "success","code"=>200],200);
                }
                return response()->json((array)$result, 200);
            } catch (Exception $e) {
                $response=[];
                $response['code'] = 404;
                $response['exception'] = get_class($e);
                $response['message'] = $e->getMessage();
                $response['trace'] = $e->getTrace();
                return response()->json($response, 403);
            }
        }

        return response()->json(['errors'=>$validator->errors()], 403);
    }

    public function login(Request $request)
    {

        if ($request->has('security_token') && $request->security_token != '') {
            
            $userData=User::where('security_token',$request->security_token)->first();
            if($userData)
            {
                $dataArray['user_id'] = $userData->id;
                $dataArray['device_id'] = $request->device_id;
                $dataArray['platform'] = $request->platform;

                $userToken =  $this->generateUserToken($dataArray);
                $userData->token = $userToken;
               return response()->json(['message' => "success","code"=>200,'user'=>$userData], 200); 
            }
            else{
               return response()->json(['message' => "Invalid email or password!","code"=>404], 200);
            }
        }
        else{
            
            $validator = Validator::make($request->all(), [
                    'email' => 'required',
                    'password' => 'required',
                    'device_id' => 'required',
                    'platform' => 'required',
            ]);
            $fieldName = 'email';
            if ($validator->fails()) {
                 return response()->json($validator->messages(), 200);
            }
            else{

                if (\Auth::attempt([$fieldName => $request->email, 'password' => $request->password])) {          
                     $userData=User::where($fieldName,$request->email)->first();
                     $dataArray['user_id'] = $userData->id;
                     $dataArray['device_id'] = $request->device_id;
                     $dataArray['platform'] = $request->platform;

                     $userToken =  $this->generateUserToken($dataArray);
                     $userData->token = $userToken;
                     return response()->json(['message' => "success","code"=>200,'user'=>$userData], 200); 
                }
                else 
                {
                    return response()->json(['message' => "invalid email or password!","code"=>404], 200);
                }
                
            } 
        }
            
        
    }
    public function generateSecurityToken()
    {
        $randstring = md5(uniqid(rand(), true));
        return $randstring;
    }

    public function generateUserToken($dataArray = array())
    {
        $previousLogin = AuthCode::where('user_id',$dataArray['user_id'])->where('device_id',$dataArray['device_id'])->first();
        $dataArray['auth_code'] = md5($dataArray['user_id']+time());
        if($previousLogin)
        {
            $previousLogin->auth_code = $dataArray['auth_code'];
            $previousLogin->save();
        }
        else{
            $authData = AuthCode::create($dataArray);
        }
        
        return $dataArray['auth_code'];
    }
    public function uploadPassport(Request $request)
    {

        $auth_token = $request->header('token');

        //$this->printArray($request->all());
        
        //dd($request->all());
        if($request->has('test'))
        {
            $auth_token = $request->token;
        }
        $validator = Validator::make($request->all(), [
                    'citizenship' => 'required',
                    'citizenship_id' => 'required',
                    'passport_number' => 'required',
                    'country_of_residence' => 'required',
        ]);
        if ($validator->fails()) {
             $validator->getMessageBag()->add('status', 'error');
             foreach ($validator->errors()->all() as $key => $value) {
                 return response()->json(["code"=>404,'message'=>$value], 200);
             }
        }

        if($auth_token !='')
        {
            $user = $this->getUserFromToken($auth_token);
            if(isset($user->id))
            {
                $checkArray = ['citizenship','passport_number','citizenship_id','country_of_residence'];
                foreach ($request->all() as $key => $value) {
                    
                    if(in_array($key, $checkArray))
                    {
                       if(!isset($user->$key) && isset($request->$key) && $request->$key !=''){
                            $user->$key = $request->$key;
                            $user->save();
                       }
                    }
                }
                $userId = $user->id;   
                // if(!$this->checkArtemisRequested($userId))
                // {
        	        
                // 	$this->requestArtemisKyc($userId);
                // }
                
                if ($request->hasFile('passport_photo')) { 
                        $orgFile=$request->passport_photo; 
                        $extension = $orgFile->getClientOriginalExtension();
                        $validator = Validator::make($request->all(), [
                        'passport_photo' => "mimes:jpeg,bmp,png,gif,svg,pdf|max:4000",
                        ]);
                        if ($validator->fails()) {
                             return response()->json($validator->messages(), 200);
                        }
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
                return response()->json(['message' => "success","code"=>200], 200);

            }
            else{
            	return response()->json(['message' => "Invalid token","code"=>404], 200);
            }

        }
        else{
            return response()->json(['message' => "Missing token","code"=>404], 200);
        }
        
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

    public function checkArtemisRequested($userId)
    {
    	$checkRequest = ArtemisRequest::where('user_id',$userId)->count();
    	if($checkRequest > 0)
    	{
    		return true;
    	}
    	else{
    		return false;
    	}
    }

    public function requestArtemisKyc($userId)
    {
    	$user = User::find($userId);
        $data = array (
			"rfrID"=>$user->id,
			"first_name"=>$user->first_name,
			"last_name"=>$user->last_name,
			"date_of_birth"=>$user->date_of_birth,
			"nationality"=>$user->citizenship,
			"country_of_residence"=>$user->country_of_residence,
			"ssic_code"=>"UNKNOWN",
			"ssoc_code"=>"UNKNOWN",
			"onboarding_mode"=>"NON FACE-TO-FACE",
			"payment_mode"=>"UNKNOWN",
			"product_service_complexity"=>"COMPLEX",
			"emails"=>$user->email,
			"domain_name"=>"NOVUMCAPITAL"
		);
    	$header = ['Content-Type: application/json', 'WEB2PY-USER-TOKEN:03a7a6cb-63b2-47b2-8715-af65aabf28ed'];
    	$curl = curl_init();
	    curl_setopt($curl, CURLOPT_POST, 1);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    if ($header)
	        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	    $result = curl_exec($curl);
	    curl_close($curl);
	    $this->printArray($result);
	    return $result;

		
		$result = callAPI("POST", $url, $data, $header);
    }

    

    public function updateProfile(Request $request)
    {
        $auth_token = $request->header('auth_token');
        //dd($auth_token);
        $userData = $this->getUserFromToken($request->token,'user');
    }

    public function getUserFromToken($token,$type='user')
    {
        $authData = AuthCode::where('auth_code',$token)->first();
        if($authData)
        {
            if($type == 'user')
            {
                $userData = User::find($authData->user_id);
                if($userData->id)
                {
                    return $userData;
                }
                else{
                    return response()->json(['message' => "No user found","code"=>404], 200);
                }
            }
            return $authData;
        }
        else{
            return response()->json(['message' => "Token invalid","code"=>404], 200);
        }
    }
}
