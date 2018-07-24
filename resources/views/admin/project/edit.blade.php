@extends('admin.base.app')
@section('content')
<div class="row">
  <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Edit project</h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <!-- <form role="form" action="{{route('admin.project.store')}}" method="POST" enctype="multipart/form-data" id="project_form"> -->
      <form role="form"  enctype="multipart/form-data" id="project_form" data-action="{{route('admin.project.update')}}">
      
        {{csrf_field()}}
              <div class="box-body">
                <div class="col-lg-6 col-md-6 box">
                <div class="form-group">
                  <label for="exampleInputEmail1">Title</label>
                  <input type="text" class="form-control" id="project_title" placeholder="Enter title of the project" name="title" required="" value="{{$project->title}}">
                </div>
                <div class="form-group">
                  <label>Short Description</label>
                  <textarea class="form-control" rows="2" placeholder="Enter short description about this project!" name="short_description" id="project_short_desc" required="">{{$project->short_description}}</textarea>
                </div>
                 <div class="form-group">
                  <label>Detailed Description</label>
                  <textarea class="form-control" rows="4" placeholder="Enter detailed description about this project!" name="detailed_description" id="project_detailed_desc" required="">{{$project->detailed_description}}</textarea>
                </div>
                <div class="form-group">
                  <div class="preview" style="width:100px;"><img src="{{$project->logo('thumb')}}"></img></div>
                  <label for="project_thumb_logo">Logo Thumbnail</label>
                  <input type="file" name="thumbnail_logo" id="project_thumb_logo">

                  <p class="help-block">Please upload 200 X 200 pixels</p>
                </div>
                <div class="form-group">
                <div class="preview" style="width:100px;"><img src="{{$project->logo()}}"></img></div>
                  <label for="project_logo">Logo</label>
                  <input type="file" name="logo" id="project_logo">

                  <p class="help-block">Please upload 600 X 600 pixels</p>
                </div>
                <div class="sale-period-div box">
                  <div class="box-header with-border">
                  <label for="project_logo">Sale periods</label>
                  <span class="pull-right remove-period-btn" onclick="removeSalePeriod()" style="display: none;"><i class="fa fa-2x fa-minus-circle red-icon"></i></span>
                  <span class="pull-right add-period-btn" onclick="addSalePeriodMore()"><i class="fa fa-2x fa-plus-circle green-icon"></i></span>
                  @foreach($project->getSalesPeriods as $key=>$period)
                  <div class="sale-period-content">
                      <div class="form-group">
                        <label >Sale period {{$key+1}} start</label>
                        <input type="text" class="form-control date_picker" id="sale_period{{$key+1}}_start" placeholder="Enter start date of sale" name="sale_period_{{$key+1}}['start']" value="{{$period->sale_start->format('m/d/Y')}}" required="">
                      </div>
                      <div class="form-group">
                        <label>Sale period {{$key+1}} end</label>
                        <input type="text" class="form-control date_picker" id="sale_period{{$key+1}}_end" placeholder="Enter end date of sale" name="sale_period_{{$key+1}}['end']" value="{{$period->sale_end->format('m/d/Y')}}" required="">
                      </div>
                      <div class="form-group">
                        <label>Discount</label>
                        <input type="number" class="form-control" id="discount{{$key+1}}" placeholder="Enter discount" name="sale_period_{{$key+1}}['discount']" value="{{$period->discount}}">
                      </div>
                  </div>
                  @endforeach
                  </div> 
                
                </div>
              </div>
               <div class="col-lg-6 col-md-6">
                <div class="payment-div box">
                  <div class="box-header with-border">
                  <label>Add Payment mode</label>
                  </div>
                  <div class="payment-mode-content">
                    <div class="form-group">
                      <label>Select payment mode</label>
                      <select class="form-control" onchange="addPaymentMode(this)" required>
                        <option value="">Select a payment mode</option>
                        @foreach($paymentMethods as $key=>$method)
                        <option value="{{$method->id}}" data-name="{{$method->name}}">{{$method->name}}</option>
                        @endforeach
                      </select>
                      <input type="hidden" id="payment_added" name="payment_methods_array" value="{{$project->payment_methods}}">
                    </div>
                    <div class="payment-mode-append-div">
                      @foreach($project->getPaymentModes as $key=>$paymentMethod)
                      @if($paymentMethod->type == "coin")
                      <div class="coin_details appending-box">
                        <span class="pull-right remove-coin-btn" onclick="removePaymentType('coin_details',this,{{$paymentMethod->method_id}})"><i class="fa fa-2x fa-minus-circle red-icon"></i></span>
                        <div class="form-group">
                          <label for="exampleInputEmail1">Coin Type</label>
                          <input type="text" class="form-control" id="account_name" name="{{$paymentMethod->method_name}}['name']" readonly value="{{$paymentMethod->method_name}}">
                        </div>
                        <div class="form-group">
                          <label for="exampleInputEmail1">Wallet Address</label>
                          <input type="text" class="form-control" id="account_name" name="{{$paymentMethod->method_name}}['wallet']" required="" value="{{$paymentMethod->wallet_address}}">
                        </div>
                        <div class="form-group">
                          <label for="exampleInputEmail1">Gross Price Per Token</label>
                          <input type="text" class="form-control" id="account_name" name="{{$paymentMethod->method_name}}['price_per_token']" required="" value="{{$paymentMethod->price_per_token}}">
                        </div>
                      </div>
                      @elseif($paymentMethod->type == "bank")
                      <div class="bank_details appending-box">
                          <label>Payment Mode : USD</label>
                          <span class="pull-right remove-bank-btn" onclick="removePaymentType('bank_details',this,'1')"><i class="fa fa-2x fa-minus-circle red-icon"></i></span>
                          <input type="hidden" name="payment_mode_usd" value="USD">
                          <div class="form-group">
                            <label for="exampleInputEmail1">Gross Price Per Token</label>
                            <input type="text" class="form-control" id="account_name" name="USD['price_per_token']" required="" value="{{$paymentMethod->price_per_token}}">
                          </div>
                          <div class="form-group">
                            <label for="exampleInputEmail1">Account Name</label>
                            <input type="text" class="form-control" id="account_name" placeholder="Enter account name" name="account_name" required="" value="{{$paymentMethod->bank->account_name}}">
                          </div>
                          <div class="form-group">
                            <label>Address</label>
                            <textarea class="form-control" rows="4" placeholder="Enter account holder adress!" name="holder_address" id="holder_address" required="">{{$paymentMethod->bank->holder_address}}</textarea>
                          </div>
                          <div class="form-group">
                            <label for="exampleInputEmail1">Account No</label>
                            <input type="number" class="form-control" id="account_number" placeholder="Enter account number" name="account_number" required="" value="{{$paymentMethod->bank->account_number}}">
                          </div>
                          <div class="form-group">
                            <label for="exampleInputEmail1">SWIFT Code</label>
                            <input type="text" class="form-control" id="swift_code" placeholder="Enter SWIFT code" name="swift_code" required="" value="{{$paymentMethod->bank->swift_code}}">
                          </div>
                          <div class="form-group">
                            <label for="exampleInputEmail1">Bank Name</label>
                            <input type="text" class="form-control" id="bank_name" placeholder="Enter bank name" name="bank_name" required="" value="{{$paymentMethod->bank->bank_name}}">
                          </div>
                          <div class="form-group">
                            <label>Bank Address</label>
                            <textarea class="form-control" rows="4" placeholder="Enter bank address!" name="bank_address" id="bank_address" required="">{{$paymentMethod->bank->bank_address}}</textarea>
                          </div>
                        </div>
                      @endif
                      @endforeach
                    </div>
                  </div>
                 </div>

                 <div class="form-group">
                  <label for="exampleInputEmail1">Total Raised</label>
                  <input type="number" class="form-control" id="total_raised" placeholder="Enter total raised " name="total_raised" required="">
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Max Raise</label>
                  <input type="number" class="form-control" id="project_title" placeholder="Enter max raise" name="max_raise" required="">
                </div>
                  <div class="form-group">
                  <label for="exampleInputEmail1">Website URL</label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-safari"></i></span>
                    <input type="url" class="form-control" placeholder="Enter website url" name="website_url">
                  </div>
                </div>

                 <div class="form-group">
                  <label for="exampleInputEmail1">Contact Email</label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    <input type="email" class="form-control" placeholder="Enter contact email" name="contact_email">
                  </div>
                </div>
              </div> 
              <div class="box-footer col-lg-12 col-md-12">
                <button type="submit" class="btn btn-primary pull-right submitBtn">Submit</button>
              </div>  
              </div>
              <!-- /.box-body -->
      </form>  
  </div>      
