<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KYC | Dashboard</title>    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="{{url('fontawesome/css/font-awesome.css')}}">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{url('css/AdminLTE.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('css/_all-skins.min.css')}}"> 
    <link rel="stylesheet" type="text/css" href="{{url('css/admin.css')}}"> 
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css"> 
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">


    <style>
		.fa.pull-right {
			margin-left: -0.4em;
		}
        /*Cropper*/
        .img-preview {
            width: 300px;
            height: 300px;
            float: left;
            margin-right: 10px;
            margin-bottom: 10px;
            overflow: hidden;
            text-align: center;
        }
    </style>
    @yield('style')
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>    <![endif]-->
    <script type="text/javascript" src="{{asset('js/jquery-1.12.3.min.js')}}"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script> 
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>   
    <script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>  
    <script type="text/javascript"  src="{{asset('js/app.min.js')}}"></script>
	<!-- <script type="text/javascript"  src="{{asset('js/adminlte.min.js')}}"></script> -->

    
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="se-pre-con"></div>
<div class="wrapper">@include('admin.base.navbar')    @include('admin.base.sidebar')            <!-- model dialoge -->
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h3>Confirm Delete <i class="fa fa-exclamation-triangle" style="color: red"
                                                                aria-hidden="true"></i></h3></div>
                <div class="modal-body"> Are you sure you want to delete this?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger btn-ok">Delete</a></div>
            </div>
        </div>
    </div>    <!-- model dialoge -->
    <div class="content-wrapper col-lg-10 col-md-10">  <!-- Content Header (Page header) -->
        <section class="content">
            <section class="content-header"><h1></h1>
              <!--   <ol class="breadcrumb">
                  <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active"></li>
              </ol> -->
            </section> 
                <div class="message_div"></div>
                @if(session()->has('success'))
                <div class="alert alert-success">{!! session('success') !!}</div>            
                @endif           
                 @if(session()->has('danger'))
                <div class="alert alert-danger">{!! session('danger') !!}</div>            
                @endif  
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                 @endif          
                @yield('content')
                 
        </section>

    </div>
    <div class="modal modal-danger fade in" id="modal-danger" >
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"></h4>
              </div>
              <div class="modal-body">
                <p></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left close-button" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-outline action-button" data-href=""></button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
</div>


@yield('customCode')
@yield('script')
<script>
    $(window).load(function() {
            // Animate loader off screen
            $(".se-pre-con").fadeOut("slow");
        });
</script>
</body>
</html>