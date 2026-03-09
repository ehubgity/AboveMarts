            <!-- main-sidebar opened -->
            <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
            <aside class="app-sidebar sidebar-scroll ">
                <div class="main-sidebar-header">
                    <a class=" desktop-logo logo-light" href="{{ route('admin') }}"><img
                            src="{{ asset('img/logos/logo-inner.png') }}" width="200" class="main-logo"
                            alt="logo"></a>
                    <a class=" desktop-logo logo-dark" href="{{ route('admin') }}"><img
                            src="{{ asset('img/logos/logo-inner.png') }}" width="200" class="main-logo dark-theme"
                            alt="logo"></a>
                    <a class="logo-icon mobile-logo icon-light" href="{{ route('admin') }}"><img
                            src="{{ asset('img/logos/logo-inner.png') }}" width="200" class="logo-icon"
                            alt="logo"></a>
                    <a class="logo-icon mobile-logo icon-dark" href="{{ route('admin') }}"><img
                            src="{{ asset('img/logos/logo-inner.png') }}" width="200" class="logo-icon dark-theme"
                            alt="logo"></a>
                </div>
                <div class="main-sidebar-body circle-animation ">

                    <ul class="side-menu circle">
                        <li>
                            <h3 class="">Dashboard</h3>
                        </li>
                        <li class="slide">
                            <a class="side-menu__item" href="{{ route('admin') }}"><i
                                    class="side-menu__icon ti-desktop"></i><span
                                    class="side-menu__label">Dashboard</span></a>
                        </li>
                        <li>
                            <h3>My Profile</h3>
                        </li>
                        <li class="slide">
                            <a class="side-menu__item" href="{{ route('adminprofile') }}"><i
                                    class="side-menu__icon ti-user"></i><span class="side-menu__label">Admin
                                    Account</span></a>
                        </li>

                        @if (Auth::guard('admin')->user()->role == 'Superadmin')
                        <li class="slide">
                            <a class="side-menu__item" data-toggle="slide" href="#"><i
                                    class="side-menu__icon ti-user"></i><span class="side-menu__label">Manage
                                    Admin</span><i class="angle fe fe-chevron-down"></i></a>
                            <ul class="slide-menu">
                                <li><a class="slide-item" href="{{ route('adminregister') }}">Add Admin</a></li>
                                <li><a class="slide-item" href="{{ route('alladmin') }}">All Admin</a></li>
                            </ul>
                        </li>
                        <li class="slide">
                            <a class="side-menu__item" data-toggle="slide" href="#"><i
                                    class="side-menu__icon ti-wallet"></i><span class="side-menu__label">Cron
                                    Job</span><i class="angle fe fe-chevron-down"></i></a>
                        </li>
                        <li class="slide">
                            <a class="side-menu__item" data-toggle="slide" href="#"><i
                                    class="side-menu__icon ti-user"></i><span class="side-menu__label">Account Manager
                                </span><i class="angle fe fe-chevron-down"></i></a>
                            <ul class="slide-menu">
                                <li><a class="slide-item" href="{{ route('allmanagers') }}">All Managers</a></li>
                                <li><a class="slide-item" href="{{ route('createaccountmanagers') }}">Add Account Manager</a></li>
                                <li><a class="slide-item" href="{{ route('reassignindex') }}">Reassign Account Manager</a></li>
                            </ul>
                        </li>
                        <li class="slide">
                            <a class="side-menu__item" data-toggle="slide" href="#"><i
                                    class="side-menu__icon ti-wallet"></i><span class="side-menu__label">Cron
                                    Job</span><i class="angle fe fe-chevron-down"></i></a>

                        </li>
                        @endif
                        @if (Auth::guard('admin')->user()->role == 'Other')
                        <li class="slide">
                            <a class="side-menu__item" data-toggle="slide" href="#"><i
                                    class="side-menu__icon ti-package"></i><span class="side-menu__label">Manage
                                    User </span><i class="angle fe fe-chevron-down"></i></a>
                            <ul class="slide-menu">
                                <li><a class="slide-item" href="{{ route('adminusers') }}">All Users</a></li>
                                <li><a class="slide-item" href="{{ route('adminuserspoint') }}">User Points</a>
                                </li>
                                <li><a class="slide-item" href="{{ route('adminfunding') }}">Deposits</a></li>



                                <!-- <li><a class="slide-item" href="deposithistory.php">Add Users</a></li> -->
                            </ul>
                        </li>
                        @elseif (Auth::guard('admin')->user()->role == 'Superadmin' || Auth::guard('admin')->user()->role == 'Admin')
                        <li class="slide">
                            <a class="side-menu__item" data-toggle="slide" href="#"><i
                                    class="side-menu__icon ti-package"></i><span class="side-menu__label">Manage
                                    User </span><i class="angle fe fe-chevron-down"></i></a>
                            <ul class="slide-menu">
                                <li><a class="slide-item" href="{{ route('adminusers') }}">All Users</a></li>
                                <li><a class="slide-item" href="{{ route('adminuserspoint') }}">User Points</a>
                                </li>


                                <!-- <li><a class="slide-item" href="deposithistory.php">Add Users</a></li> -->
                            </ul>
                        </li>
                        <li class="slide">
                            <a class="side-menu__item" data-toggle="slide" href="#"><i
                                    class="side-menu__icon ti-package"></i><span class="side-menu__label">Giveaway
                                </span><i class="angle fe fe-chevron-down"></i></a>
                            <ul class="slide-menu">
                                <li><a class="slide-item" href="{{ route('admingiveaway') }}">All Users</a>
                                </li>

                            </ul>
                        </li>
                        <li class="slide">
                            <a class="side-menu__item" data-toggle="slide" href="#"><i
                                    class="side-menu__icon ti-wallet"></i><span
                                    class="side-menu__label">Payment</span><i
                                    class="angle fe fe-chevron-down"></i></a>
                            <ul class="slide-menu">
                                <li><a class="slide-item" href="{{ route('adminfunding') }}">Deposits</a></li>
                                <li><a class="slide-item" href="{{ route('adminwithdraw') }}">Withdraw</a></li>
                                <li><a class="slide-item" href="{{ route('transferhistory') }}">Transfer</a>
                                </li>
                                <li><a class="slide-item" href="{{ route('adminbonushistory') }}">Bonus
                                        History</a>
                                </li>
                                <li><a class="slide-item" href="{{ route('adminpointhistory') }}">Point
                                        History</a>
                                </li>
                                <li><a class="slide-item" href="{{ route('transactions') }}">All
                                        Transaction</a>
                                </li>
                                <li><a class="slide-item" href="{{ route('totaltransaction') }}">Balance
                                        Records</a></li>
                            </ul>
                        </li>
                        <li class="slide">
                            <a class="side-menu__item" data-toggle="slide" href="#"><i
                                    class="side-menu__icon ti-wallet"></i><span
                                    class="side-menu__label">History</span><i
                                    class="angle fe fe-chevron-down"></i></a>
                            <ul class="slide-menu">
                                <li><a class="slide-item" href="{{ route('adminpackage') }}">Package
                                        History</a>
                                </li>

                                <li><a class="slide-item" href="{{ route('cardcount') }}">Recharge Stock
                                        History</a></li>
                                <li><a class="slide-item" href="{{ route('adminpreorder') }}">Recharge Card
                                        Preorder</a></li>
                                <li><a class="slide-item" href="{{ route('adminRC') }}">Recharge Card
                                        Purchase</a>
                                </li>
                                <li><a class="slide-item" href="{{ route('usedcardhistory') }}">Used Recharge
                                        Card History</a></li>
                                <li><a class="slide-item" href="{{ route('adminRP') }}">Recharge Card
                                        Printing</a></li>
                                <li><a class="slide-item" href="{{ route('adminDP') }}">Data Purchase</a>
                                </li>
                                <li><a class="slide-item" href="{{ route('adminE') }}">Electricity</a></li>
                                <li><a class="slide-item" href="{{ route('adminC') }}">Cable</a></li>
                            </ul>
                        </li>
                        @else
                        @endif
                        @if (Auth::guard('admin')->user()->role == 'Superadmin')
                        <li class="slide">
                            <a class="side-menu__item" data-toggle="slide" href="#"><i
                                    class="side-menu__icon ti-wallet"></i><span
                                    class="side-menu__label">Funding</span><i
                                    class="angle fe fe-chevron-down"></i></a>
                            <ul class="slide-menu">
                                <li><a class="slide-item" href="{{ route('walletfund') }}">Fund Wallet</a>
                                </li>
                                <li><a class="slide-item" href="{{ route('adminpromo') }}">Add Promo</a></li>
                                <li><a class="slide-item" href="{{ route('addbonus') }}">Add Bonus</a></li>
                                <li><a class="slide-item" href="{{ route('createpin') }}">Create E-Pin</a>
                                </li>

                            </ul>
                        </li>
                        <li class="slide">
                            <a class="side-menu__item" data-toggle="slide" href="#"><i
                                    class="side-menu__icon ti-wallet"></i><span
                                    class="side-menu__label">Services</span><i
                                    class="angle fe fe-chevron-down"></i></a>
                            <ul class="slide-menu">
                                <li><a class="slide-item" href="{{ route('addcard') }}">Upload Recharge
                                        Card</a>
                                </li>
                                <li><a class="slide-item" href="{{ route('adminbuycard') }}">Buy Recharge
                                        Card</a></li>
                                <li><a class="slide-item" href="{{ route('adminbuydata') }}">Buy Recharge
                                        Data</a></li>
                                <li><a class="slide-item" href="{{ route('adminbuylight') }}">Buy Light</a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        {{--
             <li class="slide">
                <a class="side-menu__item" href="{{ route('sms') }}"><i class="side-menu__icon ti-desktop"></i><span class="side-menu__label">Signal</span></a>
                        </li>
                        <li class="slide">
                            <a class="side-menu__item" href="{{ route('addadmin') }}"><i class="side-menu__icon ti-user"></i><span class="side-menu__label">Add Admin</span></a>
                        </li>
                        <li class="slide">
                            <a class="side-menu__item" href="{{ route('admincommission') }}"><i class="side-menu__icon ti-wallet"></i><span class="side-menu__label">Commission Payment</span></a>
                        </li> --}}
                        @if (Auth::guard('admin')->user()->role == 'Superadmin' ||
                        Auth::guard('admin')->user()->role == 'Admin' ||
                        Auth::guard('admin')->user()->role == 'Other')
                        <li class="slide">
                            <a class="side-menu__item" href="{{ route('adminsupport') }}"><i
                                    class="side-menu__icon ti-email  menu-icons"></i><span
                                    class="side-menu__label">Support</span></a>
                        </li>
                        <li class="slide">
                            <a class="side-menu__item" href="{{ route('adminlogout') }}"><i
                                    class="side-menu__icon fas fa-sign-out-alt menu-icons"></i><span
                                    class="side-menu__label">Log Out</span></a>
                        </li>

                        </li>
                        @endif


                    </ul>
                </div>
            </aside>