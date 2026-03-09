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


                    <button class="navbar-toggler navresponsive-toggler d-sm-none" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4"
                        aria-expanded="false" aria-label="Toggle navigation">
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
        <div
            class="mb-1 navbar navbar-expand-lg  nav nav-item  navbar-nav-right responsive-navbar navbar-dark d-sm-none ">
            <div class="navbar-collapse">
                <div class="d-flex order-lg-2 ml-auto">

                    <div class="d-md-flex">
                        <div class="nav-item full-screen fullscreen-button">
                            <a class="new nav-link full-screen-link" href="#"><i
                                    class="ti-fullscreen"></i></span></a>
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
                <h3 class="content-title mb-2">Welcome back,</h3>
                <div class="d-flex">
                    <i class="mdi mdi-home text-muted hover-cursor"></i>
                    <p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;Dashboard&nbsp;</p>

                </div>
            </div>

        </div>
        <!-- /breadcrumb -->

        @if (Auth::guard('admin')->user()->role == 'Superadmin' ||
        Auth::guard('admin')->user()->role == 'Admin' ||
        Auth::guard('admin')->user()->role == 'Investor' ||
        Auth::guard('admin')->user()->role == 'Other')
        <!-- row -->
        <div class="row row-sm">
            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="card crypto crypt-primary overflow-hidden">
                    <div class="card-body iconfont text-left">
                        <div class="media">
                            <div class="coin-logo bg-primary-transparent">
                                <i class="fa fa-users text-primary"></i>
                            </div>
                            <div class="media-body">
                                <h5>Members</h5>
                            </div>
                            <div class="card-body-top">
                                <div>


                                    <span>
                                        <h5> {{ 0 + $datauser }} </h5>
                                    </span>


                                    <span>
                                        <h5> </h5>
                                    </span>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="flot-wrapper">
                        <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="card crypto crypt-danger overflow-hidden">
                    <div class="card-body iconfont text-left">
                        <div class="media">
                            <div class="coin-logo bg-danger-transparent">
                                <i class="fa fa-cart-plus text-danger"></i>
                            </div>
                            <div class="media-body">
                                <h5>No of Deposit</h5>
                            </div>
                            <div class="card-body-top">
                                <div>

                                    <span>
                                        <h5> {{ 0 + $dataamount }} </h5>
                                    </span>
                                </div>

                            </div>

                        </div>
                        <div class="flot-wrapper">
                            <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="card crypto crypt-danger overflow-hidden">
                    <div class="card-body iconfont text-left">
                        <div class="media">
                            <div class="coin-logo bg-danger-transparent">
                                <i class="fa fa-cart-plus text-danger"></i>
                            </div>
                            <div class="media-body">
                                <h5>No of Withdraw</h5>
                            </div>
                            <div class="card-body-top">
                                <div>

                                    <span>
                                        <h5> {{ 0 + $datawith }} </h5>
                                    </span>
                                </div>

                            </div>

                        </div>
                        <div class="flot-wrapper">
                            <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="card crypto crypt-danger overflow-hidden">
                    <div class="card-body iconfont text-left">
                        <div class="media">
                            <div class="coin-logo bg-danger-transparent">
                                <i class="fa fa-cart-plus text-danger"></i>
                            </div>
                            <div class="media-body">
                                <h5>No of Bonus</h5>
                            </div>
                            <div class="card-body-top">
                                <div>

                                    <span>
                                        <h5> {{ 0 + $databonusno }} </h5>
                                    </span>
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
        @endif
        @if (Auth::guard('admin')->user()->role == 'Superadmin' || Auth::guard('admin')->user()->role == 'Investor')

        <div class="row row-sm">
            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="card crypto crypt-warning overflow-hidden">
                    <div class="card-body iconfont text-left">
                        <div class="media">
                            <div class="coin-logo bg-warning-transparent">
                                <i class="fa fa-thumbs-up text-warning"></i>
                            </div>
                            <div class="media-body">
                                <h5>Easy Balance</h5>
                            </div>
                            <div class="card-body-top">
                                <div>
                                    <span>
                                        <h5> #{{ round($easyaccessBalance + 0, 2) }}</h5>
                                    </span>
                                    <span>
                                        <h5> </h5>
                                    </span>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="flot-wrapper">
                        <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="card crypto crypt-warning overflow-hidden">
                    <div class="card-body iconfont text-left">
                        <div class="media">
                            <div class="coin-logo bg-warning-transparent">
                                <i class="fa fa-thumbs-up text-warning"></i>
                            </div>
                            <div class="media-body">
                                <h5>Ebills Balance</h5>
                            </div>
                            <div class="card-body-top">
                                <div>
                                    <span>
                                        <h5> #{{ round($ebillsbalance + 0, 2) }}</h5>
                                    </span>
                                    <span>
                                        <h5> </h5>
                                    </span>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="flot-wrapper">
                        <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="card crypto crypt-warning overflow-hidden">
                    <div class="card-body iconfont text-left">
                        <div class="media">
                            <div class="coin-logo bg-warning-transparent">
                                <i class="fa fa-thumbs-up text-warning"></i>
                            </div>
                            <div class="media-body">
                                <h5>Total Admin Earning</h5>
                            </div>
                            <div class="card-body-top">
                                <div>
                                    <span>
                                        <h5> #{{ round($totalBonusEarning + 0, 2) }}</h5>
                                    </span>
                                    <span>
                                        <h5> </h5>
                                    </span>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="flot-wrapper">
                        <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="card crypto crypt-warning overflow-hidden">
                    <div class="card-body iconfont text-left">
                        <div class="media">
                            <div class="coin-logo bg-warning-transparent">
                                <i class="fa fa-thumbs-up text-warning"></i>
                            </div>
                            <div class="media-body">
                                <h5>Admin Earning</h5>
                            </div>
                            <div class="card-body-top">
                                <div>
                                    <span>
                                        <h5> #{{ round($totalBonusEarning - $equityEarning + 0, 2) }}</h5>
                                    </span>
                                    <span>
                                        <h5> </h5>
                                    </span>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="flot-wrapper">
                        <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="card crypto crypt-warning overflow-hidden">
                    <div class="card-body iconfont text-left">
                        <div class="media">
                            <div class="coin-logo bg-warning-transparent">
                                <i class="fa fa-thumbs-up text-warning"></i>
                            </div>
                            <div class="media-body">
                                <h5>Clients Avaliable Income</h5>
                            </div>
                            <div class="card-body-top">
                                <div>
                                    <span>
                                        <h5> #{{ round($bonusEarning + 0 - $bonusWithdraw, 2) }}</h5>
                                    </span>
                                    <span>
                                        <h5> </h5>
                                    </span>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="flot-wrapper">
                        <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="card crypto crypt-warning overflow-hidden">
                    <div class="card-body iconfont text-left">
                        <div class="media">
                            <div class="coin-logo bg-warning-transparent">
                                <i class="fa fa-thumbs-up text-warning"></i>
                            </div>
                            <div class="media-body">
                                <h5>Total Income Transferred</h5>
                            </div>
                            <div class="card-body-top">
                                <div>
                                    <span>
                                        <h5> #{{ round($bonusWithdraw + 0) }}</h5>
                                    </span>
                                    <span>
                                        <h5> </h5>
                                    </span>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="flot-wrapper">
                        <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="card crypto crypt-warning overflow-hidden">
                    <div class="card-body iconfont text-left">
                        <div class="media">
                            <div class="coin-logo bg-warning-transparent">
                                <i class="fa fa-thumbs-up text-warning"></i>
                            </div>
                            <div class="media-body">
                                <h5>Total Income Shared</h5>
                            </div>
                            <div class="card-body-top">
                                <div>
                                    <span>
                                        <h5> #{{ round($sharedamount + 0) }}</h5>
                                    </span>
                                    <span>
                                        <h5> </h5>
                                    </span>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="flot-wrapper">
                        <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="card crypto crypt-warning overflow-hidden">
                    <div class="card-body iconfont text-left">
                        <div class="media">
                            <div class="coin-logo bg-warning-transparent">
                                <i class="fa fa-thumbs-up text-warning"></i>
                            </div>
                            <div class="media-body">
                                <h5>Total Deposit Received</h5>
                            </div>
                            <div class="card-body-top">
                                <div>
                                    <span>
                                        <h5> #{{ round($datadeposit + 0, 2) }}</h5>
                                    </span>
                                    <span>
                                        <h5> </h5>
                                    </span>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="flot-wrapper">
                        <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="card crypto crypt-warning overflow-hidden">
                    <div class="card-body iconfont text-left">
                        <div class="media">
                            <div class="coin-logo bg-warning-transparent">
                                <i class="fa fa-thumbs-up text-warning"></i>
                            </div>
                            <div class="media-body">
                                <h5>Expected Withdrawable Amount </h5>
                            </div>
                            <div class="card-body-top">
                                <div>
                                    <span>
                                        <h5> #{{ round($bonusEarning + 0 - $bonusWithdraw + $datadeposit - $datawithdraw - $expenses + 0, 2) }}
                                        </h5>
                                    </span>
                                    <span>
                                        <h5> </h5>
                                    </span>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="flot-wrapper">
                        <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="card crypto crypt-warning overflow-hidden">
                    <div class="card-body iconfont text-left">
                        <div class="media">
                            <div class="coin-logo bg-warning-transparent">
                                <i class="fa fa-thumbs-up text-warning"></i>
                            </div>
                            <div class="media-body">
                                <h5>Amount Withdrawable</h5>
                            </div>
                            <div class="card-body-top">
                                <div>
                                    <span>
                                        <h5> #{{ round($datadeposit - $datawithdraw - $expenses + 0, 2) }}</h5>
                                    </span>
                                    <span>
                                        <h5> </h5>
                                    </span>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="flot-wrapper">
                        <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="card crypto crypt-warning overflow-hidden">
                    <div class="card-body iconfont text-left">
                        <div class="media">
                            <div class="coin-logo bg-warning-transparent">
                                <i class="fa fa-thumbs-up text-warning"></i>
                            </div>
                            <div class="media-body">
                                <h5>Total Withdraw</h5>
                            </div>
                            <div class="card-body-top">
                                <div>
                                    <span>
                                        <h5> #{{ $datawithdraw + 0 }}</h5>
                                    </span>
                                    <span>
                                        <h5> </h5>
                                    </span>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="flot-wrapper">
                        <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="card crypto crypt-warning overflow-hidden">
                    <div class="card-body iconfont text-left">
                        <div class="media">
                            <div class="coin-logo bg-warning-transparent">
                                <i class="fa fa-thumbs-up text-warning"></i>
                            </div>
                            <div class="media-body">
                                <h5>Total Commission Excluding Admin</h5>
                            </div>
                            <div class="card-body-top">
                                <div>
                                    <span>
                                        <h5> #{{ $bonusEarning + 0 }}</h5>
                                    </span>
                                    <span>
                                        <h5> </h5>
                                    </span>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="flot-wrapper">
                        <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-xl-3 col-lg-6 col-md-12">
                    <div class="card crypto crypt-warning overflow-hidden">
                        <div class="card-body iconfont text-left">
                            <div class="media">
                                <div class="coin-logo bg-warning-transparent">
                                    <i class="fa fa-thumbs-up text-warning"></i>
                                </div>
                                <div class="media-body">
                                    <h5>Total Commission</h5>
                                </div>
                                <div class="card-body-top">
                                    <div>
                                        <span>
                                            <h5> #{{ $databonus + 0 }}</h5>
            </span>
            <span>
                <h5> </h5>
            </span>

        </div>

    </div>
