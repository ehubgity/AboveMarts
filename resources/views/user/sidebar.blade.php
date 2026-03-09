<div id="sidebar" class="app-sidebar">

    <div class="app-sidebar-content" data-scrollbar="true" data-height="100%">

        <div class="menu">
            <div class="menu-profile">
                <a href="javascript:;" class="menu-profile-link" data-toggle="app-sidebar-profile"
                    data-target="#appSidebarProfileMenu">
                    <div class="menu-profile-cover with-shadow"></div>
                    <div class="menu-profile-image">
                        <img src="{{ auth()->user()->photo }}" alt="" />
                    </div>
                    <div class="menu-profile-info">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                {{ auth()->user()->lastName }} {{ auth()->user()->firstName }}
                                ({{ auth()->user()->rank }})
                            </div>
                        </div>
                        <small>{{ auth()->user()->package }} Package</small>

                    </div>
                </a>
            </div>

            <div class="menu-item has-sub active">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-sitemap"></i>
                    </div>
                    <div class="menu-text">Dashboard</div>
                </a>
            </div>
            
            <div class="menu-item has-sub">
                <a href="javascript:;" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-hdd"></i>
                    </div>
                    <div class="menu-text">My Account</div>
                    <div class="menu-caret"></div>
                </a>
                <div class="menu-submenu">
                    <div class="menu-item">
                        <a href="{{ route('profile') }}" class="menu-link">
                            <div class="menu-text">Edit Profile</div>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('userpackage') }}" class="menu-link">
                            <div class="menu-text">Buy Package</div>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('packagehistory') }}" class="menu-link">
                            <div class="menu-text">My Package</div>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('pointhistory') }}" class="menu-link">
                            <div class="menu-text">My Points</div>
                        </a>
                    </div>

                </div>
            </div>
           
            <div class="menu-item has-sub">
                <a href="javascript:;" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-gem"></i>
                    </div>
                    <div class="menu-text">Payment</div>
                    <div class="menu-caret"></div>
                </a>
                <div class="menu-submenu">
                    <div class="menu-item">
                        <a href="{{ route('fund') }}" class="menu-link">
                            <div class="menu-text">Fund Wallet <i class="fa fa-wallet text-theme"></i></div>
                        </a>
                    </div>
                      @if (auth()->user()->rank != 'Free Member')
                    <div class="menu-item">
                        <a href="{{ route('withdraw') }}" class="menu-link">
                            <div class="menu-text">Get Payout <i class="fa fa-wallet text-theme"></i></div>
                        </a>
                    </div>
                    @endif
                    <div class="menu-item">
                        <a href="{{ route('transfer') }}" class="menu-link">
                            <div class="menu-text">Remit Income <i class="fa fa-wallet text-theme"></i></div>
                        </a>
                    </div>
                    @if (auth()->user()->rank != 'Free Member')
                        <div class="menu-item">
                            <a href="{{ route('membertransfer') }}" class="menu-link">
                                <div class="menu-text">Share Balance <i class="fa fa-wallet text-theme"></i></div>
                            </a>
                        </div>
                    @else
                     <div class="menu-item">
                            <a href="{{ route('userpackage') }}" class="menu-link">
                                <div class="menu-text">Share Balance <i class="fa fa-wallet text-theme"></i></div>
                            </a>
                        </div>
                    @endif

                </div>
            </div>


            <div class="menu-item has-sub">
                <a href="javascript:;" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-shop"></i>
                    </div>
                    <div class="menu-text">E-Commerce</div>
                    <div class="menu-caret"></div>
                </a>
                <div class="menu-submenu">
                    <div class="menu-item">
                        <a href="http://www.abovestores.com" target="_blank" class="menu-link">
                            <div class="menu-text">AboveStores </div>
                        </a>
                    </div>
                     <div class="menu-item">
                        <a href="http://shop.abovemarts.com/marketplace" target="_blank" class="menu-link">
                            <div class="menu-text">MarketPlace </div>
                        </a>
                    </div>


                </div>
            </div>


            <div class="menu-item has-sub">
                <a href="javascript:;" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-list-ol"></i>
                    </div>

                    {{-- <div class="menu-text">Telecom Services <span class="menu-label">NEW</span></div> --}}
                    <div class="menu-text">Airtime & Data</div>
                    <div class="menu-caret"></div>
                </a>
                <div class="menu-submenu">

                    <div class="menu-item">
                        <a href="{{ route('rechargepurchase') }}" class="menu-link">
                            <div class="menu-text">Airtime Topup</div>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="{{ route('datashare') }}" class="menu-link">
                            <div class="menu-text">Discounted Data </i></div>
                        </a>
                    </div>


                </div>
            </div>
            <div class="menu-item has-sub">
                <a href="javascript:;" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-list-ol"></i>
                    </div>
                    <div class="menu-text">Utility & Bills</div>
                    <div class="menu-caret"></div>
                </a>
                <div class="menu-submenu">


                    <div class="menu-item">
                        <a href="{{ route('tvsub') }}" class="menu-link">
                            <div class="menu-text">Cable TV Subscription</div>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="{{ route('lightpurchase') }}" class="menu-link">
                            <div class="menu-text">Electricity Payment</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="menu-item has-sub">
                <a href="javascript:;" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-list-ol"></i>
                    </div>
                    <div class="menu-text">Recharge Card Printing</div>
                    <div class="menu-caret"></div>
                </a>
                <div class="menu-submenu">


                    <div class="menu-item">
                        <a href="{{ route('samplecards') }}" class="menu-link">
                            <div class="menu-text">Demo Printing</div>
                        </a>
                    </div>
                        @if (auth()->user()->package == 'Basic')
                        @else
                    <div class="menu-item">
                        <a href="{{ route('rechargeprinting') }}" class="menu-link">
                            <div class="menu-text">Live Printing</div>
                        </a>
                    </div>
                    @endif
                    <div class="menu-item">
                        <a href="{{ route('preordercard') }}" class="menu-link">
                            <div class="menu-text">Preorder Printing</div>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('cardhistory') }}" class="menu-link">
                            <div class="menu-text">Printing History</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="menu-item has-sub">
                <a href="javascript:;" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-list-ol"></i>
                    </div>
                    <div class="menu-text">BulkSMS Services</div>
                    <div class="menu-caret"></div>
                </a>
                <div class="menu-submenu">


                    <div class="menu-item">
                        <a href="{{ route('smshome') }}" class="menu-link">
                            <div class="menu-text">Send SMS</div>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="/contact_group" class="menu-link">
                            <div class="menu-text">Contact Group</div>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="/smstransactions" class="menu-link">
                            <div class="menu-text">Transactions</div>
                        </a>
                    </div>


                </div>
            </div>
            <div class="menu-item has-sub">
                <a href="javascript:;" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-school"></i>
                    </div>
                    <div class="menu-text">E-Learning Centre</div>
                    <div class="menu-caret"></div>
                </a>
                <div class="menu-submenu">
                    <div class="menu-item">
                        <a href="https://learn.abovemarts.com/allebooks" class="menu-link">
                            <div class="menu-text">E-Library</div>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="https://learn.abovemarts.com/allcourses" class="menu-link">
                            <div class="menu-text">E-Courses</i></div>
                        </a>
                    </div>

                </div>
            </div>
             @if (auth()->user()->rank != 'Free Member')
                        <div class="menu-item has-sub">
                <a href="javascript:;" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-hdd"></i>
                    </div>
                    <div class="menu-text">Raffle Giveaway</div>
                    <div class="menu-caret"></div>
                </a>
                <div class="menu-submenu">
                    <div class="menu-item">
                        <a href="http://www.abovemarts.com/create-giveaway" class="menu-link">
                            <div class="menu-text">Create Giveaway</div>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="http://www.abovemarts.com/my-giveaway" class="menu-link">
                            <div class="menu-text">My Giveaways</div>
                        </a>
                    </div>
                  
                </div>
            </div>

            @endif

            @if (auth()->user()->rank != 'Free Member')
                <div class="menu-item has-sub">
                    <a href="javascript:;" class="menu-link">
                        <div class="menu-icon">
                            <i class="fas fa-donate"></i>
                        </div>
                        <div class="menu-text">Financial Services</div>
                        <div class="menu-caret"></div>
                    </a>

                    <div class="menu-submenu">
                        <!--<div class="menu-item">-->
                        <!--    <a href="http://abovefinex.com/" target="_blank" class="menu-link">-->
                        <!--        <div class="menu-text">Above Finex </div>-->
                        <!--    </a>-->
                        <!--</div>-->
                        <div class="menu-item">
                            <a href="/my-vouchers" target="_blank" class="menu-link">
                                <div class="menu-text">AboveFinex Tokens</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="/buy-vouchers" target="_blank" class="menu-link">
                                <div class="menu-text">AboveMarts Tokens</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="{{ route('comingsoon') }}" class="menu-link">
                                <div class="menu-text">Instant Loan </div>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a href="{{ route('comingsoon') }}" class="menu-link">
                                <div class="menu-text">Smart Saving</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="{{ route('comingsoon') }}" class="menu-link">
                                <div class="menu-text">Venture Capital</div>
                            </a>
                        </div>
                    </div>
                </div>
                
                 @else
                <!--  <div class="menu-item has-sub">-->
                <!--    <a href="javascript:;" class="menu-link">-->
                <!--        <div class="menu-icon">-->
                <!--            <i class="fas fa-donate"></i>-->
                <!--        </div>-->
                <!--        <div class="menu-text">Financial Services</div>-->
                <!--        <div class="menu-caret"></div>-->
                <!--    </a>-->

                <!--    <div class="menu-submenu">-->
                <!--        <div class="menu-item">-->
                <!--            <a href="http://abovefinex.com/" target="_blank" class="menu-link">-->
                <!--                <div class="menu-text">Abovefinex </div>-->
                <!--            </a>-->
                <!--        </div>-->
                <!--        <div class="menu-item">-->
                <!--           <a href="{{ route('userpackage') }}" class="menu-link">-->
                <!--                <div class="menu-text">Instant Loan </div>-->
                <!--            </a>-->
                <!--        </div>-->

                <!--        <div class="menu-item">-->
                <!--            <a href="{{ route('userpackage') }}" class="menu-link">-->
                <!--                <div class="menu-text">Smart Saving</div>-->
                <!--            </a>-->
                <!--        </div>-->
                <!--        <div class="menu-item">-->
                <!--           <a href="{{ route('userpackage') }}" class="menu-link">-->
                <!--                <div class="menu-text">Venture Capital</div>-->
                <!--            </a>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
                     
            @endif
            @if (auth()->user()->rank != 'Free Member')

            <div class="menu-item has-sub">
                <a href="javascript:;" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-table"></i>
                    </div>
                    <div class="menu-text">E-Cooperative</div>
                    <div class="menu-caret"></div>
                </a>
                <div class="menu-submenu">
                    <div class="menu-item">
                        <a href="{{ route('sponsorpackage') }}" class="menu-link">
                            <div class="menu-text">Partner Package</div>
                        </a>
                    </div>

                    <div class="menu-item has-sub">
                        <a href="{{ route('annualshare') }}" class="menu-link">
                            <div class="menu-text">Cooporative Shares</div>
                        </a>
                    </div>
                    <div class="menu-item has-sub">
                        <a href="{{ route('member') }}" class="menu-link">
                            <div class="menu-text"> My Connections</div>
                        </a>
                    </div>
                    <div class="menu-item has-sub">
                        <a href="{{ route('leaderboard') }}" class="menu-link">
                            <div class="menu-text">Career Grant</div>
                        </a>
                    </div>

                    <div class="menu-item has-sub">
                        <a href="{{ route('residualincome') }}" class="menu-link">
                            <div class="menu-text">Monthly Residual</div>
                        </a>
                    </div>
                    <div class="menu-item has-sub">
                        <a href="{{ route('lifestylepension') }}" class="menu-link">
                            <div class="menu-text">Lifestyle Pension</div>
                        </a>
                    </div>
                </div>
            </div>
            @endif
            <div class="menu-item has-sub">
                <a href="javascript:;" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-wallet"></i>
                    </div>
                    <div class="menu-text">Transaction History</div>
                    <div class="menu-caret"></div>
                </a>
                <div class="menu-submenu">
                    <div class="menu-item">
                        <a href="{{ route('deposithistory') }}" class="menu-link">
                            <div class="menu-text">Deposit History </div>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('withdrawhistory') }}" class="menu-link">
                            <div class="menu-text"> Payout History</i></div>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('bonushistory') }}" class="menu-link">
                            <div class="menu-text"> Commission History</div>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('serviceshistory') }}" class="menu-link">
                            <div class="menu-text">Purchase History</div>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('lighthistory') }}" class="menu-link">
                            <div class="menu-text"> Electricity History</div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="menu-item has-sub">
                <a href="{{ route('support') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-envelope"></i>
                    </div>
                    <div class="menu-text">Support</div>
                </a>

            </div>

            <div class="menu-item has-sub">
                <a href="{{ route('logout') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-key"></i>
                    </div>
                    <div class="menu-text">Logout</div>
                </a>

            </div>
        </div>
    </div>

</div>
