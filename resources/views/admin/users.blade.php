@include('admin.head')
@include('admin.header')
@include('admin.sidebar')

@php
    $adminUser = Auth::guard('admin')->user();
    $isSuperadmin = $adminUser && $adminUser->role === 'Superadmin';
    $activeQuery = trim($query ?? '');
    $activeAlphabet = $startsWith ?? null;
    $assignmentSummary = $assignmentSummary ?? [
        'totalUsers' => $datausers->total(),
        'assignedUsers' => 0,
        'unassignedUsers' => 0,
        'filteredUsers' => $datausers->total(),
    ];
@endphp

<style>
    .users-toolbar-card,
    .assignment-mode-card,
    .summary-card {
        border: 1px solid rgba(28, 39, 60, 0.08);
        border-radius: 14px;
    }

    .assignment-mode-card {
        cursor: pointer;
        transition: all 0.2s ease;
        height: 100%;
    }

    .assignment-mode-card.is-active {
        border-color: #6259ca;
        box-shadow: 0 0 0 0.15rem rgba(98, 89, 202, 0.15);
        background: rgba(98, 89, 202, 0.04);
    }

    .assignment-mode-card.is-disabled {
        opacity: 0.55;
        cursor: not-allowed;
    }

    .alphabet-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(42px, 1fr));
        gap: 8px;
    }

    .alphabet-grid .btn.active {
        background: #6259ca;
        border-color: #6259ca;
        color: #fff;
    }

    .user-name-cell {
        min-width: 210px;
    }

    .user-meta-text {
        color: #7987a1;
        display: block;
        font-size: 12px;
        line-height: 1.4;
    }

    .manager-pill {
        border-radius: 999px;
        display: inline-flex;
        padding: 0.35rem 0.75rem;
        font-size: 12px;
        font-weight: 600;
    }

    .manager-pill.is-assigned {
        background: rgba(40, 167, 69, 0.14);
        color: #1f7a33;
    }

    .manager-pill.is-unassigned {
        background: rgba(220, 53, 69, 0.12);
        color: #b42318;
    }

    .selection-toolbar {
        gap: 10px;
    }

    .selection-toolbar .btn {
        white-space: nowrap;
    }
</style>

