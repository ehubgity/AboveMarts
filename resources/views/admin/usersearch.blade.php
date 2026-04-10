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
                    <a class="new nav-link full-screen-link" href="#"><i class="ti-fullscreen"></i></a>
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
                        <a class="new nav-link full-screen-link" href="#"><i class="ti-fullscreen"></i></a>
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
                <p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;Users&nbsp;</p>
                
                <form action="{{route('usersearch')}}" method="GET">
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
                            
                            @if (Auth::guard("admin")->user()->role == "Superadmin") 
                                <div class="mb-4 p-3 bg-light border rounded"> 
                                    <form action="{{ route("assign") }}" method="POST" id="bulkAssignForm"> 
                                        @csrf 
                                        <div class="row align-items-end"> 
                                            <div class="col-md-4"> 
                                                <label class="font-weight-bold">Assign Selected to Manager:</label> 
                                                <select name="manager_email" class="form-control" required> 
                                                    <option value="">-- Select Manager --</option> 
                                                    @foreach ($managers as $manager) 
                                                        <option value="{{ $manager->email }}">{{ $manager->name }} ({{ $manager->email }})</option> 
                                                    @endforeach 
                                                </select> 
                                            </div> 
                                            <div class="col-md-2"> 
                                                <button type="submit" class="btn btn-primary btn-block">Assign Selected</button> 
                                            </div> 
                                        </div> 
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <label class="font-weight-bold mr-2">Quick Select by Alphabet:</label>
                                                <div class="btn-group btn-group-sm flex-wrap">
                                                    @foreach (range('A', 'Z') as $letter)
                                                        <button type="button" class="btn btn-outline-secondary select-alphabet" data-letter="{{ $letter }}">{{ $letter }}</button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </form> 
                                </div> 
                            @endif
                                @if(isset($query))
                                    <h1>Search results for "{{ $query }}"</h1>
                                    
                                    <div class="table-responsive market-values">
                                        <table class="table table-bordered table-hover table-striped text-nowrap mb-0 tx-13" >
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" class="selectAll"></th>
                                                    <th>Name</th>
                                                    <th>Username</th>
                                                    <th>Email</th>
                                                    <th>Phone Number</th>
                                                    <th>Rank</th>
                                                    <th>Package</th>
                                                    <th>Account Number</th>
                                                    <th>Assigned Manager</th>
                                                    <th>Sponsor</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    @if (Auth::guard('admin')->user()->role == 'Superadmin')
                                                    <th>Action</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($datas as $datauser)
                                                <tr class="border-bottom">
                                                    <td><input type="checkbox" name="usernames[]" value="{{ $datauser->username }}" class="user-checkbox" form="bulkAssignForm" data-username="{{ $datauser->username }}"></td>
                                                    <td>{{ $datauser->firstName.' '.$datauser->lastName }}</td>
                                                    <td>{{ $datauser->username}}</td>
                                                    <td>{{ $datauser->email }}</td>
                                                    <td>{{ $datauser->phoneNumber }}</td>
                                                    <td>{{ $datauser->rank }}</td>
                                                    <td>{{ $datauser->package }}</td>
                                                    <td>{{ $datauser->accountNumber }}</td>
                                                    <td>{{ $datauser->accountManager->name ?? 'Not Assigned' }}</td>
                                                    <td>{{ $datauser->sponsor }}</td>                                                  
                                                    <td>{{ $datauser->created_at }}</td>
                                                    <td class=""><span class="shadow-none badge outline-badge-primary"></span>{{ $datauser->status }}</td>
                                                    @if (Auth::guard('admin')->user()->role == 'Superadmin')
                                                    <td>
                                                        <div class="btn-group">
                                                            @if($datauser->status =='ACTIVE')
                                                                   <button class='btn btn-success' data-toggle='modal' title='Lock User' data-target='#myModalLOCK{{$datauser->userId}}'><i class='fa fa-unlock'></i></button>
                                                            @else
                                                                <button class='btn btn-danger' data-toggle='modal' title='Unlock User' data-target='#myModalUNLOCK{{ $datauser->userId }}'><i class='fa fa-lock'></i></button>
                                                            @endif
                                                            
                                                            <button class="btn btn-primary btn-sm" data-toggle="modal" title="Assign Manager" data-target="#myModalASSIGN{{ $datauser->userId }}"><i class="fa fa-user-plus"></i></button>
                                                            
                                                            <a class="btn btn-info" data-toggle="" title="Edit User" href="{{ route("edituser", ['id' => $datauser->userId ]) }}"><i class="fa fa-edit"></i></a> 
                                                            <button class="btn btn-danger" data-toggle="modal" title="Delete User" data-target='#myModalDELETED{{ $datauser->userId }}'><i class="fa fa-trash"></i></button>
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>

                                                {{-- Modal templates inside loop if needed, but better outside. Keeping them matching users.blade --}}
                                                <div id="myModalASSIGN{{ $datauser->userId }}" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">Assign Manager to {{ $datauser->username }}</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div><form action="{{ route("reassign") }}" method="POST"> @csrf <input type="hidden" name="username" value="{{ $datauser->username }}"><div class="modal-body"><div class="form-group"><label>Select Account Manager</label><select name="manager_email" class="form-control" required><option value="">-- Select Manager --</option> @foreach ($managers as $manager) <option value="{{ $manager->email }}" {{ $datauser->manager == $manager->id ? "selected" : "" }}> {{ $manager->name }} ({{ $manager->email }}) </option> @endforeach </select></div></div><div class="modal-footer"><button type="submit" class="btn btn-primary">Assign Manager</button><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></form></div></div></div>
                                                <div id="myModalLOCK{{$datauser->userId}}" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">Lock User?</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body"><p>Are you sure you want to lock this user?</p></div><div class="modal-footer"><a type="submit" class="btn btn-danger" href="{{Route('adminusers', ['lockid' => $datauser->userId])}} ">Lock User</a><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>
                                                <div id="myModalDELETED{{$datauser->userId}}" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">Delete User?</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body"><p>Are you sure you want to delete User?</p></div><div class="modal-footer"><a type="button" class="btn btn-danger" href="{{ Route('adminusers', ['deleteid' => $datauser->userId]) }}">Delete User</a><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>
                                                <div id="myModalUNLOCK{{ $datauser->userId }}" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">Unlock User?</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body"><p>Are you sure you want to unlock this user?</p></div><div class="modal-footer"><a type="button" class="btn btn-danger" href="{{ Route('adminusers', ['unlockid' =>  $datauser->userId]) }}">Unlock User</a><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {{ $datausers->links() }}
                                    </div>
                                @else 
                                    <div class="table-responsive market-values">
                                        <table class="table table-bordered table-hover table-striped text-nowrap mb-0 tx-13" >
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" class="selectAll"></th>
                                                    <th>Name</th>
                                                    <th>Username</th>
                                                    <th>Email</th>
                                                    <th>Assigned Manager</th>
                                                    <th>Status</th>
                                                    @if (Auth::guard('admin')->user()->role == 'Superadmin')
                                                    <th>Action</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($datausers as $datauser)
                                                <tr class="border-bottom">
                                                    <td><input type="checkbox" name="usernames[]" value="{{ $datauser->username }}" class="user-checkbox" form="bulkAssignForm" data-username="{{ $datauser->username }}"></td>
                                                    <td>{{ $datauser->firstname.' '.$datauser->lastname }}</td>
                                                    <td>{{ $datauser->username}}</td>
                                                    <td>{{ $datauser->email }}</td>
                                                    <td>{{ $datauser->accountManager->name ?? 'Not Assigned' }}</td>
                                                    <td class=""><span class="shadow-none badge outline-badge-primary"></span>{{ $datauser->status }}</td>
                                                    @if (Auth::guard('admin')->user()->role == 'Superadmin')
                                                    <td>
                                                        <div class="btn-group">
                                                            @if($datauser->status =='ACTIVE')
                                                                   <button class='btn btn-success' data-toggle='modal' title='Lock User' data-target='#myModalLOCK{{$datauser->userId}}'><i class='fa fa-unlock'></i></button>
                                                            @else
                                                                <button class='btn btn-danger' data-toggle='modal' title='Unlock User' data-target='#myModalUNLOCK{{ $datauser->userId }}'><i class='fa fa-lock'></i></button>
                                                            @endif
                                                            
                                                            <button class="btn btn-primary btn-sm" data-toggle="modal" title="Assign Manager" data-target="#myModalASSIGN_loop2{{ $datauser->userId }}"><i class="fa fa-user-plus"></i></button>

                                                            <a class="btn btn-info" data-toggle="" title="Edit User" href="{{ route("edituser", ['id' => $datauser->userId ]) }}"><i class="fa fa-edit"></i></a> 
                                                            <button class="btn btn-danger" data-toggle="modal" title="Delete User" data-target='#myModalDELETED{{ $datauser->userId }}'><i class="fa fa-trash"></i></button>
                                                        </div>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <div id="myModalASSIGN_loop2{{ $datauser->userId }}" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">Assign Manager to {{ $datauser->username }}</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div><form action="{{ route("reassign") }}" method="POST"> @csrf <input type="hidden" name="username" value="{{ $datauser->username }}"><div class="modal-body"><div class="form-group"><label>Select Account Manager</label><select name="manager_email" class="form-control" required><option value="">-- Select Manager --</option> @foreach ($managers as $manager) <option value="{{ $manager->email }}" {{ $datauser->manager == $manager->id ? "selected" : "" }}> {{ $manager->name }} ({{ $manager->email }}) </option> @endforeach </select></div></div><div class="modal-footer"><button type="submit" class="btn btn-primary">Assign Manager</button><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></form></div></div></div>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {{ $datausers->links() }}
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