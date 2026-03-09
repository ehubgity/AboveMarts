@include('admin.head');

@include('admin.header');
@include('admin.sidebar');
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
            {{-- <h3 class="content-title mb-2">Welcome back,</h3> --}}
            <div class="d-flex">
                <i class="mdi mdi-home text-muted hover-cursor"></i>
                <p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;Bonus&nbsp;</p>
                
            </div>
        </div>
        
    </div>
    <!-- /breadcrumb -->

    
    
            <div class="row" style="width:100%";>
                <div class="col-md-12">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="">
                                <div class="d-flex justify-content-between">
                                    <h4 class="card-title mg-b-10"></h4>
                                    <i class="mdi mdi-dots-horizontal text-gray"></i>
                                </div>
                            </div>

                                    
                            <div class="table-responsive market-values">
                                <h5>Referral History</h5>
                                <table class="table table-bordered table-hover table-striped text-nowrap mb-0 tx-13" >
                                    @foreach ($datarefs as  $dataref)
                                    <thead>
                                        <tr>
                                            <th>Referrer</th>
                                            <th>Referee</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="border-bottom">
                                            <td>{{ $dataref->referralname}}</td>
                                            <td>{{ $dataref->referee}}</td>
                                            <td>{{ $dataref->amount }}</td>
                                            
                                            <td>{{ $dataref->created_at }}</td>

                                            
                                        </tr>

                                    </tbody>
                                    @endforeach

                                </table>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

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
            <a href="#" class="sidebar-remove"><i class="fe fe-x"></i></a>
        </div>
    </div>
    
    <div class="panel-body tabs-menu-body side-tab-body p-0 border-0 ">
        <div class="tab-content">
            <div class="tab-pane active" id="tab">
                {{-- <div class="card-body p-0">
                    
                    <a class="dropdown-item mt-4 border-top" href="editprofile.php">
                        <i class="dropdown-icon fe fe-edit mr-2"></i> Edit Profile
                    </a>
                   
                    <a class="dropdown-item  border-top" href="support.php">
                        <i class="dropdown-icon fe fe-help-circle mr-2"></i> Need Help?
                    </a>
                    <a class="dropdown-item  border-top" href="logout.php">
                        <i class="dropdown-icon fas fa-sign-out-alt mr-2"></i> Log Out
                    </a>
                  
                </div> --}}
            </div>
            
        </div>
    @include('admin.footer');