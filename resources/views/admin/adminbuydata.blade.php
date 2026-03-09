@include('admin.head')

@include('admin.header')
@include('admin.sidebar')
<!-- main-content -->
<div class="main-content app-content">

    <!-- main-header -->
    <div class="main-header sticky side-header nav nav-item">
    <div class="container-fluid">
    <div class="app-sidebar__toggle mobile-toggle" data-toggle="sidebar">
                                <a class="open-toggle" href="#"><i class="header-icons" data-eva="menu-outline"></i></a>
                                <a class="close-toggle" href="#"><i class="header-icons" data-eva="close-outline"></i></a>
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
        <div class="left-content">
            <div class="d-flex">
                <i class="fas fa-donate text-muted hover-cursor"></i>
                <p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;Buy Data&nbsp;</p>
                
            </div>
        </div>
        
    </div>
    <!-- /breadcrumb -->
    
    
    {{-- <marquee behavior="" direction=""><h3>NOTE: Do not fill any information on the TRANSACTION ID field</h3></marquee> --}}

    <!-- row -->
    <div class="row row-sm">
    <div class="col-xl-6 col-lg-6 col-md-12">
            <div class="card crypto crypt-primary overflow-hidden">
                <div class="card-body iconfont text-left">
                    <div class="media">
                        <div class="coin-logo bg-primary-transparent">
                            <i class="fas fa-donate text-warning"></i>
                        </div>
                        <div class="media-body">
                            <h3>Buy Data</h3>
                        </div>
                        
                    </div>
                    <div class="flot-wrapper">
                        <div class="flot-chart ht-150  mt-0" id="flotChart5"></div>
                    </div>
                </div>
                
            </div>
        </div>
       
    </div>
    <!-- /row -->
    <div class="col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    
                                    
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12 col-xl-12 mx-auto d-block">
                                            <div class="card card-body pd-10 pd-md-20 border shadow-none">
                                                <h5 class="card-title mg-b-20">Recharge Data</h5>
                                                <form action="{{ route('adminbuydata') }}" method="post">
                                                @csrf
                                               
                                                <div class="form-group">
                                                    <label class="main-content-label tx-11 tx-medium tx-gray-600">PHONE NUMBER</label> <input class="form-control" name="phoneNumber" type="number" id="username">
                                                </div>
                                                 <div class="form-group">
                                                    <label class="main-content-label tx-11 tx-medium tx-gray-600">NETWORK</label> 
                                                    <select class="form-control" name="network" id="network" onChange="update()">
                                                        <option value="">Select Package</option>
                                                        <option value="mtn">MTN</option>
                                                        <option value="airtel">AIRTEL</option>
                                                        <option value="glo">GLO</option>
                                                        <option value="9mobile">9MOBILE</option>
                                                    </select>
                                                 </div>
                                                 <div class="form-group"style="display: none;" id="mtn">
                                                    <label class="form-label col-form-label">Select Package</label>
                                                   
                                                        <select class="form-control" name="packageMTN" id="mtnData" onChange="insertAmount()">
                                                            <option value="mtn_75mb_24hrs">MTN 75MB 24hrs</option>
                                                            <option value="mtn_1gb_24hrs">MTN 1GB 24hrs</option>
                                                            <option value="mtn_200mb_2days">MTN 200MB 2days</option>
                                                            <option value="mtn_2_5gb_2days">MTN 2.5GB 2days</option>
                                                            <option value="mtn_350mb_7days">MTN 350MB 7days</option>
                                                            <option value="mtn_1gb_7days">MTN 1GB 7Days</option>
                                                            <option value="mtn_6gb_7_days">MTN 6GB 7 days</option>
                                                            <option value="mtn_750mb_14days">MTN 750MB 14days</option>
                                                            <option value="mtn_1_5gb_30days">MTN 1.5GB 30days</option>
                                                            <option value="mtn_2gb_30_days">MTN 2GB 30 Days</option>
                                                            <option value="mtn_3gb_30days">MTN 3GB 30days</option>
                                                            <option value="mtn_4_5gb_30days">MTN 4.5GB 30days</option>
                                                            <option value="mtn_6gb_30days">MTN 6GB 30days</option>
                                                            <option value="mtn_8gb_30days">MTN 8GB 30days</option>
                                                            <option value="mtn_10gb_30days">MTN 10GB 30days</option>
                                                            <option value="mtn_15gb_30_days">MTN 15GB 30 Days</option>
                                                            <option value="mtn_20gb_30_days">MTN 20GB 30 Days</option>
                                                            <option value="mtn_40gb">MTN 40GB 30 Days</option>
                                                            <option value="mtn_75gb_30days">MTN 75GB 30days</option>
                                                            <option value="mtn_110gb_30days">MTN 110GB 30days </option>
                                                            <option value="mtn_75gb_60days">MTN 75GB 60days</option>
                                                            <option value="mtn_120gb_60days">MTN 120GB 60Days</option>
                                                            <option value="mtn_150gb_90_days">MTN 150GB 90 Days</option>
                                                            <option value="mtn_250gb_90days">MTN 250GB 90days</option>
                                                            <option value="mtn_400gb_365days">MTN 400GB 365days</option>
                                                            <option value="mtn_1000gb_365days">MTN 1000GB 365days</option>
                                                            <option value="mtn_2000gb_365days">MTN 2000GB 365days</option>
                                                        </select>
                                                 
                                                </div>
                                                <div class="form-group" style="display: none;" id="airtel">
                                                    <label class="form-label col-form-label ">Select Package</label>
                                                        <select class="form-control" name="packageAirtel" id="airtelData" onChange="insertAAmount()">
                                                            <option value="airtel_75mb10_extra_24hrs"> Airtel 75MB+10% Extra 24hrs </option>
                                                            <option value="airtel_1gb__1day">Airtel 1GB 1day</option>
                                                            <option value="airtel_2gb__2days"> Airtel 2GB 2Days</option>
                                                            <option value="airtel_200mb_3days">Airtel 200MB 3Days </option>
                                                            <option value="airtel_350mb__10_extra_7days">Airtel 350MB + 10% Extra 7days</option>
                                                            <option value="airtel_6gb_7days">Airtel 6GB 7Days </option>
                                                            <option value="airtel_750mb">Airtel 750MB </option>
                                                            <option value="airtel_1_5gb">Airtel 1.5GB</option>
                                                            <option value="airtel_2gb_30days">Airtel 2GB 30days </option>
                                                            <option value="airtel_3gb__30days"> Airtel 3GB 30days </option>
                                                            <option value="airtel_4_5gb_30days">Airtel 4.5GB 30days</option>
                                                            <option value="airtel_6gb__30days">Airtel 6GB 30days </option>
                                                            <option value="airtel_8gb_30days">Airtel 8GB 30days </option>
                                                            <option value="airtel_11gb_30days">Airtel 11GB 30days </option>
                                                            <option value="airtel_15gb">Airtel 15GB  30days </option>
                                                            <option value="airtel_40gb_30days">Airtel 40GB 30days</option>
                                                            <option value="airtel_75gb_30days">Airtel 75GB 30days</option>
                                                            <option value="airtel_110gb_30days">Airtel 110GB 30days</option>
                                                        </select>
                                                   
                                                </div>
                                                <div class="form-group" style="display: none;" id="glo">
                                                    <label class="form-label col-form-label ">Select Package</label>
                                                    
                                                        <select class="form-control" name="packageGLO" id="gloData" onChange="insertGAmount()">
                                                            <option value="glo_100mb_1_day">GLO 100MB 1 Day</option>
                                                            <option value="glo_350mb_2_days">GLO 350MB 2 DAYS</option>
                                                            <option value="glo_1_35gb_14days">GLO 1.35GB 14Days</option>
                                                            <option value="glo_2_5gb">GLO 2.5GB</option>
                                                            <option value="glo_3_75gb">GLO 3.75GB</option>
                                                            <option value="glo_5_8_gb">GLO 5.8 GB</option>
                                                            <option value="glo_7_7_gb">GLO 7.7 GB</option>
                                                            <option value="glo_10gb">GLO 10GB</option>
                                                            <option value="glo_13_5_gb">GLO 13.5 GB</option>
                                                            <option value="glo_1825gb">GLO 18.25GB</option>
                                                            <option value="glo_259gb">GLO 29.05GB</option>
                                                            <option value="glo_50gb">GLO 50GB</option>
                                                            <option value="glo_93gb">GLO 93GB</option>
                                                            <option value="glo_119gb">GLO 119GB</option>
                                                            <option value="glo_138gb">GLO 138GB</option>
                                            
                                                        </select>
                                                    
                                                </div>
                                                
                                                <div class="form-group" style="display: none;" id="9mobile">
                                                    <label class="form-label col-form-label ">Select Package</label>
                                                   
                                                        <select class="form-control" name="package9Mobile" id="9mobileData" onChange="insertMAmount()">
                                                            <option value="9mobile_100mb_24hrs">9Mobile 100MB 24hrs </option>
                                                            <option value="9mobile_650mb_24hrs">9Mobile 650MB 24hrs </option>
                                                            <option value="9mobile_7gb_7_days">9Mobile 7GB 7 Days </option>
                                                            <option value="9mobile_500mb_30days">9Mobile 500MB 30Days </option>
                                                            <option value="9mobile_1_5gb_30_days">9Mobile 1.5GB 30 Days</option>
                                                            <option value="9mobile_2gb_30days">9Mobile 2gb 30days  </option>
                                                            <option value="9mobile_4_5gb_30_days">9Mobile 4.5GB 30 Days </option>
                                                            <option value="9mobile_11gb_30days">9Mobile 11GB 30days  </option>
                                                            <option value="9mobile_15gb_30days">9Mobile 15GB 30Days </option>
                                                            <option value="9mobile_40_gb_30_days">9Mobile 40GB 30Days  </option>
                                                            <option value="9mobile_75_gb_30_days">9Mobile 75 GB 30 Days  </option>
                                                            <option value="9mobile_30gb_90_days">9Mobile 30GB 90 Days  </option>
                                                            <option value="9mobile_100gb_100_days">9Mobile 100GB 100 Days  </option>
                                                            <option value="9mobile_60gb_180_days">9Mobile 60GB 180 Days </option>
                                                            <option value="9mobile_120gb_365_days">9Mobile 120GB 365 Days </option>
                                                        </select>
                                                    
                                                </div>

                                                <div class="form-group" id="amount" style="display: none;">
                                                    <label class="form-label col-form-label ">Amount</label>
                                                    
                                                        <input class="form-control" type="text" id="amountV" name ="amount" value="100" placeholder="Enter Amount" required />
    
                                                </div>

                                                <button class="btn btn-main-primary btn-block" name="sub" type="submit">Buy</button>
                                                </form>
                                            </div>
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
    </div>
    </div>
@include('admin.footer')