</div>
</div>
<div class="flot-wrapper">
    <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
</div>
</div>
</div> --}}
<div class="col-xl-3 col-lg-6 col-md-12">
    <div class="card crypto crypt-primary overflow-hidden">
        <div class="card-body iconfont text-left">
            <div class="media">
                <div class="coin-logo bg-primary-transparent">
                    <i class="fas fa-hand-point-right  text-primary"></i>
                </div>
                <div class="media-body">
                    <h5>Total Reward Point</h5>
                </div>
                <div class="card-body-top">
                    <div>

                        <span>
                            <h5> {{ $totalPoints + 0 }} </h5>
                        </span>

                        <span>
                            <h5> </h5>
                        </span>
                    </div>

                </div>
            </div>

        </div>
        <div class="flot-wrapper">
            <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
        </div>
    </div>
</div>
<div class="col-xl-3 col-lg-6 col-md-12">
    <div class="card crypto crypt-primary overflow-hidden">
        <div class="card-body iconfont text-left">
            <div class="media">
                <div class="coin-logo bg-primary-transparent">
                    <i class="fas fa-hand-point-right  text-primary"></i>
                </div>
                <div class="media-body">
                    <h5>Total Reward Value</h5>
                </div>
                <div class="card-body-top">
                    <div>

                        <span>
                            <h5> {{ $points + 0 }} </h5>
                        </span>

                        <span>
                            <h5> </h5>
                        </span>
                    </div>

                </div>
            </div>

        </div>
        <div class="flot-wrapper">
            <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
        </div>
    </div>
