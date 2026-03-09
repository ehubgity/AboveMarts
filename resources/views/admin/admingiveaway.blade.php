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
            {{-- <h3 class="content-title mb-2">Welcome back,</h3> --}}
            <div class="d-flex">
                <i class="mdi mdi-home text-muted hover-cursor"></i>
                <p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;Giveaway&nbsp;</p>
                <form action="{{route('admingiveawaysearch')}}" method="GET">
                    <input type="text" name="query" placeholder="Search ..." class="p-2">
                    <button class ="btn btn-success" type="submit">Search</button>
                </form>
            </div>
        </div>
        <button class="btn btn-primary m-2" onclick="printTable()">Print Table</button>
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
                                    @foreach ($giveawayusers as  $data)
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User ID</th>
                                            <th>Giveaway ID</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Win</th>
                                            <th>Lucky number</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                            
                                        </tr>
                                    </thead>
                        
                                        
                        
                                    <tbody>
                                        <tr class="border-bottom">
                                            <td>{{ $data->id}}</td>
                                            <td>{{ $data->user_id}}</td>
                                            <td>{{ $data->giveaway_id }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->phone }}</td>
                                            <td>{{ $data->is_win }}</td>
                                            <td>{{ $data->lucky_number }}</td>
                                            <td>{{ $data->created_at }}</td>
                                         
                                                <td>
                                                    <div class="btn-group">
                                                      
                                                        <!--<a class="btn btn-info" data-toggle="" title="Add Interest" href="{{ Route('addinterest', ['id' => $data->id]) }}"><i class="fa fa-edit"></i></a> -->
                                                                                                                  @if (Auth::guard('admin')->user()->role == 'Superadmin')

                                                        <button class="btn btn-danger" data-toggle="modal" title="Delete User" data-target='#myModalDELETED{{ $data->id }}'><i class="fa fa-trash"></i></button>
                                                                                                    @endif

                                                    </div>
                                                </td>
                                        </tr>

                                            <div id="myModalLOCK{{$data->id }}" class="modal fade" role="dialog">
                                                <div class="modal-dialog">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                    
                                                            <h4 class="modal-title">Pending Giveaway?</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to make this Giveaway pending?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a type="submit" class="btn btn-danger" href="{{Route('admingiveaway', ['unconfirmid' => $data->id])}} ">Pending Giveaway</a>
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div id="myModalDELETED{{$data->id}}" class="modal fade" role="dialog">
                                                <div class="modal-dialog">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                    
                                                            <h4 class="modal-title">Delete Giveaway?</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to delete Giveaway?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a type="button" class="btn btn-danger" href="{{ Route('admingiveaway', ['deleteid' => $data->id]) }}">Delete Giveaway</a>
                                                            <button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                        <!-- Modal -->
                                            <div id="myModalUNLOCK{{ $data->id }}" class="modal fade" role="dialog">
                                                <div class="modal-dialog">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                
                                                        <h4 class="modal-title">Confirm Transation?</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to confirm Giveaway?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a type="button" class="btn btn-danger" href="{{ Route('admingiveaway', ['confirmid' =>  $data->id]) }}">Confirm Giveaway</a>
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
                                                    
                                                            <h4 class="modal-title">Delete Giveaway?</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>

                                                                    <div class="modal-body">
                                                                        <p>Are you sure you want to delete giveaway?</p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <a type="button" class="btn btn-danger" href="">Delete Giveaway</a>
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    </div>

                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                    </tbody>
                                    @endforeach

                                </table>
                                {{ $giveawayusers->links() }}
                            </div>
                            @else
                            <div class="table-responsive market-values">
                                <table class="table table-bordered table-hover table-striped text-nowrap mb-0 tx-13" >
                                    @foreach ($giveawayusers as  $data)
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User ID</th>
                                            <th>Giveaway ID</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Win</th>
                                            <th>Lucky number</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                            
                                        </tr>
                                    </thead>
                        
                                        
                        
                                    <tbody>
                                        <tr class="border-bottom">
                                            <td>{{ $data->id}}</td>
                                            <td>{{ $data->user_id}}</td>
                                            <td>{{ $data->giveaway_id }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->phone }}</td>
                                            <td>{{ $data->is_win }}</td>
                                            <td>{{ $data->lucky_number }}</td>
                                            <td>{{ $data->created_at }}</td>
                                         
                                                <td>
                                                    <div class="btn-group">
                                                       
                                                        <!--<a class="btn btn-info" data-toggle="" title="Add Interest" href="{{ Route('addinterest', ['id' => $data->id]) }}"><i class="fa fa-edit"></i></a> -->
                                                                                                                  @if (Auth::guard('admin')->user()->role == 'Superadmin')

                                                        <button class="btn btn-danger" data-toggle="modal" title="Delete User" data-target='#myModalDELETED{{ $data->id }}'><i class="fa fa-trash"></i></button>
                                                                                                    @endif

                                                    </div>
                                                </td>
                                        </tr>

                                            <div id="myModalLOCK{{$data->id }}" class="modal fade" role="dialog">
                                                <div class="modal-dialog">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                    
                                                            <h4 class="modal-title">Pending Giveaway?</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to make this Giveaway pending?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a type="submit" class="btn btn-danger" href="{{Route('admingiveaway', ['unconfirmid' => $data->id])}} ">Pending Giveaway</a>
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div id="myModalDELETED{{$data->id}}" class="modal fade" role="dialog">
                                                <div class="modal-dialog">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                    
                                                            <h4 class="modal-title">Delete Giveaway?</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to delete Giveaway?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a type="button" class="btn btn-danger" href="{{ Route('admingiveaway', ['deleteid' => $data->id]) }}">Delete Giveaway</a>
                                                            <button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                        <!-- Modal -->
                                            <div id="myModalUNLOCK{{ $data->id }}" class="modal fade" role="dialog">
                                                <div class="modal-dialog">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                
                                                        <h4 class="modal-title">Confirm Transation?</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to confirm Giveaway?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a type="button" class="btn btn-danger" href="{{ Route('admingiveaway', ['confirmid' =>  $data->id]) }}">Confirm Giveaway</a>
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
                                                    
                                                            <h4 class="modal-title">Delete giveaway?</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>

                                                                    <div class="modal-body">
                                                                        <p>Are you sure you want to delete giveaway?</p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <a type="button" class="btn btn-danger" href="">Delete giveaway</a>
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    </div>

                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                    </tbody>
                                    @endforeach

                                </table>
                                {{ $giveawayusers->links() }}
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