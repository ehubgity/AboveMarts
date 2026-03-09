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
                {{-- <div class="col-md-12">
                <div class="left-content">
                    <div class="d-flex">
                        <i class="mdi mdi-home text-muted hover-cursor"></i>
                        <p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;Users&nbsp;</p>
                        
                    </div>
                    <form action="{{route('usersearch')}}" method="GET">
                <input type="text" name="query" placeholder="Search ..." class="p-2">
                <button class="btn btn-success" type="submit">Search</button>
                </form>
            </div>
        </div> --}}
        <div class="col-md-12 mt-4">
            <div class="row ">
                {{-- <div class="col-md-6">
                        <button class="btn btn-primary m-1" onclick="printTable()">Print Table</button>
                    </div> --}}
                {{-- <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <form action="{{ route('exportusers') }}" method="GET">
                <select name="package" class="form-control" class="select-box">
                    <option value="None">Select Option</option>
                    <option value="Basic">Basic</option>
                    <option value="Bronze">Bronze</option>
                    <option value="Silver">Silver</option>
                    <option value="Gold">Gold</option>
                    <option value="Platinum">Platinum</option>
                </select>
            </div>
            <div class="col-md-6">
                <button class="btn btn-warning m-1 w-100" type="submit">Export As CSV</button>
            </div>
            </form>
        </div>
    </div> --}}
</div>
</div>
</div>
</div>
<!-- /breadcrumb -->



<div class="row" style="width:100%" ;>
    <div class="col-md-12">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mg-b-10"></h4>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>
                </div>

                <h2> Users</h2>
                <div class="table-responsive market-values">
                    <table class="table table-bordered table-hover table-striped text-nowrap mb-0 tx-13">
                        <thead>
                            <tr <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Sponsor</th>
                                <th>Rank</th>
                                <th>Points</th>
                                <th>Before Balance</th>
                                <th>Current Balance</th>
                                <th>Package</th>
                                <th>Status</th>
                                <th>Date</th>

                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        @foreach ($users as $datauser)

                        <tbody>
                            <tr class="border-bottom">
                                <td>{{ $datauser->firstName . ' ' . $datauser->lastName }}</td>
                                <td>{{ $datauser->username }}</td>
                                <td>{{ $datauser->email }}</td>
                                <td>{{ $datauser->phoneNumber }}</td>
                                <td>{{ $datauser->rank }}</td>
                                <td>{{ $datauser->point }}</td>
                                <td>{{ $datauser->beforeBalance }}</td>
                                <td>{{ $datauser->currentBalance }}</td>
                                <td>{{ $datauser->package }}</td>
                                <td class=""><span
                                        class="shadow-none badge outline-badge-primary"></span>{{ $datauser->status }}
                                </td>
                                <td>{{ $datauser->created_at }}</td>
                                <!-- <td class=""><span class="shadow-none badge outline-badge-primary"></span>{{ $datauser->status }}</td> -->
                                <td>
                                    <div class="btn-group">
                                        <!-- @if($datauser->status =='Active')
                                        <button class='btn btn-success' data-toggle='modal' title='Lock User' data-target='#myModalLOCK{{$datauser->accountManagerId}}'><i class='fa fa-unlock'></i></button>
                                        @else
                                        <button class='btn btn-danger' data-toggle='modal' title='Unlock User' data-target='#myModalUNLOCK{{ $datauser->accountManagerId }}'><i class='fa fa-lock'></i></button>
                                        @endif -->
                                        <!-- <a class="btn btn-success" data-toggle="" title="View Users" href="{{ route("editaccountmanagers", ['id' => $datauser->accountManagerId ]) }}"><i class="fa fa-user"></i></a>
                                        <a class="btn btn-info" data-toggle="" title="Edit Manager" href="{{ route("editaccountmanagers", ['id' => $datauser->accountManagerId ]) }}"><i class="fa fa-edit"></i></a>
                                        <button class="btn btn-danger" data-toggle="modal" title="Delete Manager" data-target='#myModalDELETED{{ $datauser->id }}'><i class="fa fa-trash"></i></button> -->
                                        {{-- <button class="btn btn-danger" data-toggle="modal" title="Delete User" data-target='#myModalDELETE{{ $datauser->accountManagerId }}'><i class="fa fa-trash"></i></button> --}}
                                    </div>
                                </td>
                            </tr>

                            <div id="myModalLOCK{{$datauser->id}}" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">

                                            <h4 class="modal-title">Lock User?</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to lock this user?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <a type="submit" class="btn btn-danger" href="{{Route('alladmin', ['lockid' => $datauser->accountManagerId])}} ">Lock User</a>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div id="myModalDELETED{{$datauser->id}}" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Delete Manager?</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete Manager?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="modal-footer">
                                                <form action="{{ route('destroyaccount-managers', $datauser->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                                        Delete </button>
                                                </form>
                                                <!-- <a type="submit" class="btn btn-danger" href="{{Route('alladmin', ['lockid' => $datauser->accountManagerId])}} ">Lock User</a> -->
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div id="myModalUNLOCK{{ $datauser->accountManagerId }}" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">

                                            <h4 class="modal-title">Unlock User?</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to unlock this user?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <a type="button" class="btn btn-danger" href="{{ Route('alladmin', ['unlockid' =>  $datauser->accountManagerId]) }}">Unlock User</a>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>

                                </div>


                                <!-- Modal -->

                                <div id="myModalDELETE{{ $datauser->id }}" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">

                                                <h4 class="modal-title">Delete Manager?</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>


                                            <div class="modal-body">
                                                <p>Are you sure you want to delete manager?</p>
                                            </div>
                                            <!-- <div class="modal-footer">
                                                <a type="button" class="btn btn-danger" href="">Delete Transaction</a>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div> -->
                                            <div class="modal-footer">
                                                <form action="{{ route('destroyaccount-managers', $datauser->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                                        Delete </button>
                                                </form>
                                                <!-- <a type="submit" class="btn btn-danger" href="{{Route('alladmin', ['lockid' => $datauser->accountManagerId])}} ">Lock User</a> -->
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tbody>
                        @endforeach

                    </table>
                    {{ $users->links() }}
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
            <div class="tab-pane Active" id="tab">
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
            </div>

        </div>

        @include('admin.footer');