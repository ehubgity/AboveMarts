            <!-- main-sidebar opened -->
            <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
            <aside class="app-sidebar sidebar-scroll ">
                <div class="main-sidebar-header">
                    <a class=" desktop-logo logo-light" href="{{ route('account-manager.dashboard') }}"><img
                            src="{{ asset('img/logos/logo-inner.png') }}" width="200" class="main-logo"
                            alt="logo"></a>
                    <a class=" desktop-logo logo-dark" href="{{ route('account-manager.dashboard') }}"><img
                            src="{{ asset('img/logos/logo-inner.png') }}" width="200" class="main-logo dark-theme"
                            alt="logo"></a>
                    <a class="logo-icon mobile-logo icon-light" href="{{ route('account-manager.dashboard') }}"><img
                            src="{{ asset('img/logos/logo-inner.png') }}" width="200" class="logo-icon"
                            alt="logo"></a>
                    <a class="logo-icon mobile-logo icon-dark" href="{{ route('account-manager.dashboard') }}"><img
                            src="{{ asset('img/logos/logo-inner.png') }}" width="200" class="logo-icon dark-theme"
                            alt="logo"></a>
                </div>
                <div class="main-sidebar-body circle-animation ">

                    <ul class="side-menu circle">
                        <li>
                            <h3 class="">Manager Portal</h3>
                        </li>
                        <li class="slide">
                            <a class="side-menu__item" href="{{ route('account-manager.dashboard') }}"><i
                                    class="side-menu__icon ti-desktop"></i><span
                                    class="side-menu__label">Dashboard</span></a>
                        </li>
                        <li>
                            <h3>User Management</h3>
                        </li>
                        <li class="slide">
                            <a class="side-menu__item" href="{{ route('account-manager.users') }}"><i
                                    class="side-menu__icon ti-user"></i><span class="side-menu__label">Assigned
                                    Users</span></a>
                        </li>

                        <li class="slide">
                            <form id="logout-form" action="{{ route('account-manager.logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a class="side-menu__item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="side-menu__icon fas fa-sign-out-alt"></i>
                                <span class="side-menu__label">Log Out</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </aside>
