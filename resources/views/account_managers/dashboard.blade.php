@include('admin.head')

@include('admin.header')
@include('account_managers.sidebar')

@php
    $totalUsers = (int) ($status['total_users'] ?? 0);
    $totalDeposit = (float) ($status['total_deposit'] ?? 0);
@endphp

<style>
    .manager-dashboard-hero {
        border-radius: 18px;
        border: 1px solid rgba(28, 39, 60, 0.08);
        background: linear-gradient(135deg, rgba(98, 89, 202, 0.08), rgba(98, 89, 202, 0));
    }

    .manager-dashboard-hero h2 {
        font-size: 22px;
        margin-bottom: 6px;
    }

    .manager-dashboard-hero .hero-subtitle {
        color: #7987a1;
        margin-bottom: 0;
    }

    .manager-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.35rem 0.85rem;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid rgba(98, 89, 202, 0.22);
        background: rgba(98, 89, 202, 0.08);
        color: #3b3895;
    }

    .manager-stat-card {
        border-radius: 18px;
        border: 1px solid rgba(28, 39, 60, 0.08);
        overflow: hidden;
    }

    .manager-stat-card .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .manager-stat-card .stat-label {
        color: #7987a1;
        font-size: 13px;
        margin-bottom: 4px;
    }

    .manager-stat-card .stat-value {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 0;
        letter-spacing: -0.02em;
    }

    .manager-action-card {
        border-radius: 18px;
        border: 1px solid rgba(28, 39, 60, 0.08);
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .manager-action-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 26px rgba(28, 39, 60, 0.12);
    }

    .manager-action-card .action-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        background: rgba(98, 89, 202, 0.1);
        color: #6259ca;
    }
</style>

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

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="manager-dashboard-hero p-4 mb-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div class="mb-3 mb-md-0">
                            <p class="text-muted mb-1">Account Manager Dashboard</p>
                            <h2>Welcome back, {{ $manager->name }}</h2>
                            <p class="hero-subtitle">Here is a quick snapshot of your assigned users and balances.</p>
                        </div>
                        <div class="d-flex flex-wrap align-items-center">
                            <span class="manager-pill mr-2 mb-2 mb-md-0">
                                <i class="fa fa-id-badge mr-2"></i>{{ $manager->accountManagerId }}
                            </span>
                            <a href="{{ route('account-manager.users') }}" class="btn btn-primary">
                                <i class="fa fa-users mr-1"></i> View Assigned Users
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-sm">
            <div class="col-xl-4 col-lg-6 col-md-12 mb-3">
                <div class="card manager-stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="stat-label">Assigned Users</p>
                                <p class="stat-value">{{ number_format($totalUsers) }}</p>
                            </div>
                            <div class="stat-icon" style="background: rgba(98, 89, 202, 0.12); color: #6259ca;">
                                <i class="fa fa-users"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="text-muted">Total users currently mapped to your account.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-12 mb-3">
                <div class="card manager-stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="stat-label">Users Total Balance</p>
                                <p class="stat-value">#{{ number_format($totalDeposit, 2) }}</p>
                            </div>
                            <div class="stat-icon" style="background: rgba(40, 167, 69, 0.12); color: #28a745;">
                                <i class="fa fa-wallet"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="text-muted">Sum of current balances across your assigned users.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-12 mb-3">
                <div class="card manager-stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="stat-label">Manager ID</p>
                                <p class="stat-value">{{ $manager->accountManagerId }}</p>
                            </div>
                            <div class="stat-icon" style="background: rgba(23, 162, 184, 0.12); color: #17a2b8;">
                                <i class="fa fa-id-badge"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="text-muted">Use this ID for internal support and verification.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-12">
                <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
                    <h4 class="mb-0">Quick Actions</h4>
                    <span class="text-muted">Shortcuts for daily workflows</span>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-12 mb-3">
                <a href="{{ route('account-manager.users') }}" class="text-dark">
                    <div class="card manager-action-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="action-icon mr-3">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">Assigned Users</h5>
                                    <p class="text-muted mb-0">View, search, and open transactions for users assigned to you.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </div>
    <!-- container closed -->
</div>
<!-- main-content closed -->

@include('admin.footer')