</div>
<div class="" id="usd_payment_content" style="display: none;">
  <div class="bank_details appending-box">
    <label>Payment Mode : USD</label>
    <span class="pull-right remove-bank-btn" onclick="removePaymentType('bank_details',this,'1')"><i class="fa fa-2x fa-minus-circle red-icon"></i></span>
    <input type="hidden" name="payment_mode_usd" value="USD">
    <div class="form-group">
      <label for="exampleInputEmail1">Gross Price Per Token</label>
      <input type="text" class="form-control" id="account_name" name="USD['price_per_token']" required="">
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">Account Name</label>
      <input type="text" class="form-control" id="account_name" placeholder="Enter account name" name="account_name" required="">
    </div>
    <div class="form-group">
      <label>Address</label>
      <textarea class="form-control" rows="2" placeholder="Enter account holder adress!" name="holder_address" id="holder_address" required=""></textarea>
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">Account No</label>
      <input type="number" class="form-control" id="account_number" placeholder="Enter account number" name="account_number" required="">
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">SWIFT Code</label>
      <input type="text" class="form-control" id="swift_code" placeholder="Enter SWIFT code" name="swift_code" required="">
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">Bank Name</label>
      <input type="text" class="form-control" id="bank_name" placeholder="Enter bank name" name="bank_name" required="">
    </div>
    <div class="form-group">
      <label>Bank Address</label>
      <textarea class="form-control" rows="2" placeholder="Enter bank address!" name="bank_address" id="bank_address" required=""></textarea>
    </div>
  </div>
