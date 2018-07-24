@extends('admin.base.app')
@section('content')
<div class="row" style="padding-right: 30px;">
  <h2>Payment Methods</h2>

  <a href="{{route('admin.coin.create')}}"><button type="button" class="btn btn-primary pull-right">Create New</button></a>
  @if($coins->count() > 0)
<table class="table table-bordered box table-hover">
    <thead>
      <tr>
        <th>S No</th>
        <th>Method Name</th>
        <th>Method Type</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    @foreach($coins as $key=>$coin)
      <tr class="center-text">
        <td>{{$key+1}}</td>
        <td>{{$coin->name}}</td>
        <td>{{ucfirst($coin->type)}}</td>
        <td><button type="button" class="btn btn-primary">Edit</button></td>
          
      </tr>
    @endforeach

    </tbody>
  </table>
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