 <aside class="main-sidebar" >
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{asset('images/admin.png')}}" style="width: 50px !important;max-width: 50px !important;" class="img-circle user-img" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Admin</p>         
        </div>
      </div>
     
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
		
        <li>
          <a href="{{route('admin.home')}}">
            <i class="fa fa-dashboard"></i><span>Dashboard</span>
          </a>
		 </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-user"></i> <span>Manage Users</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{route('admin.users')}}"><i class="fa fa-circle-o"></i> View all</a></li>
            <li><a href="{{route('admin.users.verified')}}"><i class="fa fa-circle-o"></i> View KYC completed</a></li>
            <li><a href="{{route('admin.users.pending')}}"><i class="fa fa-circle-o"></i> View KYC pending</a></li>
          </ul>
        </li>
       
        <li class="treeview">
          <a href="#">
            <i class="fa fa-th"></i> <span>Manage Projects</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{route('admin.project.create')}}"><i class="fa fa-circle-o"></i> Create New</a></li>
            <li><a href="{{route('admin.project.list')}}#"><i class="fa fa-circle-o"></i> View Existing</a></li>
          </ul>
        </li>
        <li>
       <a href="#">
            <i class="fa fa-book"></i><span>KYC Requests</span>
          </a>
        </li>
        <li>
        <li>
       <a href="{{route('admin.coin.list')}}">
            <i class="fa fa-book"></i><span>Payment Methods </span>
          </a>
        </li>
        <li>
       <a href="#">
            <i class="fa fa-gears"></i><span>Settings</span>
          </a>
        </li>
        <li>
        </li>
      </ul>
       </section>
    <!-- /.sidebar -->
  </aside>