</div>
<div class="" id="coin_payment_content" style="display: none;">
  
  <div class="coin_details appending-box">
    <span class="pull-right remove-coin-btn" onclick="removePaymentType('coin_details',this,'YYYY')"><i class="fa fa-2x fa-minus-circle red-icon"></i></span>
    <div class="form-group">
      <label for="exampleInputEmail1">Coin Type</label>
      <input type="text" class="form-control" id="account_name" name="XXXX['name']" readonly value="XXXX">
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">Wallet Address</label>
      <input type="text" class="form-control" id="account_name" name="XXXX['wallet']" required="">
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">Gross Price Per Token</label>
      <input type="text" class="form-control" id="account_name" name="XXXX['price_per_token']" required="">
    </div>
  </div>
</div>
<div id="sale_period_main_content" style="display:none;">
  <div class="sale-period-content">
        <div class="form-group">
          <label >Sale period XX start</label>
          <input type="text" class="form-control date_picker" id="sale_periodXX_start" placeholder="Enter start date of sale" name="sale_period_XX['start']" value="" required="">
        </div>
        <div class="form-group">
          <label>Sale period XX end</label>
          <input type="text" class="form-control date_picker" id="sale_periodXX_end" placeholder="Enter end date of sale" name="sale_period_XX['end']" value="" required="">
        </div>
        <div class="form-group">
          <label>Discount</label>
          <input type="number" class="form-control" id="discountXX" placeholder="Enter discount" name="sale_period_XX['discount']" value="">
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
  function addSalePeriodMore() {

    $('.remove-period-btn').show();
     count = $('.sale-period-div').find('.sale-period-content').length + 1;
     addSalePeriod(count);
  }
  function removeSalePeriod(){
    count = $('.sale-period-div').find('.sale-period-content').length;
    if(count > 1){
      $('.sale-period-div').find('.sale-period-content').last().remove();
    }
    if(count == 2)
    {
      $('.remove-period-btn').hide();
    }
  }
  function addSalePeriod(count) {
    //liveCount = $('.sale-period-div').find('.sale-period-content').length + 1;
    var content = $('#sale_period_main_content').html();
    content = content.replace(/XX/g, count);
    content = content.replace(/hasDatepicker/g, "");
    $('.sale-period-div').append(content);
    
    $('.date_picker').datepicker();
  }
  function addPaymentMode(obj) {
    var element = obj.value;
    if(element){
      var elementName =$(obj).find(':selected').data('name');
      //alert(elementName);
      var value = $('#payment_added').val(); //retrieve array
      if(value)
      {
        paymentArray = JSON.parse(value);
      }
      else{
        paymentArray = [];
      }
      if(!(paymentArray.indexOf(element) >=0))
      {
        paymentArray.push(element);
        $('#payment_added').val(JSON.stringify(paymentArray));
        console.log(paymentArray);
        addPaymentContent(elementName,element);
        
      }
      else{
        alert('Already added!');
      }
    }
    //return false;

   }
   function addPaymentContent(value,id) {
     if(value == 'USD')
     {
      content = $('#usd_payment_content').html();
      $('.payment-mode-append-div').append(content);
     }
     else {
      content = $('#coin_payment_content').html();     
      content = content.replace(/XXXX/g, value);
      content = content.replace(/YYYY/g, id);
      $('.payment-mode-append-div').append(content);
      
     }
     
   }
   function removePaymentType(removingClass,obj,type) {
      $(obj).closest('.'+removingClass).remove();
      var value = $('#payment_added').val(); //retrieve array
      console.log(value);
      if(value)
      {
        paymentArray = JSON.parse(value);
        var index = paymentArray.indexOf(type);
        console.log(index);
        if (index > -1) {
          paymentArray.splice(index, 1);
          $('#payment_added').val(JSON.stringify(paymentArray));
          console.log(paymentArray);
        }
      }
   }
   //addSalePeriod(1);

   $(document).ready(function(e){
    $("#project_form").on('submit', function(e){
        e.preventDefault();
        //alert(this.data('action'));
        //alert($(this).data('action'))
        //var url
        //return false;
        $.ajax({
            type: 'POST',
            url: $(this).data('action'),
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
                $('.submitBtn').attr("disabled","disabled");
                //$('#project_form').css("opacity",".5");
                $(".se-pre-con").show();
            },
            success: function(result){
              $(".se-pre-con").fadeOut("slow");
              $(".submitBtn").removeAttr("disabled");
              console.log(result);
              //return false;
                if(result.code == 200){
                    $('#project_form')[0].reset();
                    $('.message_div').html('<div class="alert alert-success">'+result.message+'</div> ');
                    setTimeout(function(){ location.reload(); }, 5000);
                }else{
                    $('.message_div').html('<div class="alert alert-danger">'+result.message+'</div> ');
                    //$('.statusMsg').html('<span style="font-size:18px;color:#EA4335">Some problem occurred, please try again.</span>');
                }
                
            },
            error:function(result)
            {
              console.log(result);
                $(".se-pre-con").fadeOut("slow");
                $(".submitBtn").removeAttr("disabled");
                $('.message_div').html('<div class="alert alert-danger">'+result.message+'</div> ');
            }
        });
    });
   });
 $('.date_picker').datepicker();

</script>
@endsection
@section('style')
<style type="text/css">
  .sale-period-content{
    border: 1px solid #ccc;
    padding: 9px;
    margin: 10px;
  }
  .sale-period-div{
    padding-bottom: 10px;
  }
  .green-icon{
    color:green;
  }
  .red-icon{
    color:red;
  }
  .appending-box{
    padding: 10px;
    border: 1px solid #ccc;
    margin: 10px;
  }
</style>
@endsection