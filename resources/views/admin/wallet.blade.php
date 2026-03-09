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
                <p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;UserWallet&nbsp;</p>
                
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
                                <table class="table table-bordered table-hover table-striped text-nowrap mb-0 tx-13" >
                                    @foreach ($datawallets as  $datawallet)
                                    <thead>
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                            
                                        </tr>
                                    </thead>
                        
                                        
                        
                                    <tbody>
                                        <tr class="border-bottom">
                                            <td>{{ $datawallet->transaction_id}}</td>
                                            <td>{{ $datawallet->name}}</td>
                                            <td>{{ $datawallet->email }}</td>
                                            <td>{{ $datawallet->amount }}</td>
                                            <td class=""><span class="shadow-none badge outline-badge-primary"></span>{{ $datawallet->status }}</td>
                                            <td>{{ $datawallet->created_at }}</td>

                                            <td>
                                                <div class="btn-group">
                                                    @if($datawallet->status =='CONFIRM')
                                                           <button class='btn btn-success' data-toggle='modal' title='Approve Transaction' data-target='#myModalLOCK{{$datawallet->transaction_id}}'><i class='fa fa-unlock'></i></button>
                                                    @else
                                                        <button class='btn btn-danger' data-toggle='modal' title='Pending Transaction' data-target='#myModalUNLOCK{{ $datawallet->transaction_id }}'><i class='fa fa-lock'></i></button>
                                                    @endif
                                                    {{-- <a class="btn btn-info" data-toggle="" title="Add Interest" href="{{ Route('addinterest', ['id' => $datawallet->transaction_id]) }}"><i class="fa fa-edit"></i></a>  --}}
                                                    <button class="btn btn-danger" data-toggle="modal" title="Delete User" data-target='#myModalDELETED{{ $datawallet->transaction_id }}'><i class="fa fa-trash"></i></button>
                                                    {{-- <button class="btn btn-danger" data-toggle="modal" title="Delete User" data-target='#myModalDELETE{{ $datauser->userID }}'><i class="fa fa-trash"></i></button> --}}
                                                </div>
                                            </td>
                                        </tr>

                                            <div id="myModalLOCK{{$datawallet->transaction_id }}" class="modal fade" role="dialog">
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
                                                            <a type="submit" class="btn btn-danger" href="{{Route('adminwallet', ['unconfirmid' => $datawallet->transaction_id])}} ">Pending Transaction</a>
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div id="myModalDELETED{{$datawallet->transaction_id}}" class="modal fade" role="dialog">
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
                                                            <a type="button" class="btn btn-danger" href="{{ Route('adminwallet', ['deleteid' => $datawallet->transaction_id]) }}">Delete Transaction</a>
                                                            <button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                        <!-- Modal -->
                                            <div id="myModalUNLOCK{{ $datawallet->transaction_id }}" class="modal fade" role="dialog">
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
                                                        <a type="button" class="btn btn-danger" href="{{ Route('adminwallet', ['confirmid' =>  $datawallet->transaction_id]) }}">Confirm Transaction</a>
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
            {{-- <div class="tab-pane active" id="tab">
                <div class="card-body p-0">
                    
                    <a class="dropdown-item mt-4 border-top" href="editprofile.php">
                        <i class="dropdown-icon fe fe-edit mr-2"></i> Edit Profile
                    </a>
                   
                    <a class="dropdown-item  border-top" href="support.php">
                        <i class="dropdown-icon fe fe-help-circle mr-2"></i> Need Help?
                    </a>
                    <a class="dropdown-item  border-top" href="logout.php">
                        <i class="dropdown-icon fas fa-sign-out-alt mr-2"></i> Log Out
                    </a>
                  
                </div>
            </div> --}}
            
        </div>
    @include('admin.footer');