@include('admin.head')

@include('admin.header')
@include('account_managers.sidebar')

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
                <h3 class="content-title mb-2">Transactions: {{ $user->username }}</h3>
                <div class="d-flex">
                    <i class="mdi mdi-home text-muted hover-cursor"></i>
                    <p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;Users&nbsp;/&nbsp;Transactions&nbsp;</p>
                </div>
            </div>
        </div>
        <!-- /breadcrumb -->

        <div class="row">
            <div class="col-md-12">
                <div class="card overflow-hidden">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mg-b-0">Transaction History</h4>
                             <a href="{{ route('account-manager.users') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left mr-1"></i> Back to Users
                            </a>
                        </div>
                        <p class="tx-12 tx-gray-500 mb-2">Detailed list of transactions for {{ $user->firstName }} {{ $user->lastName }}.</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped text-nowrap mb-0 tx-13">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Service</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->transactionId }}</td>
                                        <td>{{ $transaction->transactionService }}</td>
                                        <td>{{ $transaction->transactionType }}</td>
                                        <td>#{{ number_format($transaction->amount, 2) }}</td>
                                        <td>{{ $transaction->paymentMethod }}</td>
                                        <td>
                                            @if($transaction->status == 'CONFIRM')
                                                <span class="badge badge-success">{{ $transaction->status }}</span>
                                            @else
                                                <span class="badge badge-warning">{{ $transaction->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d M, Y H:i') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No transactions found for this user.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $transactions->links() }}
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