<div class="main-content app-content">
    <div class="main-header sticky side-header nav nav-item">
        <div class="container-fluid">
            <div class="main-header-left">
                <div class="app-sidebar__toggle mobile-toggle" data-toggle="sidebar">
                    <a class="open-toggle" href="#"><i class="header-icons" data-eva="menu-outline"></i></a>
                    <a class="close-toggle" href="#"><i class="header-icons" data-eva="close-outline"></i></a>
                </div>
            </div>
            <div class="main-header-center"></div>
            <div class="main-header-right">
                <div class="nav nav-item navbar-nav-right ml-auto">
                    <div class="nav-item full-screen fullscreen-button">
                        <a class="new nav-link full-screen-link" href="#"><i class="ti-fullscreen"></i></a>
                    </div>
                    <button class="navbar-toggler navresponsive-toggler d-sm-none" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon fe fe-more-vertical"></span>
                    </button>
                    <div class="dropdown main-header-message right-toggle">
                        <a class="nav-link" data-toggle="sidebar-right" data-target=".sidebar-right">
                            <i class="ti-menu tx-20 bg-transparent"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="responsive main-header collapse" id="navbarSupportedContent-4">
        <div class="mb-1 navbar navbar-expand-lg nav nav-item navbar-nav-right responsive-navbar navbar-dark d-sm-none">
            <div class="navbar-collapse">
                <div class="d-flex order-lg-2 ml-auto">
                    <div class="d-md-flex">
                        <div class="nav-item full-screen fullscreen-button">
                            <a class="new nav-link full-screen-link" href="#"><i class="ti-fullscreen"></i></a>
                        </div>
                    </div>
                    <div class="dropdown main-header-message right-toggle">
                        <a class="nav-link" data-toggle="sidebar-right" data-target=".sidebar-right">
                            <i class="ti-menu tx-20 bg-transparent"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="breadcrumb-header justify-content-between">
            <div class="row w-100">
                <div class="col-md-12">
                    <div class="d-flex align-items-center mb-3">
                        <i class="mdi mdi-home text-muted hover-cursor"></i>
                        <p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;Users</p>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card users-toolbar-card mb-4">
                        <div class="card-body">
                            <div class="row align-items-end">
                                <div class="col-lg-6">
                                    <label class="font-weight-bold">Search users</label>
                                    <form action="{{ route('usersearch') }}" method="GET" class="d-flex flex-wrap">
                                        @if ($activeAlphabet)
                                            <input type="hidden" name="starts_with" value="{{ $activeAlphabet }}">
                                        @endif
                                        <input type="text" name="query" value="{{ $activeQuery }}" placeholder="Search by name, email, username, sponsor, package or status"
                                            class="form-control mr-2 mb-2">
                                        <button class="btn btn-success mr-2 mb-2" type="submit">Search</button>
                                        @if ($activeQuery !== '' || $activeAlphabet)
                                            <a href="{{ route('adminusers') }}" class="btn btn-light mb-2">Clear</a>
                                        @endif
                                    </form>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <button class="btn btn-primary w-100" onclick="printTable()">Print Table</button>
                                        </div>
                                        @if ($isSuperadmin)
                                            <div class="col-md-6 mb-2">
                                                <form action="{{ route('exportusers') }}" method="GET" class="d-flex">
                                                    <select name="package" class="form-control mr-2">
                                                        <option value="None">All Packages</option>
                                                        <option value="Basic">Basic</option>
                                                        <option value="Bronze">Bronze</option>
                                                        <option value="Silver">Silver</option>
                                                        <option value="Gold">Gold</option>
                                                        <option value="Platinum">Platinum</option>
                                                    </select>
                                                    <button class="btn btn-warning" type="submit">Export CSV</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if ($activeQuery !== '')
                                <div class="alert alert-light border mt-3 mb-0">
                                    Showing {{ $assignmentSummary['filteredUsers'] }} matching user{{ $assignmentSummary['filteredUsers'] === 1 ? '' : 's' }} for
                                    <strong>{{ $activeQuery }}</strong>.
                                </div>
                            @endif

                            @if ($activeAlphabet)
                                <div class="alert alert-light border {{ $activeQuery !== '' ? 'mt-2' : 'mt-3' }} mb-0">
                                    Showing users whose first name starts with <strong>{{ $activeAlphabet }}</strong>.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card summary-card mb-3">
                        <div class="card-body">
                            <p class="text-muted mb-2">Total users</p>
                            <h3 class="mb-0">{{ number_format($assignmentSummary['totalUsers']) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card mb-3">
                        <div class="card-body">
                            <p class="text-muted mb-2">Assigned users</p>
                            <h3 class="mb-0">{{ number_format($assignmentSummary['assignedUsers']) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card mb-3">
                        <div class="card-body">
                            <p class="text-muted mb-2">Unassigned users</p>
                            <h3 class="mb-0">{{ number_format($assignmentSummary['unassignedUsers']) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card summary-card mb-3">
                        <div class="card-body">
                            <p class="text-muted mb-2">Matched results</p>
                            <h3 class="mb-0">{{ number_format($datausers->total()) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($isSuperadmin)
            <div class="row">
                <div class="col-md-12">
                    <div class="card overflow-hidden mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap mb-4">
                                <div class="mb-3">
                                    <h4 class="mb-1">Manager Assignment Workspace</h4>
                                    <p class="text-muted mb-0">Assign selected rows, every user under the current search, or all users whose names start with one letter.</p>
                                </div>
                                <div>
                                    <span class="badge badge-primary px-3 py-2" id="selectedCountBadge">0 selected on this page</span>
                                </div>
                            </div>

                            <form action="{{ route('assign') }}" method="POST" id="bulkAssignForm" data-has-filtered-results="{{ $activeQuery !== '' || $activeAlphabet ? '1' : '0' }}">
                                @csrf
                                <input type="hidden" name="query" value="{{ $activeQuery }}">
                                <input type="hidden" name="starts_with" id="startsWithInput" value="{{ old('starts_with', $activeAlphabet) }}">

                                <div class="row">
                                    <div class="col-lg-4">
                                        <label class="font-weight-bold">Assign to manager</label>
                                        <select name="manager_email" class="form-control" required>
                                            <option value="">Select manager</option>
                                            @foreach ($managers as $manager)
                                                <option value="{{ $manager->email }}" {{ old('manager_email') == $manager->email ? 'selected' : '' }}>
                                                    {{ $manager->name }} ({{ $manager->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 mt-3 mt-lg-0">
                                        <label class="font-weight-bold d-block">Scope</label>
                                        <div class="custom-control custom-checkbox mt-2">
                                            <input type="checkbox" class="custom-control-input" id="onlyUnassigned" name="only_unassigned" value="1"
                                                {{ old('only_unassigned') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="onlyUnassigned">Only users without a manager</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mt-3 mt-lg-0">
                                        <label class="font-weight-bold d-block">Action</label>
                                        <button type="submit" class="btn btn-primary btn-block mt-2">Assign Users</button>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-4 mb-3">
                                        <label class="assignment-mode-card p-3 mb-0 is-active" data-mode-card="selected">
                                            <input type="radio" class="d-none assignment-mode-input" name="assignment_mode" value="selected"
                                                {{ old('assignment_mode', 'selected') === 'selected' ? 'checked' : '' }}>
                                            <span class="d-block font-weight-bold">Selected rows</span>
                                            <span class="text-muted d-block mt-1">Assign only the users you tick in the table below.</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="assignment-mode-card p-3 mb-0" data-mode-card="search">
                                            <input type="radio" class="d-none assignment-mode-input" name="assignment_mode" value="search"
                                                {{ old('assignment_mode') === 'search' ? 'checked' : '' }} {{ $activeQuery === '' && !$activeAlphabet ? 'disabled' : '' }}>
                                            <span class="d-block font-weight-bold">Current filtered results</span>
                                            <span class="text-muted d-block mt-1">
                                                {{ $activeQuery === '' && !$activeAlphabet ? 'Apply a search or alphabet filter first, then assign all matching users at once.' : 'Assign every user currently shown by your active search and alphabet filters.' }}
                                            </span>
                                        </label>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="assignment-mode-card p-3 mb-0" data-mode-card="alphabet">
                                            <input type="radio" class="d-none assignment-mode-input" name="assignment_mode" value="alphabet"
                                                {{ old('assignment_mode') === 'alphabet' ? 'checked' : '' }}>
                                            <span class="d-block font-weight-bold">Starts with a letter</span>
                                            <span class="text-muted d-block mt-1">Assign every user whose first name starts with one selected alphabet.</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div id="selectedModePanel" class="assignment-panel">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap selection-toolbar">
                                                <p class="text-muted mb-2 mb-md-0">Use the checkboxes in the table to build your selection. This mode works well for mixed groups.</p>
                                                <div class="d-flex flex-wrap selection-toolbar">
                                                    <button type="button" class="btn btn-light btn-sm" id="selectVisibleUsers">Select visible page</button>
                                                    <button type="button" class="btn btn-light btn-sm" id="clearVisibleUsers">Clear selection</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="searchModePanel" class="assignment-panel d-none">
                                            <div class="alert alert-light border mb-0">
                                                @if ($activeQuery !== '' || $activeAlphabet)
                                                    This will assign <strong>all {{ number_format($assignmentSummary['filteredUsers']) }}</strong> users in the current filtered result set,
                                                    including every matching page.
                                                @else
                                                    This mode becomes available after applying a search or alphabet filter.
                                                @endif
                                            </div>
                                        </div>

                                        <div id="alphabetModePanel" class="assignment-panel d-none">
                                            <p class="text-muted mb-3">Choose a letter to filter the table by users whose first name starts with that alphabet, then use current filtered results to assign them.</p>
                                            <div class="alphabet-grid">
                                                @foreach (range('A', 'Z') as $letter)
                                                    <a href="{{ route('adminusers', array_filter(['query' => $activeQuery ?: null, 'starts_with' => $letter])) }}"
                                                        class="btn btn-outline-secondary {{ $activeAlphabet === $letter ? 'active' : '' }}">{{ $letter }}</a>
                                                @endforeach
                                            </div>
                                            <div class="mt-3">
                                                <span class="badge badge-light border px-3 py-2" id="selectedLetterLabel">
                                                    {{ $activeAlphabet ? 'Filtered alphabet: ' . $activeAlphabet : 'No alphabet selected' }}
                                                </span>
                                                @if ($activeAlphabet)
                                                    <a href="{{ route('adminusers', array_filter(['query' => $activeQuery ?: null])) }}" class="btn btn-light btn-sm ml-2">Clear alphabet filter</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row" style="width: 100%;">
            <div class="col-md-12">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                            <div>
                                <h4 class="mb-1">Users Directory</h4>
                                <p class="text-muted mb-0">Review users, track current manager assignments, and handle one-off updates from a single table.</p>
                            </div>
                            <i class="mdi mdi-dots-horizontal text-gray"></i>
                        </div>

                        <div class="table-responsive market-values">
                            <table class="table table-bordered table-hover table-striped mb-0 tx-13">
                                <thead>
                                    <tr>
                                        @if ($isSuperadmin)
                                            <th class="text-center" style="width: 48px;">
                                                <input type="checkbox" class="selectAll" id="selectAllUsers">
                                            </th>
                                        @endif
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Sponsor</th>
                                        <th>Rank</th>
                                        <th>Points</th>
                                        <th>Before Balance</th>
                                        <th>Current Balance</th>
                                        <th>Package</th>
                                        <th>Account Number</th>
                                        <th>Assigned Manager</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        @if ($isSuperadmin)
                                            <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($datausers as $datauser)
                                        <tr data-name="{{ strtoupper(trim(($datauser->firstName ?? '') . ' ' . ($datauser->lastName ?? ''))) }}"
                                            data-username="{{ strtoupper($datauser->username ?? '') }}">
                                            @if ($isSuperadmin)
                                                <td class="text-center">
                                                    <input type="checkbox" name="usernames[]" value="{{ $datauser->username }}" class="user-checkbox"
                                                        form="bulkAssignForm">
                                                </td>
                                            @endif
                                            <td class="user-name-cell">
                                                <strong>{{ trim(($datauser->firstName ?? '') . ' ' . ($datauser->lastName ?? '')) ?: 'No Name' }}</strong>
                                                <span class="user-meta-text">Joined {{ $datauser->created_at ? $datauser->created_at->format('d M Y, h:i A') : '' }}</span>
                                            </td>
                                            <td>{{ $datauser->username }}</td>
                                            <td>{{ $datauser->email }}</td>
                                            <td>{{ $datauser->phoneNumber }}</td>
                                            <td>{{ $datauser->sponsor }}</td>
                                            <td>{{ $datauser->rank }}</td>
                                            <td>{{ $datauser->point }}</td>
                                            <td>{{ $datauser->beforeBalance }}</td>
                                            <td>{{ $datauser->currentBalance }}</td>
                                            <td>{{ $datauser->package }}</td>
                                            <td>{{ $datauser->accountNumber }}</td>
                                            <td>
                                                @if ($datauser->accountManager)
                                                    <span class="manager-pill is-assigned">{{ $datauser->accountManager->name }}</span>
                                                    <span class="user-meta-text">{{ $datauser->accountManager->email }}</span>
                                                @else
                                                    <span class="manager-pill is-unassigned">Not Assigned</span>
                                                @endif
                                            </td>
                                            <td>{{ $datauser->status }}</td>
                                            <td>{{ $datauser->created_at }}</td>
                                            @if ($isSuperadmin)
                                                <td>
                                                    <div class="btn-group">
                                                        @if ($datauser->status == 'ACTIVE')
                                                            <button class="btn btn-success" data-toggle="modal" title="Lock User"
                                                                data-target="#myModalLOCK{{ $datauser->userId }}"><i class="fa fa-unlock"></i></button>
                                                        @else
                                                            <button class="btn btn-danger" data-toggle="modal" title="Unlock User"
                                                                data-target="#myModalUNLOCK{{ $datauser->userId }}"><i class="fa fa-lock"></i></button>
                                                        @endif
                                                        <button class="btn btn-primary btn-sm open-assign-modal" data-toggle="modal"
                                                            data-target="#sharedAssignModal" data-username="{{ $datauser->username }}"
                                                            data-user-label="{{ trim(($datauser->firstName ?? '') . ' ' . ($datauser->lastName ?? '')) ?: $datauser->username }}"
                                                            data-manager-email="{{ optional($datauser->accountManager)->email }}"
                                                            title="Assign Manager">
                                                            <i class="fa fa-user-plus"></i>
                                                        </button>
                                                        <a class="btn btn-info" title="Edit User"
                                                            href="{{ route('edituser', ['id' => $datauser->userId]) }}"><i class="fa fa-edit"></i></a>
                                                        <button class="btn btn-danger" data-toggle="modal" title="Delete User"
                                                            data-target="#myModalDELETED{{ $datauser->userId }}"><i class="fa fa-trash"></i></button>
                                                    </div>

                                                    <div id="myModalLOCK{{ $datauser->userId }}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Lock User?</h4>
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to lock this user?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <a class="btn btn-danger" href="{{ route('adminusers', ['lockid' => $datauser->userId]) }}">Lock User</a>
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="myModalDELETED{{ $datauser->userId }}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Delete User?</h4>
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to delete this user?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <a class="btn btn-danger" href="{{ route('adminusers', ['deleteid' => $datauser->userId]) }}">Delete User</a>
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="myModalUNLOCK{{ $datauser->userId }}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Unlock User?</h4>
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to unlock this user?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <a class="btn btn-danger" href="{{ route('adminusers', ['unlockid' => $datauser->userId]) }}">Unlock User</a>
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ $isSuperadmin ? 16 : 15 }}" class="text-center py-5">
                                                <h5 class="mb-2">No users found</h5>
                                                <p class="text-muted mb-0">Try a different search term or clear the active filters.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $datausers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($isSuperadmin)
        <div id="sharedAssignModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Assign Manager</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="{{ route('reassign') }}" method="POST">
                        @csrf
                        <input type="hidden" name="username" id="sharedAssignUsername">
                        <div class="modal-body">
                            <p class="mb-3 text-muted">Update the assigned manager for <strong id="sharedAssignUserLabel">this user</strong>.</p>
                            <div class="form-group">
                                <label>Select Account Manager</label>
                                <select name="manager_email" id="sharedAssignManagerEmail" class="form-control" required>
                                    <option value="">Select manager</option>
                                    @foreach ($managers as $manager)
                                        <option value="{{ $manager->email }}">{{ $manager->name }} ({{ $manager->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save Assignment</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

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
        <div class="panel-body tabs-menu-body side-tab-body p-0 border-0">
            <div class="tab-content">
                <div class="tab-pane active" id="tab">
                    <div class="card-body p-0">
                        <a class="dropdown-item mt-4 border-top" href="editprofile.php">
                            <i class="dropdown-icon fe fe-edit mr-2"></i> Edit Profile
                        </a>
                        <a class="dropdown-item border-top" href="support.php">
                            <i class="dropdown-icon fe fe-help-circle mr-2"></i> Need Help?
                        </a>
                        <a class="dropdown-item border-top" href="logout.php">
                            <i class="dropdown-icon fas fa-sign-out-alt mr-2"></i> Log Out
                        </a>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById('bulkAssignForm');
                    const hasActiveFilteredResults = form ? form.getAttribute('data-has-filtered-results') === '1' : false;
                    const selectedCountBadge = document.getElementById('selectedCountBadge');
                    const selectAllCheckbox = document.getElementById('selectAllUsers');
                    const visibleCheckboxes = Array.from(document.querySelectorAll('.user-checkbox'));
                    const modeInputs = Array.from(document.querySelectorAll('.assignment-mode-input'));
                    const modeCards = Array.from(document.querySelectorAll('[data-mode-card]'));
                    const selectVisibleUsers = document.getElementById('selectVisibleUsers');
                    const clearVisibleUsers = document.getElementById('clearVisibleUsers');
                    const assignmentPanels = {
                        selected: document.getElementById('selectedModePanel'),
                        search: document.getElementById('searchModePanel'),
                        alphabet: document.getElementById('alphabetModePanel')
                    };

                    const showMessage = function(message) {
                        if (window.Swal) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Assignment unavailable',
                                text: message
                            });
                            return;
                        }

                        window.alert(message);
                    };

                    const updateSelectionSummary = function() {
                        const selectedCount = visibleCheckboxes.filter(function(checkbox) {
                            return checkbox.checked;
                        }).length;

                        if (selectedCountBadge) {
                            selectedCountBadge.textContent = selectedCount + ' selected on this page';
                        }

                        if (selectAllCheckbox) {
                            selectAllCheckbox.checked = visibleCheckboxes.length > 0 && selectedCount === visibleCheckboxes.length;
                        }
                    };

                    const applyModeState = function() {
                        const activeInput = modeInputs.find(function(input) {
                            return input.checked;
                        });

                        const activeMode = activeInput ? activeInput.value : 'selected';

                        modeCards.forEach(function(card) {
                            const cardMode = card.getAttribute('data-mode-card');
                            card.classList.toggle('is-active', cardMode === activeMode);
                            card.classList.toggle('is-disabled', card.querySelector('input')?.disabled === true);
                        });

                        Object.keys(assignmentPanels).forEach(function(panelKey) {
                            assignmentPanels[panelKey]?.classList.toggle('d-none', panelKey !== activeMode);
                        });
                    };

                    modeCards.forEach(function(card) {
                        card.addEventListener('click', function() {
                            const input = card.querySelector('.assignment-mode-input');

                            if (!input || input.disabled) {
                                return;
                            }

                            input.checked = true;
                            applyModeState();
                        });
                    });

                    if (selectAllCheckbox) {
                        selectAllCheckbox.addEventListener('change', function() {
                            visibleCheckboxes.forEach(function(checkbox) {
                                checkbox.checked = selectAllCheckbox.checked;
                            });
                            updateSelectionSummary();
                        });
                    }

                    visibleCheckboxes.forEach(function(checkbox) {
                        checkbox.addEventListener('change', updateSelectionSummary);
                    });

                    if (selectVisibleUsers) {
                        selectVisibleUsers.addEventListener('click', function() {
                            visibleCheckboxes.forEach(function(checkbox) {
                                checkbox.checked = true;
                            });
                            updateSelectionSummary();
                        });
                    }

                    if (clearVisibleUsers) {
                        clearVisibleUsers.addEventListener('click', function() {
                            visibleCheckboxes.forEach(function(checkbox) {
                                checkbox.checked = false;
                            });
                            updateSelectionSummary();
                        });
                    }

                    if (form) {
                        form.addEventListener('submit', function(event) {
                            const activeModeInput = modeInputs.find(function(input) {
                                return input.checked;
                            });

                            const activeMode = activeModeInput ? activeModeInput.value : 'selected';

                            if (activeMode === 'selected' && visibleCheckboxes.filter(function(checkbox) {
                                return checkbox.checked;
                            }).length === 0) {
                                event.preventDefault();
                                showMessage('Select at least one user on this page before assigning a manager.');
                            }

                            if (activeMode === 'alphabet' && !document.getElementById('startsWithInput')?.value) {
                                event.preventDefault();
                                showMessage('Choose an alphabet filter first before assigning users by first name.');
                            }

                            if (activeMode === 'search' && !hasActiveFilteredResults) {
                                event.preventDefault();
                                showMessage('Apply a search or alphabet filter first before assigning the current filtered results.');
                            }
                        });
                    }

                    document.querySelectorAll('.open-assign-modal').forEach(function(button) {
                        button.addEventListener('click', function() {
                            const username = button.getAttribute('data-username') || '';
                            const userLabel = button.getAttribute('data-user-label') || username;
                            const managerEmail = button.getAttribute('data-manager-email') || '';
                            const usernameInput = document.getElementById('sharedAssignUsername');
                            const labelElement = document.getElementById('sharedAssignUserLabel');
                            const managerSelect = document.getElementById('sharedAssignManagerEmail');

                            if (usernameInput) {
                                usernameInput.value = username;
                            }

                            if (labelElement) {
                                labelElement.textContent = userLabel;
                            }

                            if (managerSelect) {
                                managerSelect.value = managerEmail;
                            }
                        });
                    });

                    updateSelectionSummary();
                    applyModeState();
                });
            </script>

            @include('admin.footer');
