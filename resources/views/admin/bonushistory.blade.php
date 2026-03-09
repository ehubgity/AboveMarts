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
        <div class="row">
            <div class="col-md-12 mt-4">
                <div class="left-content">
                    {{-- <h3 class="content-title mb-2">Welcome back,</h3> --}}
                    <div class="d-flex">
                        <i class="mdi mdi-home text-muted hover-cursor"></i>
                        <p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;Commission History&nbsp;</p>
                        <form action="{{route('adminbonushistory')}}" method="GET">
                            <input type="text" name="query" placeholder="Search ..." class="p-2">
                            <button class ="btn btn-success" type="submit">Search</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-4">
                <div class="row ">
                    <div class="col-md-6">
                        <button class="btn btn-primary m-1" onclick="printTable()">Print Table</button>
                    </div>
                    @if (Auth::guard('admin')->user()->role == 'Superadmin' )

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <form action="{{ route('exportbonus') }}" method="GET">
                                    <select name="user" class="form-control" class="select-box">
                                        <option value="None">Select Option</option>
                                        <option value="Noadmin">Non Admin</option>
                                        <option value="Admin">Admin</option>
                                    </select>    
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-warning m-1 w-100" type="submit">Export As CSV</button>
    
                            </div>
                            </form> 
                        </div>
                        
                                
                            
                        

                       
                    </div>
                    @endif
 
                </div>
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

                            @if(isset($query))
                            <h1>Search results for "{{ $query }}"</h1>
                                <div class="table-responsive market-values">
                                    <table class="table table-bordered table-hover table-striped text-nowrap mb-0 tx-13" >
                                        @foreach ($datas as  $datadeposit)
                                        <thead>
                                            <tr>
                                                <th>Bonus ID</th>
                                                <th>Sponsor</th>
                                                <th>Downline</th>
                                                <th>Amount</th>
                                                <th>Package</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                @if (Auth::guard('admin')->user()->role == 'Superadmin')
                                                    <th>Action</th>
                                                @endif
                                            </tr>
                                        </thead>
                            
                                            
                            
                                        <tbody>
                                            <tr class="border-bottom">
                                                <td>{{ $datadeposit->bonusId}}</td>
                                                <td>{{ $datadeposit->sponsor }}</td>
                                            <td>{{ $datadeposit->username }}</td>
                                            <td>{{ $datadeposit->amount }}</td>
                                            <td>{{ $datadeposit->package }}</td>
                                           
                                                <td class=""><span class="shadow-none badge outline-badge-primary"></span>{{ $datadeposit->status }}</td>
                                                <td>{{ $datadeposit->created_at }}</td>
                                                @if (Auth::guard('admin')->user()->role == 'Superadmin')
                                                    <td>
                                                        <div class="btn-group">
                                                            {{-- @if($data->status =='CONFIRM')
                                                                <button class='btn btn-success' data-toggle='modal' title='Approve Transaction' data-target='#myModalLOCK{{$data->bonusId}}'><i class='fa fa-unlock'></i></button>
                                                            @else
                                                                <button class='btn btn-danger' data-toggle='modal' title='Pending Transaction' data-target='#myModalUNLOCK{{ $data->bonusId }}'><i class='fa fa-lock'></i></button>
                                                            @endif --}}
                                                            {{-- <a class="btn btn-info" data-toggle="" title="Add Interest" href="{{ Route('addinterest', ['id' => $data->bonusId]) }}"><i class="fa fa-edit"></i></a>  --}}
                                                            <button class="btn btn-danger" data-toggle="modal" title="Delete User" data-target='#myModalDELETED{{ $datadeposit->bonusId }}'><i class="fa fa-trash"></i></button>
                                                            {{-- <button class="btn btn-danger" data-toggle="modal" title="Delete User" data-target='#myModalDELETE{{ $datauser->userID }}'><i class="fa fa-trash"></i></button> --}}
                                                        </div>
                                                    </td>
                                                @endif
                                            </tr>
    
                                                <div id="myModalLOCK{{$datadeposit->bonusId }}" class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
    
                                                        <!-- Modal content-->
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                        
                                                                <h4 class="modal-title">Pending Transaction?</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Are you sure you want to make this transaction pending?</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <a type="submit" class="btn btn-danger" href="{{Route('adminbonushistory', ['unconfirmid' => $datadeposit->bonusId])}} ">Pending Transaction</a>
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
    
                                                    </div>
                                                </div>
                                                <div id="myModalDELETED{{$datadeposit->bonusId}}" class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
    
                                                        <!-- Modal content-->
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                        
                                                                <h4 class="modal-title">Delete Transaction?</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Are you sure you want to delete transaction?</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <a type="button" class="btn btn-danger" href="{{ Route('adminbonushistory', ['deleteid' => $datadeposit->bonusId]) }}">Delete Transaction</a>
                                                                <button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                            <!-- Modal -->
                                                <div id="myModalUNLOCK{{ $datadeposit->bonusId }}" class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
    
                                                        <!-- Modal content-->
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                    
                                                            <h4 class="modal-title">Confirm Transation?</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to confirm transaction?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a type="button" class="btn btn-danger" href="{{ Route('adminbonushistory', ['confirmid' =>  $datadeposit->bonusId]) }}">Confirm Transaction</a>
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
    
                                                </div>
                                                
                                                            
                                                            <!-- Modal -->
    
                                                <div id="myModalDELETE" class="modal fade" role="dialog">
                                                    <div class="modal-dialog">
    
                                                        <!-- Modal content-->
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                        
                                                                <h4 class="modal-title">Delete Transaction?</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
    
    
                                                                        <div class="modal-body">
                                                                            <p>Are you sure you want to delete transaction?</p>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <a type="button" class="btn btn-danger" href="">Delete Transaction</a>
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                        </div>
    
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                        </tbody>
                                        @endforeach
    
                                    </table>
                                    {{ $datausedcard->links() }}
                                </div>
                            @else
                            <div class="table-responsive market-values">
                                <table class="table table-bordered table-hover table-striped text-nowrap mb-0 tx-13" >
                                    @foreach ($datadeposits as  $datadeposit)
                                    <thead>
                                        <tr>
                                            <th>Bonus ID</th>
                                            <th>Sponsor</th>
                                            <th>Downline</th>
                                            <th>Amount</th>
                                            <th>Package</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            @if (Auth::guard('admin')->user()->role == 'Superadmin')
                                                <th>Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                        
                                        
                        
                                    <tbody>
                                        <tr class="border-bottom">
                                            <tr class="border-bottom">
                                            <td>{{ $datadeposit->bonusId}}</td>
                                            <td>{{ $datadeposit->sponsor }}</td>
                                            <td>{{ $datadeposit->username }}</td>
                                            <td>{{ $datadeposit->amount }}</td>
                                            <td>{{ $datadeposit->package }}</td>
                                            <td class=""><span class="shadow-none badge outline-badge-primary"></span>{{ $datadeposit->status }}</td>
                                            <td>{{ $datadeposit->created_at }}</td>
                                            @if (Auth::guard('admin')->user()->role == 'Superadmin')
                                                <td>
                                                    <div class="btn-group">
                                                        {{-- @if($datadeposit->status =='CONFIRM')
                                                            <button class='btn btn-success' data-toggle='modal' title='Approve Transaction' data-target='#myModalLOCK{{$datadeposit->bonusId}}'><i class='fa fa-unlock'></i></button>
                                                        @else
                                                            <button class='btn btn-danger' data-toggle='modal' title='Pending Transaction' data-target='#myModalUNLOCK{{ $datadeposit->bonusId }}'><i class='fa fa-lock'></i></button>
                                                        @endif --}}
                                                        {{-- <a class="btn btn-info" data-toggle="" title="Add Interest" href="{{ Route('addinterest', ['id' => $datadeposit->bonusId]) }}"><i class="fa fa-edit"></i></a>  --}}
                                                        <button class="btn btn-danger" data-toggle="modal" title="Delete User" data-target='#myModalDELETED{{ $datadeposit->bonusId }}'><i class="fa fa-trash"></i></button>
                                                        {{-- <button class="btn btn-danger" data-toggle="modal" title="Delete User" data-target='#myModalDELETE{{ $datauser->userID }}'><i class="fa fa-trash"></i></button> --}}
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>

                                            <div id="myModalLOCK{{$datadeposit->bonusId }}" class="modal fade" role="dialog">
                                                <div class="modal-dialog">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                    
                                                            <h4 class="modal-title">Pending Transaction?</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to make this transaction pending?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a type="submit" class="btn btn-danger" href="{{Route('adminbonushistory', ['unconfirmid' => $datadeposit->bonusId])}} ">Pending Transaction</a>
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div id="myModalDELETED{{$datadeposit->bonusId}}" class="modal fade" role="dialog">
                                                <div class="modal-dialog">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                    
                                                            <h4 class="modal-title">Delete Transaction?</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to delete transaction?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a type="button" class="btn btn-danger" href="{{ Route('adminbonushistory', ['deleteid' => $datadeposit->bonusId]) }}">Delete Transaction</a>
                                                            <button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                        <!-- Modal -->
                                            <div id="myModalUNLOCK{{ $datadeposit->bonusId }}" class="modal fade" role="dialog">
                                                <div class="modal-dialog">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                
                                                        <h4 class="modal-title">Confirm Transation?</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to confirm transaction?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a type="button" class="btn btn-danger" href="{{ Route('adminbonushistory', ['confirmid' =>  $datadeposit->bonusId]) }}">Confirm Transaction</a>
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>

                                            </div>
                                            
                                                        
                                                        <!-- Modal -->

                                            <div id="myModalDELETE" class="modal fade" role="dialog">
                                                <div class="modal-dialog">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                    
                                                            <h4 class="modal-title">Delete Transaction?</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>


                                                                    <div class="modal-body">
                                                                        <p>Are you sure you want to delete transaction?</p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <a type="button" class="btn btn-danger" href="">Delete Transaction</a>
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    </div>

                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                    </tbody>
                                    @endforeach

                                </table>
                                {{ $datadeposits->links() }}
                            </div>
                            @endif
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