</div>
<div class="col-xl-3 col-lg-6 col-md-12">
    <div class="card crypto crypt-primary overflow-hidden">
        <div class="card-body iconfont text-left">
            <div class="media">
                <div class="coin-logo bg-primary-transparent">
                    <i class="fa fa-bars text-primary"></i>
                </div>
                <div class="media-body">
                    <h5>Total Sales Excluding Packages</h5>
                </div>
                <div class="card-body-top">
                    <div>

                        <span>
                            <h5> #{{ $dataSales + 0 }} </h5>
                        </span>

                        <span>
                            <h5> </h5>
                        </span>
                    </div>

                </div>
            </div>

        </div>
        <div class="flot-wrapper">
            <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
        </div>
    </div>
</div>
<div class="col-xl-3 col-lg-6 col-md-12">
    <div class="card crypto crypt-primary overflow-hidden">
        <div class="card-body iconfont text-left">
            <div class="media">
                <div class="coin-logo bg-primary-transparent">
                    <i class="fa fa-bars text-primary"></i>
                </div>
                <div class="media-body">
                    <h5>Total Packages Sales</h5>
                </div>
                <div class="card-body-top">
                    <div>

                        <span>
                            <h5> #{{ $totalPackages + 0 }} </h5>
                        </span>

                        <span>
                            <h5> </h5>
                        </span>
                    </div>

                </div>
            </div>

        </div>
        <div class="flot-wrapper">
            <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
        </div>
    </div>
