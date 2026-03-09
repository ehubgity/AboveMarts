@include('admin.head')

@include('admin.header')
@include('admin.sidebar')
<!-- main-content -->
<div class="main-content app-content">

<!-- main-header -->
<div class="main-header sticky side-header nav nav-item">
<div class="container-fluid">
<div class="main-header-left ">
						<div class="app-sidebar__toggle mobile-toggle" data-toggle="sidebar">
							<a class="open-toggle" href="#"><i class="header-icons" data-eva="menu-outline"></i></a>
							<a class="close-toggle" href="#"><i class="header-icons" data-eva="close-outline"></i></a>
						</div>
						
				</div>
      

    
    <div class="main-header-right">
        <div class="nav nav-item  navbar-nav-right ml-auto">
            
            <div class="nav-item full-screen fullscreen-button">
                <a class="new nav-link full-screen-link" href="#"><i class="ti-fullscreen"></i></span></a>
            </div>
            
            
            <button class="navbar-toggler navresponsive-toggler d-sm-none" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-4"
                aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon fe fe-more-vertical "></span>
            </button>
           
            <div class="dropdown main-header-message right-toggle">
                <a class="nav-link " data-toggle="sidebar-right" data-target=".sidebar-right">
                    <i class="ti-menu tx-20 bg-transparent"></i>
                </a>
            </div>
        </div>
    </div>
</div>
</div>
<!-- /main-header -->

<!-- mobile-header -->
<div class="responsive main-header collapse" id="navbarSupportedContent-4">
<div class="mb-1 navbar navbar-expand-lg  nav nav-item  navbar-nav-right responsive-navbar navbar-dark d-sm-none ">
    <div class="navbar-collapse">
        <div class="d-flex order-lg-2 ml-auto">
           
            <div class="d-md-flex">
                <div class="nav-item full-screen fullscreen-button">
                    <a class="new nav-link full-screen-link" href="#"><i class="ti-fullscreen"></i></span></a>
                </div>
            </div>
            
            <div class="dropdown main-header-message right-toggle">
                <a class="nav-link " data-toggle="sidebar-right" data-target=".sidebar-right">
                    <i class="ti-menu tx-20 bg-transparent"></i>
                </a>
            </div>
        </div>
    </div>
</div>
</div>
<!-- mobile-header -->

<!-- container -->
<div class="container-fluid">

    
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="left-content">
        <div class="d-flex">
            <i class="fas fa-user-shield text-muted hover-cursor"></i>
            <p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;Edit Profile&nbsp;</p>
            
        </div>
    </div>
    
</div>
<!-- /breadcrumb -->



<!-- row -->
<div class="row row-sm">
<div class="col-xl-6 col-lg-6 col-md-12">
        <div class="card crypto crypt-primary overflow-hidden">
            <div class="card-body iconfont text-left">
                <div class="media">
                    <div class="coin-logo bg-primary-transparent">
                        <i class="fas fa-user-shield text-primary"></i>
                    </div>
                    <div class="media-body">
                        <h3>Personal Information</h3>
                    </div>
                    
                </div>
                <div class="flot-wrapper">
                    <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                </div>
            </div>
            
        </div>
    </div>
   
</div>
<!-- /row -->
            



{{-- <div class="col-lg-12 col-md-12">
						<div class="card">
							<div class="card-body">
								
								
								<div class="row">
									<div class="col-md-12 col-lg-12 col-xl-6 mx-auto d-block">
										<div class="card card-body pd-10 pd-md-20 border shadow-none">
                                            <h5 class="card-title mg-b-20">User Information</h5>
                                            <form action="" method="post">
                                                <div class="form-group">
                                                    <label class="main-content-label tx-11 tx-medium tx-gray-600">Username</label> <input class="form-control" id="uname" name="uname" type="text" value="<?php echo $call->getAdminData('uname');?>">
                                                </div>
                                                <div class="form-group">
                                                    <label class="main-content-label tx-11 tx-medium tx-gray-600">Email</label> <input class="form-control"name="email" id="email" type="text" value="<?php echo $call->getAdminData('email');?>">
                                                </div>
                                                <button class="btn btn-main-primary btn-block" name="suba">Update Profile</button>
                                            </form>
										</div>
									</div>
								</div>
							</div>
						</div>
</div> --}}
            @if (Auth::guard('admin')->user()->role == 'Superadmin')

