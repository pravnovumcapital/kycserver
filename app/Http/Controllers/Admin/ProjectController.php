<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\PaymentMethod;
use App\Project;
use App\ProjectSalePeriod;
use App\ProjectPaymentMethod;
use App\ProjectBankDetails;
use Carbon\Carbon;

use Validator;
use Storage;

class ProjectController extends Controller
{
    public function create()
    {
        $paymentMethods = PaymentMethod::get();
    	return view('admin.project.create',compact('paymentMethods'));
    }
    public function store(Request $request)
    {
        //dd($request->all());
        // echo "<pre>";
        // print_r($request->all());
        // exit;
        //exit;
        
       
        $validator = Validator::make($request->all(), [
                'title' => 'required',
                'short_description' => 'required',
                'detailed_description' => 'required',
                'payment_methods_array' => 'required',
                'website_url' => 'required',
        ]);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('status', 'error');
             foreach ($validator->errors()->all() as $key => $value) {
                 return response()->json(["code"=>400,'message'=>$value], 200);
             }
        }
        if ($request->session()->has('project_id')) {
            $projectData = Project::find(session("project_id"));
            if(!$projectData)
            {
                $projectData = new Project();
            }

        }else{
            $projectData = new Project();
        } 
        $projectData->title = $request->title;
        $projectData->short_description = $request->short_description;
        $projectData->detailed_description = $request->detailed_description;
        $projectData->payment_methods = $request->payment_methods_array;
        $projectData->payment_methods_ids = NULL;
        $projectData->total_raised = $request->total_raised;
        $projectData->max_raise = $request->max_raise;
        $projectData->website_url = $request->website_url;
        $projectData->contact_email = $request->contact_email;