</div>
<div class="col-xl-3 col-lg-6 col-md-12">
    <div class="card crypto crypt-primary overflow-hidden">
        <div class="card-body iconfont text-left">
            <div class="media">
                <div class="coin-logo bg-primary-transparent">
                    <i class="fa fa-bars text-primary"></i>
                </div>
                <div class="media-body">
                    <h5>Total Expenditures From Clients</h5>
                </div>
                <div class="card-body-top">
                    <div>

                        <span>
                            <h5> #{{ $expenses + 0 }} </h5>
                        </span>

                        <span>
                            <h5> </h5>
                        </span>
                    </div>

                </div>
            </div>

        </div>
        <div class="flot-wrapper">
            <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-lg-6 col-md-12">
    <div class="card crypto crypt-primary overflow-hidden">
        <div class="card-body iconfont text-left">
            <div class="media">
                <div class="coin-logo bg-primary-transparent">
                    <i class="fa fa-bars text-primary"></i>
                </div>
                <div class="media-body">
                    <h5>Total Pending Withdraw</h5>
                </div>
                <div class="card-body-top">
                    <div>

                        <span>
                            <h5> #{{ $datawithdrawPending + 0 }} </h5>
                        </span>

                        <span>
                            <h5> </h5>
                        </span>
                    </div>

                </div>
            </div>

        </div>
        <div class="flot-wrapper">
            <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
        </div>
    </div>
</div>
<div class="col-xl-3 col-lg-6 col-md-12">
    <div class="card crypto crypt-primary overflow-hidden">
        <div class="card-body iconfont text-left">
            <div class="media">
                <div class="coin-logo bg-primary-transparent">
                    <i class="fa fa-bars text-primary"></i>
                </div>
                <div class="media-body">
                    <h5>Total Pending Deposit</h5>
                </div>
                <div class="card-body-top">
                    <div>

                        <span>
                            <h5> #{{ $datadepositPending + 0 }} </h5>
                        </span>

                        <span>
                            <h5> </h5>
                        </span>
                    </div>

                </div>
            </div>

        </div>
        <div class="flot-wrapper">
            <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
        </div>
    </div>
</div>
<div class="col-xl-3 col-lg-6 col-md-12">
    <div class="card crypto crypt-primary overflow-hidden">
        <div class="card-body iconfont text-left">
            <div class="media">
                <div class="coin-logo bg-primary-transparent">
                    <i class="fa fa-bars text-primary"></i>
                </div>
                <div class="media-body">
                    <h5>Total Pending Transaction</h5>
                </div>
                <div class="card-body-top">
                    <div>

                        <span>
                            <h5> #{{ $datatransactionPending + 0 }} </h5>
                        </span>

                        <span>
                            <h5> </h5>
                        </span>
                    </div>

                </div>
            </div>

        </div>
        <div class="flot-wrapper">
            <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
        </div>
    </div>
</div>
<div class="col-xl-3 col-lg-6 col-md-12">
    <div class="card crypto crypt-primary overflow-hidden">
        <div class="card-body iconfont text-left">
            <div class="media">
                <div class="coin-logo bg-primary-transparent">
                    <i class="fa fa-bars text-primary"></i>
                </div>
                <div class="media-body">
                    <h5>Total Pending Preorder</h5>
                </div>
                <div class="card-body-top">
                    <div>

                        <span>
                            <h5> {{ $dataPreorderPending + 0 }} </h5>
                        </span>

                        <span>
                            <h5> </h5>
                        </span>
                    </div>

                </div>
            </div>

        </div>
        <div class="flot-wrapper">
            <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
        </div>
    </div>
