@extends('admin.base.app')
@section('content')
<div class="row" style="padding-right: 30px;">
	<h2>User list</h2>
  @if($projects->count() > 0)
<table class="table table-bordered box table-hover">
    <thead>
      <tr>
        <th>Title</th>
        <th>Short Description</th>
        <th>Logo</th>
        <th>Website Url</th>
        <th>Contact Email</th>
        <th>Created At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    @foreach($projects as $key=>$project)
      <tr class="center-text">
        <td>{{$project->title}}</td>
        <td>{{str_limit($project->short_description, $limit = 10, $end = '...')}}</td>
        <td><img style="width:100px;height:100px" src="{{$project->logo('thumb')}}"></img></td>
        <td>{{$project->website_url}}</td>
        <td>{{$project->contact_email}}</td>
        <td>{{$project->created_at->format('d/m/Y')}}</td>
        <td><a href="{{route('admin.project.edit',$project->id)}}"><span><i class="fa fa-edit text-blue"></i></span></a>
          <a href="#"><span><i class="fa fa-eye text-green"></i></span></a>
          <a href="#" onclick="deleteRecord('modal-danger','Delete this record?','Are you sure delete this record?','Delete','{{$project->id}}','{{route("admin.project.delete",$project->id)}}')"><span><i class="fa fa-trash-o text-red"></i></span></td></a>
      </tr>
    @endforeach

    </tbody>
  </table>
  {{ $projects->links() }}
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