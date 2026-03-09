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
                <p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;Support&nbsp;</p>
                
            </div>
        </div>
        
    </div>
    <!-- /breadcrumb -->

    
    <div class="row row-sm">
        <div class="col-xl-6 col-lg-6 col-md-12">
                <div class="card crypto crypt-primary overflow-hidden">
                    <div class="card-body iconfont text-left">
                        <div class="media">
                            <div class="coin-logo bg-primary-transparent">
                                <i class="fas fa-envelope text-warning"></i>
                            </div>
                            <div class="media-body">
                                <h3>Email</h3>
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
                                                    <h5 class="card-title mg-b-20">Email</h5>
                                                    <form action="{{ route('adminsupport') }}" method="post">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label class="main-content-label tx-11 tx-medium tx-gray-600">Email</label> <input class="form-control"name="username" type="text" id="username">
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label">Message</label>
                                                        <textarea rows="5" class="form-control" placeholder="About Project"></textarea>
                                                      </div>
                                                    
                                                    <button class="btn btn-main-primary btn-block" name="sub" type="submit">Send</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
        </div>
        
        </div>
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
                                <table class="table table-bordered table-hover table-striped text-nowrap mb-0 tx-13" >
                                    @foreach ($datasupports as  $datasupport)
                                    <thead>
                                        <tr>
                                            <th>Ticket ID</th>
                                            <th>email</th>
                                            <th>title</th>
                                            <th>msg</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            
                                        </tr>
                                    </thead>
                        
                                        
                        
                                    <tbody>
                                        <tr class="border-bottom">
                                            <td>{{ $datasupport->ticketId}}</td>
                                            <td>{{ $datasupport->email}}</td>
                                            <td>{{ $datasupport->title }}</td>
                                            <td>{{ $datasupport->msg }}</td>
                                            <td class=""><span class="shadow-none badge outline-badge-primary"></span>{{ $datasupport->status }}</td>
                                            <td>{{ $datasupport->created_at }}</td>

                                            
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