</div>
@endif
@if (Auth::guard('admin')->user()->role == 'Superadmin' || Auth::guard('admin')->user()->role == 'Investor')
<div class="col-xl-3 col-lg-6 col-md-12">
    <div class="card crypto crypt-primary overflow-hidden">
        <div class="card-body iconfont text-left">
            <div class="media">
                <div class="coin-logo bg-primary-transparent">
                    <i class="fa fa-bars text-primary"></i>
                </div>
                <div class="media-body">
                    <h5>Total Amount Of Recharge Card Printing </h5>
                </div>
                <div class="card-body-top">
                    <div>

                        <span>
                            <h5> #{{ $dataRP + 0 }} </h5>
                        </span>

                        <span>
                            <h5> </h5>
                        </span>
                    </div>

                </div>
            </div>

        </div>
        <div class="flot-wrapper">
            <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-lg-6 col-md-12">
    <div class="card crypto crypt-success overflow-hidden">
        <div class="card-body iconfont text-left">
            <div class="media">
                <div class="coin-logo bg-success-transparent">
                    <i class="fas fa-wallet text-success"></i>
                </div>
                <div class="media-body">
                    <h5>Total Amount Of Recharge Card Purchase</h5>
                </div>
                <div class="card-body-top">
                    <div>

                        <span>
                            <h5> #{{ $dataRC + 0 }} </h5>
                        </span>

                        <span>
                            <h5> </h5>
                        </span>
                    </div>

                </div>

            </div>
            <div class="flot-wrapper">
                <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
            </div>

        </div>
    </div>
</div>
{{-- <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="card crypto crypt-success overflow-hidden">
                    <div class="card-body iconfont text-left">
                        <div class="media">
                            <div class="coin-logo bg-success-transparent">
                                <i class="fas fa-wallet text-success"></i>
                            </div>
                            <div class="media-body">
                                <h5>Total Amount Of Data Purchase</h5>
                            </div>
                            <div class="card-body-top">
                                <div>

                                    <span><h5> #{{ $dataDP + 0 }} </h5> </span>

<span>
    <h5> </h5>
</span>
</div>

</div>

</div>
<div class="flot-wrapper">
    <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
</div>

</div>
</div>
</div> --}}
<div class="col-xl-3 col-lg-6 col-md-12">
    <div class="card crypto crypt-danger overflow-hidden">
        <div class="card-body iconfont text-left">
            <div class="media">
                <div class="coin-logo bg-danger-transparent">
                    <i class="fas fa-coins text-danger"></i>
                </div>
                <div class="media-body">
                    <h5>Total Amount Of Data Share</h5>
                </div>
                <div class="card-body-top">
                    <div>

                        <span>
                            <h5> #{{ $dataDS + 0 }} </h5>
                        </span>

                        <span>
                            <h5> </h5>
                        </span>

                    </div>

                </div>
            </div>

        </div>
        <div class="flot-wrapper">
            <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
        </div>

    </div>
</div>
<div class="col-xl-3 col-lg-6 col-md-12">
    <div class="card crypto crypt-warning overflow-hidden">
        <div class="card-body iconfont text-left">
            <div class="media">
                <div class="coin-logo bg-warning-transparent">
                    <i class="fas fa-coins text-warning"></i>
                </div>
                <div class="media-body">
                    <h5>Amount Of Electricity Purchase </h5>
                </div>
                <div class="card-body-top">
                    <div>



                        <span>
                            <h5> #{{ $dataEL }} </h5>
                        </span>
                        <span>
                            <h5> </h5>
                        </span>

                    </div>

                </div>
            </div>
        </div>
        <div class="flot-wrapper">
            <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-lg-6 col-md-12">
    <div class="card crypto crypt-warning overflow-hidden">

        <div class="card-body iconfont text-left">
            <div class="media">
                <div class="coin-logo bg-warning-transparent">
                    <i class="fas fa-coins text-warning"></i>
                </div>
                <div class="media-body">
                    <h5>Total Amount On Cable </h5>
                </div>
                <div class="card-body-top">
                    <div>

                        <span>
                            <h5> #{{ $dataCA - 0 }} </h5>
                        </span>
                        <span>
                            <h5> </h5>
                        </span>

                    </div>

                </div>
            </div>
        </div>

        <div class="flot-wrapper">
            <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
        </div>
    </div>
</div>



</div>
@endif
<!-- Container closed -->

</div>
<!-- main-content closed -->

<!-- Right-sidebar-->
<div class="sidebar sidebar-right sidebar-animate">
    <div class="p-3">
        <a href="#" class="text-right float-right" data-toggle="sidebar-right" data-target=".sidebar-right"><i
                class="fe fe-x"></i></a>
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
        @include('admin.footer')