{{-- <div class="col-lg-12 col-md-12">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-md-12 col-lg-12 col-xl-6 mx-auto d-block">
										<div class="card card-body pd-10 pd-md-20 border shadow-none">
                                            <h5 class="card-title mg-b-20">Your Account Details</h5>
                                            <form action="{{ route('adminprofile') }}" method="post">
                                                @csrf
                                                <div class="form-group">
                                                    <label class="main-content-label tx-11 tx-medium tx-gray-600">USDT  Wallet</label> <input class="form-control"  type="text" value="{{ $coinu[0]->coinAddress}}" name="wallet">
                                                </div>
                                                
                                                <button class="btn btn-main-primary btn-block" name="sub" type="submit">Update Account Details</button>
                                            </form>
											
										</div>
									</div>
								</div>
							</div>
						</div>
					</div> --}}

<div class="col-lg-12 col-md-12">
						<div class="card">
							<div class="card-body">
								
								
								<div class="row">
									<div class="col-md-12 col-lg-12 col-xl-6 mx-auto d-block">
										<div class="card card-body pd-10 pd-md-20 border shadow-none">
                                            <h5 class="card-title mg-b-20">Security Information</h5>
                                            <form action="{{ route('adminprofilepass') }}" method="post">
                                                @csrf
                                                <div class="form-group">
                                                    <label class="main-content-label tx-11 tx-medium tx-gray-600">Old Password</label> <input class="form-control" id="oldPassword" name="currentPassword" type="password">
                                                </div>
                                                <div class="form-group">
                                                    <label class="main-content-label tx-11 tx-medium tx-gray-600">New Password</label> <input class="form-control"id="password" name="password" type="password">
                                                </div>
                                                <div class="form-group">
                                                    <label class="main-content-label tx-11 tx-medium tx-gray-600">Confirm Password</label> <input class="form-control" id="password_confirmation" name="password_confirmation" type="password">
                                                </div>	
                                                <button class="btn btn-main-primary btn-block" type="subp">Update Security Info</button>
                                            </form>
										</div>
									</div>
								</div>
							</div>
						</div>
</div>
@else
<div class="col-lg-12 col-md-12">
						<div class="card">
							<div class="card-body">
								
								
								<div class="row">
									<div class="col-md-12 col-lg-12 col-xl-6 mx-auto d-block">
										<div class="card card-body pd-10 pd-md-20 border shadow-none">
                                            <h5 class="card-title mg-b-20">Security Information</h5>
                                            <form action="{{ route('adminprofilepass') }}" method="post">
                                                @csrf
                                                <div class="form-group">
                                                    <label class="main-content-label tx-11 tx-medium tx-gray-600">Old Password</label> <input class="form-control" id="oldPassword" name="currentPassword" type="password">
                                                </div>
                                                <div class="form-group">
                                                    <label class="main-content-label tx-11 tx-medium tx-gray-600">New Password</label> <input class="form-control"id="password" name="password" type="password">
                                                </div>
                                                <div class="form-group">
                                                    <label class="main-content-label tx-11 tx-medium tx-gray-600">Confirm Password</label> <input class="form-control" id="password_confirmation" name="password_confirmation" type="password">
                                                </div>	
                                                <button class="btn btn-main-primary btn-block" type="subp">Update Security Info</button>
                                            </form>
										</div>
									</div>
								</div>
							</div>
						</div>
</div>

@endif
<!-- Container closed -->

</div>
<!-- main-content closed -->

<!-- Right-sidebar-->
<div class="sidebar sidebar-right sidebar-animate">
<div class="p-3">
    <a href="#" class="text-right float-right" data-toggle="sidebar-right" data-target=".sidebar-right"><i class="fe fe-x"></i></a>
</div>


<div class="panel-body tabs-menu-body side-tab-body p-0 border-0 ">
    <div class="tab-content">
        <div class="tab-pane active" id="tab">
            {{-- <div class="card-body p-0">
                
                <a class="dropdown-item mt-4 border-top" href="editprofile.php">
                    <i class="dropdown-icon fe fe-edit mr-2"></i> Edit Profile
                </a>
                <a class="dropdown-item  border-top" href="logout.php">
                    <i class="dropdown-icon fas fa-sign-out-alt mr-2"></i> Log Out
                </a>
              
            </div> --}}
        </div>
        
    </div>
</div>
</div>
@include('admin.footer');