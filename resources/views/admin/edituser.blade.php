@include('admin.head')

@include('admin.header')
@include('admin.sidebar')
<!-- main-sidebar -->
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
        <div class="main-header-center ">
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
            <h3 class="content-title mb-2"></h3>
            <div class="d-flex">
                <i class="mdi mdi-home text-muted hover-cursor"></i>
                <p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;Edit User&nbsp;</p>
                
            </div>
        </div>
        
    </div>
    <!-- /breadcrumb -->
    
        @if (Auth::guard('admin')->user()->role == 'Superadmin')
    
    <!-- row -->
    <div class="row row-sm">
        <div class="col-xl-4 col-lg-6 col-md-12">
            <div class="card crypto crypt-primary overflow-hidden">
                <div class="card-body iconfont text-left">
                    <div class="media">
                        <div class="coin-logo bg-primary-transparent">
                            <i class="fas fa-wallet text-primary"></i>
                        </div>
                        <div class="media-body">
                            <h5>Main Wallet</h5>
                        </div>
                        <div class="card-body-top">
                            <div>

                                    
                                <span><h5> #{{ round($capital - $expenses, 2)}} </h5> </span>
                                   
                                    
                                <span><h5>  </h5> </span>
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
                <div class="flot-wrapper">
                        <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-12">
            <div class="card crypto crypt-warning overflow-hidden">
                <div class="card-body iconfont text-left">
                    <div class="media">
                        <div class="coin-logo bg-warning-transparent">
                            <i class="fas fa-money-bill-alt text-warning"></i>
                        </div>
                        <div class="media-body">
                            <h5>Bonus Wallet</h5>
                        </div>
                        <div class="card-body-top">
                            <div>
                                <span><h5>#{{ $bonusamount - $withdrawbonus }}</h5> </span>
                                    <span><h5>  </h5> </span>    
    
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="flot-wrapper">
                        <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-12">
            <div class="card crypto crypt-warning overflow-hidden">
                <div class="card-body iconfont text-left">
                    <div class="media">
                        <div class="coin-logo bg-warning-transparent">
                            <i class="fas fa-money-bill-alt text-warning"></i>
                        </div>
                        <div class="media-body">
                            <h5>Total Income</h5>
                        </div>
                        <div class="card-body-top">
                            <div>
                                <span><h5>#{{ $bonusamount  }}</h5> </span>
                                    <span><h5>  </h5> </span>    
    
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="flot-wrapper">
                        <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-12">
            <div class="card crypto crypt-warning overflow-hidden">
                <div class="card-body iconfont text-left">
                    <div class="media">
                        <div class="coin-logo bg-warning-transparent">
                            <i class="fas fa-money-bill-alt text-warning"></i>
                        </div>
                        <div class="media-body">
                            <h5>Total Transferred</h5>
                        </div>
                        <div class="card-body-top">
                            <div>
                                <span><h5>#{{ $withdrawbonus }}</h5> </span>
                                    <span><h5>  </h5> </span>    
    
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="flot-wrapper">
                        <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-12">
            <div class="card crypto crypt-warning overflow-hidden">
                <div class="card-body iconfont text-left">
                    <div class="media">
                        <div class="coin-logo bg-warning-transparent">
                            <i class="fas fa-money-bill-alt text-warning"></i>
                        </div>
                        <div class="media-body">
                            <h5>Total Expenses</h5>
                        </div>
                        <div class="card-body-top">
                            <div>
                                <span><h5>#{{ $expenses }}</h5> </span>
                                    <span><h5>  </h5> </span>    
    
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="flot-wrapper">
                        <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-12">
            <div class="card crypto crypt-warning overflow-hidden">
                <div class="card-body iconfont text-left">
                    <div class="media">
                        <div class="coin-logo bg-warning-transparent">
                            <i class="fas fa-money-bill-alt text-warning"></i>
                        </div>
                        <div class="media-body">
                            <h5>Total Shared Amount</h5>
                        </div>
                        <div class="card-body-top">
                            <div>
                                <span><h5>#{{ $sharedamount  }}</h5> </span>
                                    <span><h5>  </h5> </span>    
    
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flot-wrapper">
                        <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-6 col-md-12">
            <div class="card crypto crypt-danger overflow-hidden">
                <div class="card-body iconfont text-left">
                    <div class="media">
                        <div class="coin-logo bg-danger-transparent">
                            <i class="fa fa-users text-danger"></i>
                        </div>
                        <div class="media-body">
                            <h5>Total Deposit</h5>
                        </div>
                        <div class="card-body-top">
                            <div>

                                <span><h5> #{{ 0 +  $capital }} </h5> </span>
                            </div>
                            
                        </div>
                        
                    </div>
                    <div class="flot-wrapper">
                        <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-6 col-md-12">
            <div class="card crypto crypt-warning overflow-hidden">
                <div class="card-body iconfont text-left">
                    <div class="media">
                        <div class="coin-logo bg-warning-transparent">
                            <i class="fas fa-money-bill-alt text-warning"></i>
                        </div>
                        <div class="media-body">
                            <h5>Total Withdraw</h5>
                        </div>
                        <div class="card-body-top">
                            <div>
                                <span><h5> #{{ $withdrawamount + 0 }}</h5> </span>
                                    <span><h5>  </h5> </span>    
    
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="flot-wrapper">
                        <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                    </div>
            </div>
        </div>
       
       
        <div class="col-xl-4 col-lg-6 col-md-12">
            <div class="card crypto crypt-danger overflow-hidden">
                <div class="card-body iconfont text-left">
                    <div class="media">
                        <div class="coin-logo bg-danger-transparent">
                            <i class="fa fa-users text-danger"></i>
                        </div>
                        <div class="media-body">
                            <h5>Points</h5>
                        </div>
                        <div class="card-body-top">
                            <div>

                                <span><h5> {{ $data->point }} </h5> </span>
                            </div>
                            
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
   

    <div class="col-lg-6 col-md-12">
        <div class="card">
            <div class="card-body">
                
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-xl-12 mx-auto d-block">
                        <div class="card card-body pd-10 pd-md-20 border shadow-none">
                            <h5 class="card-title mg-b-20">User Personal Information</h5>
                            <form action="{{ route('edituser') }}" method="post">
                            @csrf
                            
                            <div class="form-group">
                                <label class="main-content-label tx-11 tx-medium tx-gray-600">USER ID</label> <input class="form-control"name="id" type="text" id="id" value="{{ $data->userId }}" readonly>
                            </div>
                            <div class="form-group">
                                <label class="main-content-label tx-11 tx-medium tx-gray-600">First Name</label> <input class="form-control"name="firstname" type="text" id="firstname" value="{{ $data->firstName }}">
                            </div>
                            <div class="form-group">
                                <label class="main-content-label tx-11 tx-medium tx-gray-600">Last Name</label> <input class="form-control"name="lastname" type="text" id="lastname" value="{{ $data->lastName }}">
                            </div>
                            <div class="form-group">
                                <label class="main-content-label tx-11 tx-medium tx-gray-600">User Name</label> <input class="form-control"name="username" type="text" id="username" value="{{ $data->username }}">
                            </div>
                            <div class="form-group">
                                <label class="main-content-label tx-11 tx-medium tx-gray-600">Email Address</label> <input class="form-control"name="email" type="text" id="email"  value="{{ $data->email }}">
                            </div>
                            <div class="form-group">
                                <label class="main-content-label tx-11 tx-medium tx-gray-600">My Sponsor Id</label> <input class="form-control"name="mysponsorid" type="text" id="mysponsorid"  value="{{ $data->mySponsorId }}">
                            </div>
                            <div class="form-group">
                                <label class="main-content-label tx-11 tx-medium tx-gray-600">Sponsor</label> <input class="form-control"name="sponsor" type="text" id="sponsor"  value="{{ $data->uplineOne }}">
                            </div>
                            <div class="form-group">
                                <label class="main-content-label tx-11 tx-medium tx-gray-600">Country</label> <input class="form-control"name="country" type="text" id="country"  value="{{ $data->country }}">
                            </div>
                            <div class="form-group">
                                <label class="main-content-label tx-11 tx-medium tx-gray-600">Phone Number</label> <input class="form-control"name="phone" type="text" id="phone"  value="{{ $data->phoneNumber }}">
                            </div>
                              <div class="form-group">
                                <label class="main-content-label tx-11 tx-medium tx-gray-600">Account Number</label> <input class="form-control"name="accountNumber" type="text" id="accountNumber"  value="{{ $data->accountNumber }}">
                            </div>
                           
                            <button class="btn btn-main-primary btn-block" name="sub" type="submit">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
    @endif
     
    <div class="col-lg-6 col-md-12">
        <div class="card">
            <div class="card-body">
                
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-xl-12 mx-auto d-block">
                        <div class="card card-body pd-10 pd-md-20 border shadow-none">
                            <h5 class="card-title mg-b-20">Edit User Password</h5>
                            <form action="{{ route('edituserpassword') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="main-content-label tx-11 tx-medium tx-gray-600">USER ID</label> <input class="form-control"name="id" type="text" id="id" value="{{ $data->userId }}" readonly>
                            </div>
                            <div class="form-group">
                                <label class="main-content-label tx-11 tx-medium tx-gray-600">Password</label> <input class="form-control"name="password" type="text" id="firstname" value="xxxx">
                            </div>
                           
                            <button class="btn btn-main-primary btn-block" name="sub" type="submit">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
    
    
    <!-- row -->
    
    <!-- /row -->
    
    </div>
    <!-- Container closed -->
   
    </div>
    <!-- main-content closed -->
    
    <!-- Right-sidebar-->
    <div class="sidebar sidebar-right sidebar-animate">
    <div class="p-3">
        <a href="#" class="text-right float-right" data-toggle="sidebar-right" data-target=".sidebar-right"><i class="fe fe-x"></i></a>
    </div>
    <div class="tab-menu-heading border-0 card-header">
        <div class="card-title mb-0">Profile</div>
        <div class="card-options ml-auto">
            <a href="{{ route('adminprofile') }}" class="sidebar-remove"><i class="fe fe-x"></i></a>
        </div>
    </div>
    
    <div class="panel-body tabs-menu-body side-tab-body p-0 border-0 ">
        <div class="tab-content">
            <div class="tab-pane active" id="tab">
                <div class="card-body p-0">
                    
                    {{-- <a class="dropdown-item mt-4 border-top" href="editprofile.php">
                        <i class="dropdown-icon fe fe-edit mr-2"></i> Edit Profile
                    </a>
                   
                    <a class="dropdown-item  border-top" href="support.php">
                        <i class="dropdown-icon fe fe-help-circle mr-2"></i> Need Help?
                    </a>
                    <a class="dropdown-item  border-top" href="logout.php">
                        <i class="dropdown-icon fas fa-sign-out-alt mr-2"></i> Log Out
                    </a> --}}
                  
                </div>
            </div>
            
        </div>
    @include('admin.footer');