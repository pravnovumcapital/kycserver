@extends('admin.base.app')
@section('content')
<div class="row" style="padding-right: 30px;">
	<h2>User list</h2>
  @if($users->count() > 0)
<table class="table table-bordered box table-hover">
    <thead>
      <tr>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>Email</th>
        <th>Dob</th>
        <th>country code</th>
        <th>Phone</th>
        <th>Status</th>
        <th>Created At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    @foreach($users as $key=>$user)
      <tr class="center-text">
        <td>{{$user->first_name}}</td>
        <td>{{$user->last_name}}</td>
        <td>{{$user->email}}</td>
        <td>{{$user->date_of_birth}}</td>
        <td>{{$user->country_code}}</td>
        <td>{{$user->phone_number}}</td>
        <td>{{$user->status}}</td>
        <td>{{$user->created_at->format('d/m/Y')}}</td>
        <td><a href="{{route('admin.users.edit',$user->id)}}"><span><i class="fa fa-edit text-blue"></i></span></a>
          <a href="#"><span><i class="fa fa-eye text-green"></i></span></a>
          <a href="#" onclick="deleteRecord('modal-danger','Delete this record?','Are you sure delete this record?','Delete','{{$user->id}}','{{route("admin.users.delete",$user->id)}}')"><span><i class="fa fa-trash-o text-red"></i></span></td></a>
      </tr>
    @endforeach

    </tbody>
  </table>
  {{ $users->links() }}
   @else
   <table class="table table-striped">
    <th> No Records !</th>
   </table>
     
   
    @endif
</div>
@endsection
@section('script')
<script type="text/javascript">
  function deleteRecord(modalId,title,body,okayButton,recId,url) {
    $('#'+modalId).find('.modal-title').html(title);
    $('#'+modalId).find('.modal-body').html(body);
    $('#'+modalId).find('.action-button').html(okayButton);
    $('#'+modalId).find('.action-button').data('href',url);
    $('#'+modalId).modal('show');
  }
  $('#modal-danger').on('click', '.action-button', function(e) {
  var $modalDiv = $(e.delegateTarget);
  var url = $(this).data('href');
  var CSRF_TOKEN = "{{ csrf_token() }}";
  $modalDiv.addClass('loading');
  $.ajax({
       url:  url,
       type: 'POST',
       data:"_token="+CSRF_TOKEN,
       dataType: 'JSON',
       success: function (result) {
        if(result.code = 200){

          window.location.href = result.url;
        }
        else{
          alert('An error occured! Please try again later!')
        }
       },
       error: function(XMLHttpRequest, textStatus, errorThrown) { 
              alert('An error occured! Please try again later!');                
       } 
      });
});
</script>
@endsection