        $projectData->save();

        
        if($projectData)
        {
            $request->session()->put('project_id', $projectData->id);
            $projectId = $projectData->id;
            ProjectSalePeriod::where('project_id',$projectId)->delete();
            //for storing sale period
            for ($i=1; $i <= 10; $i++) { 
                $var = 'sale_period_'.$i;
                if(isset($request->$var))
                {

                    $saleArray = $request->$var;
                    $startDate = Carbon::createFromFormat('m/d/Y',$saleArray["'start'"]);
                    $endDate = Carbon::createFromFormat('m/d/Y',$saleArray["'end'"]);
                    $dateCheck = $this->salePeriodValidation($startDate,$endDate);
                    if($dateCheck){
                        $saleInput['project_id'] = $projectId;
                        $saleInput['sale_start'] = $startDate;
                        $saleInput['sale_end'] = $endDate;
                        $saleInput['discount'] = $saleArray["'discount'"];
                        $saleInput['period_name'] = $var;

                        ProjectSalePeriod::create($saleInput);
                    }
                    else{
                        return response()->json(['message'=>'Please check start and end date of sale period '.$i.'!','code'=>400], 200);
                    }

                }
                else{
                    break;
                }
            }

            //for storing payment methods used for project
            if(isset($request->payment_methods_array) && $request->payment_methods_array !='')
            {
                $paymentArray = json_decode($request->payment_methods_array, true);
                $paymentArrayIds = [];
  				if(!empty($paymentArray)){
                    ProjectPaymentMethod::where('project_id',$projectId)->delete();
  					foreach ($paymentArray as $key => $paymentMethod) {
  						//for storing bank details
  						$paymentMethodData = PaymentMethod::find($paymentMethod); 
  						//dd($paymentMethodData);
  						if($paymentMethodData){

  							//ProjectPaymentMethod
  							$paymentMethodInput = array();
  							$paymentMethodInput['project_id'] = $projectId;
  							$paymentMethodInput['method_name'] = $paymentMethodData->name;
  							$paymentMethodInput['method_id'] = $paymentMethodData->id;
  							$paymentMethodInput['type'] = $paymentMethodData->type;
  							//
  							$methodName = $paymentMethodData->name;
  							if(isset($request->$methodName["'price_per_token'"]) && $request->$methodName["'price_per_token'"] != '')
  							{
  								$paymentMethodInput['price_per_token'] = $request->$methodName["'price_per_token'"];
  							}
  							if(isset($request->$methodName["'wallet'"]) && $request->$methodName["'wallet'"] != '')
  							{
  								$paymentMethodInput['wallet_address'] = $request->$methodName["'wallet'"];
  							}
  							if($paymentMethodData->type == 'bank')
  							{
                                ProjectBankDetails::where('project_id',$projectId)->delete();
  								$bankInput['project_id'] = $projectId;
  								$bankInput['payment_method_id'] = $paymentMethodData->id;
  								$bankInput['payment_method_name'] = $paymentMethodData->name;
  								$bankInput['account_name'] = $request->account_name;
  								$bankInput['holder_address'] = $request->holder_address;
  								$bankInput['account_number'] = $request->account_number;
  								$bankInput['swift_code'] = $request->swift_code;
  								$bankInput['bank_name'] = $request->bank_name;
  								$bankInput['bank_address'] = $request->bank_address;
  								$bankObject = ProjectBankDetails::create($bankInput);
  								if($bankObject)
  								{
  									$paymentMethodInput['project_bank_detail_id'] = $bankObject->id;
  								}

  							}
                              $paymentMethod = ProjectPaymentMethod::create($paymentMethodInput);
                              array_push($paymentArrayIds,$paymentMethod->id);
  						}
            		}
                  }
                  $projectData->payment_methods_ids = serialize($paymentArrayIds);
                  $projectData->save();
            	
            }
            else{
            	return redirect()->back()->with('error','Please add payment method!');
            }
            //for storing logo
            if(isset($request->logo) && $request->hasFile('logo'))
            {
               $logoName =  $this->uploadLogo($request->logo,$projectId,'logo');
               $projectData->project_logo = $logoName;
               $projectData->save();
            }
            //for storing thumbnail logo
            if(isset($request->thumbnail_logo) && $request->hasFile('thumbnail_logo'))
            {  
                $thumbLogoName = $this->uploadLogo($request->thumbnail_logo,$projectId,'thumb');
                $projectData->thumbnail_logo = $thumbLogoName;
                $projectData->save();
            }
        
            
            
            $request->session()->forget('project_id');
            //return redirect()->back()->with('success','Project created sucessfully!');
            return response()->json(['message'=>'Project created sucessfully!','code'=>200], 200);
        }
        else{
            return response()->json(['message'=>'Oops!,Something went wrong!','code'=>400], 400);
        	//return redirect()->back()->with('error','Please try again later!');
        }
        
        //dd($projectData);


    }
    public function listProjects()
    {
        $projects = Project::paginate();
        return view('admin.project.list',compact('projects'));
        //dd($projects);
    }
    public function view($projectId)
    {
    	
    }
    public function edit($projectId)
    {
        $paymentMethods = PaymentMethod::get();
        $project = Project::find($projectId);
        if($project)
        {
            return view('admin.project.edit',compact('project','paymentMethods'));
        }
    }
    public function update($value='')
    {
    	
    }
    public function deleteProject($id)
    {
    	
    }

    public function listCoin($value='')
    {
        $coins = PaymentMethod::get();
        return view('admin.coin.index',compact('coins'));
    }
    public function createCoin($value='')
    {
        return view('admin.coin.create');
    }
    public function storeCoin(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:3',
                'type' => 'required|string'
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }  
        PaymentMethod::create($request->all());
        return redirect()->route('admin.coin.list')->with('success','Added Successfully!');
    }
    public function updateCoin($value='')
    {
        
    }
    public function uploadLogo($orgFile,$projectId,$type)
    {
         $destinationPath = 'admin/project/'.$projectId.'/'.$type.'/';
         //dd($orgFile);
         $originalFileName = Storage::disk('public')->put($destinationPath,$orgFile); 
         $filenameArray = explode('/', $originalFileName);
         $filename = $filenameArray[5];
         return $filename;
        
    }
    public function salePeriodValidation($start,$end)
    {
        $start = strtotime($start);
        $end = strtotime($end);
        if($start < $end)
        {
            return true;
        }
        else{
            return false;
        }
    }
}
