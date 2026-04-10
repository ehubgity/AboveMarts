@include('admin.head')

@include('admin.header')
@if(Auth::guard('account_manager')->check())
    @include('account_managers.sidebar')
@else
    @include('admin.sidebar')
@endif
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
            <div class="main-header-right">
                <div class="nav nav-item navbar-nav-right ml-auto">
                    <div class="nav-item full-screen fullscreen-button">
                        <a class="new nav-link full-screen-link" href="#"><i class="ti-fullscreen"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /main-header -->

    <!-- container -->
    <div class="container-fluid">

        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between">
            <div class="left-content">
                <h3 class="content-title mb-2">Assigned Users</h3>
                <div class="d-flex">
                    <i class="mdi mdi-home text-muted hover-cursor"></i>
                    <p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;Users&nbsp;</p>
                </div>
            </div>
        </div>
        <!-- /breadcrumb -->

        <div class="row">
            <div class="col-md-12">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped text-nowrap mb-0 tx-13">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Points</th>
                                        <th>Current Balance</th>
                                        <th>Package</th>
                                        <th>Status</th>
                                        <th>Date Joined</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $datauser)
                                    <tr>
                                        <td>{{ $datauser->firstName . ' ' . $datauser->lastName }}</td>
                                        <td>{{ $datauser->username }}</td>
                                        <td>{{ $datauser->email }}</td>
                                        <td>{{ $datauser->phoneNumber }}</td>
                                        <td>{{ $datauser->point }}</td>
                                        <td>#{{ number_format($datauser->currentBalance, 2) }}</td>
                                        <td>{{ $datauser->package }}</td>
                                        <td>
                                            @if($datauser->status == 'ACTIVE')
                                                <span class="badge badge-success">{{ $datauser->status }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ $datauser->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $datauser->created_at->format('d M, Y') }}</td>
                                        <td>
                                            <a class="btn btn-info btn-sm" title="View Transactions" href="{{ route('account-manager.user.transactions', ['id' => $datauser->userId]) }}">
                                                <i class="fa fa-history"></i> Transactions
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- container closed -->
</div>
<!-- main-content closed -->

@include('admin.footer')