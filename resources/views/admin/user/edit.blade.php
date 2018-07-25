@extends('admin.base.app')
@section('content')
<div class="row">
  <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Edit user</h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <form role="form" action="{{route('admin.users.update',$user->id)}}" method="POST" enctype="multipart/form-data" id="user_form">
      <!-- <form role="form"  enctype="multipart/form-data" id="project_form" data-action="{{route('admin.project.store')}}"> -->
      
        {{csrf_field()}}
              <div class="box-body">
                  <div class="col-lg-6 col-md-6 box">
                    <div class="form-group">
                      <label for="user_name">First Name</label>
                      <input type="text" class="form-control" id="first_name" placeholder="Enter first name of the user" name="first_name" required="" value="{{$user->first_name}}">
                    </div>
                    <div class="form-group">
                      <label for="last_name">Last Name</label>
                      <input type="text" class="form-control" id="last_name" placeholder="Enter last name of the user" name="last_name" required="" value="{{$user->last_name}}">
                    </div>
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" placeholder="Enter email of the user" name="email" required="" value="{{$user->email}}">
                    </div>
                    <div class="form-group">
                      <label for="date_of_birth">Date of Birth</label>
                      <input type="text" class="form-control date_picker" id="date_of_birth" placeholder="Enter dob of the user" name="date_of_birth" required="" value="{{$user->date_of_birth}}">
                    </div>
                    <div class="form-group">
                      <label for="phone_number">Phone Number</label>
                      <input type="text" class="form-control" id="phone_number" placeholder="Enter phone number of the user" name="phone_number" required="" value="{{$user->phone_number}}">
                    </div>
                    <div class="form-group">
                      <label for="country_code">Country Code</label>
                      <input type="text" class="form-control" id="country_code" placeholder="Enter country code of the user" name="country_code" required="" value="{{$user->country_code}}">
                    </div>
                    <div class="form-group">
                      <label for="country_of_residence">Residence Country</label>
                      <input type="text" class="form-control" id="country_of_residence" placeholder="Enter country code of the user" name="country_of_residence" required="" value="{{$user->country_of_residence}}">
                    </div>
                    <div class="form-group">
                      <label for="country_of_residence">Device Security Enabled</label><br>
                      <input type="radio" name="device_security_enable" value="true"> True<br>
                      <input type="radio" name="device_security_enable" value="false"> False<br>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 box">
                      <div class="form-group">
                        <label for="country_of_residence">Citizenship</label>
                          <select class="form-control">
                          <option value="">Select</option>
                          @foreach($nationalities as $key=>$nationality)
                          @if($key == $user->citizenship_id)
                          <option value="{{$key}}" selected="selected">{{$nationality}}</option>
                          @else
                          <option value="{{$key}}">{{$nationality}}</option>
                          @endif
                          @endforeach
                          </select>
                      </div>
                      <div class="form-group">
                        <label for="passport_number">Passport Number</label><br>
                        <input type="text" class="form-control" id="passport_number" placeholder="Enter passport number of the user" name="passport_number" required="" value="{{$user->passport_number}}">
                      </div>
                      <div class="form-group">
                        <label for="erc20_address">Wallet Address</label><br>
                        <input type="text" class="form-control" id="erc20_address" placeholder="Enter wallet address" name="erc20_address" required="" value="{{$user->erc20_address}}">
                      </div>
                      <div class="form-group">
                        <div class="preview-image">
                          <label>Passport image</label>
                          <img id="preview_passport" src="{{$user->passport_photo}}">
                          <input type="file" name="passport_photo" id="passport_photo" onchange="readURL(this,'preview_passport')">
                        </div>
                        <div class="preview-image">
                          <label>Selfie image</label>
                          <img id="preview_selfie" src="{{$user->selfie_photo}}">
                          <input type="file" name="selfie_photo" id="selfie_photo" onchange="readURL(this,'preview_selfie')">
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
@endsection
@section('script')
<script type="text/javascript">
$('.date_picker').datepicker(
        {changeMonth: true,
        changeYear: true,
        yearRange: '-100:+0',
        dateFormat: "dd/mm/yy",
});
function readURL(input,id) {
  if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#'+id).attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
}
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
  .preview-image{
    width:50%;
    float:left;
  }
  .preview-image img{
    width:200px;
    height:300px;
  }
</style>
@endsection