@include('user.head')
@include('user.header')
@include('user.sidebar')
<div class="app-sidebar-bg"></div>
<div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a>
</div>

 <style>
        .container {
            display: flex;
            align-items: center;
        }

        input {
            flex: 1;
            margin-right: 10px;
        }

        button {
            padding: 10px;
        }
    </style>
<div id="content" class="app-content">

  <div class='d-flex'>
        <div class='col'>
    <!--<ol class="breadcrumb float-xl-end">-->
    <!--    <li class="breadcrumb-item"><a href="javascript:;">Dashboard</a></li>-->
    <!--</ol>-->


    <h1 class="page-header">Dashboard </h1>
    
  
    @if (auth()->user()->package == 'Basic')
        <a href="{{ route('userpackage') }}">
            <button class="btn btn-danger mb-4">PARTNER UPGRADE</button>
        </a>
    @else
     <button class="btn btn-danger mb-4"  style="text-transform: uppercase;">{{auth()->user()->rank}}</button>
    @endif
    </div>
    <div class='col'>
       <div> <b>Wallet Balance : </b>₦ {{ number_format($capital - $expenses, 2) }}</div>
        <div><b>Total Earnings : </b>₦ {{ number_format($bonusamount, 2) }} </div>
    </div>
    
    </div>
    <div class='alert alert-primary' style='border:2px dashed #004085;'>
        <span style='text-align:center'>Download our mobile app today for a better Xperience. <a target='_blank' href='https://drive.google.com/file/d/1S5E1vgQa952mNGgT-HiCIypWdEE5DBrE/view' style='cursor:pointer;color:red' style='color:red'> Download Now! </a></span>
       
    </div>
    <!--//This is where you comment and uncomment daily task-->
    <!--//Daily Task Start Here-->
      <!--<div style='font-size:17px; font-weight:300; border-top:10px solid #856404;' class='alert alert-warning'>-->
                   
      <!--           <h1>Task Of The Week</h1>-->
      <!--           <ul>-->
      <!--               <li>Refer three(3) people using your <a href='/teammembers'>referral link.</a></li>-->
      <!--               <li>Price : 1GB of data</li>-->
      <!--               <li>Duration 7Days</li>-->
      <!--           </ul>-->
      <!--           <i>After completing the task,  <a href="https://wa.me/2348188731239?text=Hi,%20please%20confirm%20my%20task%20completion%20on%20Abovemarts.">Click here</a> -->
      <!--               to claim prize.-->
      <!--           </i>-->
                  
      <!--  </div>-->
    <!--//Daily Task Ends Here-->
    <!--//Invite $ Earn Starts Here-->
    <div class='alert alert-success' style='border:2px dotted #155724;'>
        <h4>Invite & Earn</h4>
        Refer your Family & Friends to Earn Residual Bonus & Points. <br> Receive from <b>#10k</b> to <b>#50M</b> FREE Cash Grants!
       <!--Refer a friend and receive #1,000 Bonus for every 5 Activations! Upgrade & Earn up to 50% Cashbacks & Commissions.-->
        
        <div class="container">
        <input type="text" id="referralCode" class='form form-control form-control-sm' value="{{ Route('register', ['ref' => auth()->user()->mySponsorId]) }}" readonly>
        <button id='referalButton' class='btn btn-success' onclick="copyReferralCode()"><i class='fa fa-copy'></i></button>
    </div>
    </div>
     <!--//Invite & Earn Ends Here-->

    <div class="row">

        <div class="col-xl-3 col-md-6 col-6">
            
            
            <a href="{{ route('fund') }}">

                <div style='border-left:5px solid #155724;background:#d4edda' class="widget widget-stats">
                    
                    <div class="stats-icon stats-icon-lg">
                        <i class="fa fa-wallet fa-fw"></i>
                        </div>
                    <div class="stats-content">
                        <div class="stats-title text-black" style='color:#155724'>
                              <span class="svg-icon svg-icon-primary svg-icon-2x">
                                    <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo8/dist/../src/media/svg/icons/Shopping/Credit-card.svg--><svg
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <rect fill="#000000" opacity="0.3" x="2" y="5" width="20" height="14"
                                                rx="2" />
                                            <rect fill="#000000" x="2" y="8" width="20" height="3" />
                                            <rect fill="#000000" opacity="0.3" x="16" y="14" width="4" height="2"
                                                rx="1" />
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon-->
                                </span>
                            FUND WALLET</div>

                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 col-6">
            <a href="{{ route('userpackage') }}">

                <div style='border-left:5px solid #004085;background:#cce5ff' class="widget widget-stats">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-archive fa-fw"></i></div>
                    <div class="stats-content">
                        <div class="stats-title" style='color:#004085'>
                              <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo2/dist/../src/media/svg/icons/Layout/Layout-horizontal.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <rect fill="#000000" opacity="0.3" x="4" y="5" width="16" height="6" rx="1.5"/>
                                        <rect fill="#000000" x="4" y="13" width="16" height="6" rx="1.5"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                            BUY PACKAGE</div>

                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 col-6">
            <a href="{{ route('rechargepurchase') }}">

                <div style='border-left:5px solid #383d41;background:#e2e3e5' class="widget widget-stats">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-archive fa-fw"></i></div>
                    <div class="stats-content">
                        <div class="stats-title" style='color:#383d41'>
                             <span class="svg-icon svg-icon-primary svg-icon-2x">
                                    <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo8/dist/../src/media/svg/icons/Communication/Active-call.svg--><svg
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <path
                                                d="M13.0799676,14.7839934 L15.2839934,12.5799676 C15.8927139,11.9712471 16.0436229,11.0413042 15.6586342,10.2713269 L15.5337539,10.0215663 C15.1487653,9.25158901 15.2996742,8.3216461 15.9083948,7.71292558 L18.6411989,4.98012149 C18.836461,4.78485934 19.1530435,4.78485934 19.3483056,4.98012149 C19.3863063,5.01812215 19.4179321,5.06200062 19.4419658,5.11006808 L20.5459415,7.31801948 C21.3904962,9.0071287 21.0594452,11.0471565 19.7240871,12.3825146 L13.7252616,18.3813401 C12.2717221,19.8348796 10.1217008,20.3424308 8.17157288,19.6923882 L5.75709327,18.8875616 C5.49512161,18.8002377 5.35354162,18.5170777 5.4408655,18.2551061 C5.46541191,18.1814669 5.50676633,18.114554 5.56165376,18.0596666 L8.21292558,15.4083948 C8.8216461,14.7996742 9.75158901,14.6487653 10.5215663,15.0337539 L10.7713269,15.1586342 C11.5413042,15.5436229 12.4712471,15.3927139 13.0799676,14.7839934 Z"
                                                fill="#000000" />
                                            <path
                                                d="M14.1480759,6.00715131 L13.9566988,7.99797396 C12.4781389,7.8558405 11.0097207,8.36895892 9.93933983,9.43933983 C8.8724631,10.5062166 8.35911588,11.9685602 8.49664195,13.4426352 L6.50528978,13.6284215 C6.31304559,11.5678496 7.03283934,9.51741319 8.52512627,8.02512627 C10.0223249,6.52792766 12.0812426,5.80846733 14.1480759,6.00715131 Z M14.4980938,2.02230302 L14.313049,4.01372424 C11.6618299,3.76737046 9.03000738,4.69181803 7.1109127,6.6109127 C5.19447112,8.52735429 4.26985715,11.1545872 4.51274152,13.802405 L2.52110319,13.985098 C2.22450978,10.7517681 3.35562581,7.53777247 5.69669914,5.19669914 C8.04101739,2.85238089 11.2606138,1.72147333 14.4980938,2.02230302 Z"
                                                fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon-->
                                </span>
                            
                            BUY AIRTIME</div>

                    </div>
                </div>
            </a>
        </div>



        <div class="col-xl-3 col-md-6 col-6">
            <a href="{{ route('datashare') }}">

                <div style='border-left:5px solid #856404;background:#fff3cd' class="widget widget-stats">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-archive fa-fw"></i></div>
                    <div class="stats-content">
                        <div class="stats-title" style='color:#856404'>
                             <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo8/dist/../src/media/svg/icons/Devices/LTE2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M16.4508979,17.4029496 L15.1784978,15.8599014 C16.324501,14.9149052 17,13.5137472 17,12 C17,10.4912085 16.3289582,9.09418404 15.1893841,8.14910121 L16.466112,6.60963188 C18.0590936,7.93073905 19,9.88958759 19,12 C19,14.1173586 18.0528606,16.0819686 16.4508979,17.4029496 Z M19.0211112,20.4681628 L17.7438102,18.929169 C19.7927036,17.2286725 21,14.7140097 21,12 C21,9.28974232 19.7960666,6.77820732 17.7520315,5.07766256 L19.031149,3.54017812 C21.5271817,5.61676443 23,8.68922234 23,12 C23,15.3153667 21.523074,18.3916375 19.0211112,20.4681628 Z M7.54910207,17.4029496 C5.94713944,16.0819686 5,14.1173586 5,12 C5,9.88958759 5.94090645,7.93073905 7.53388797,6.60963188 L8.81061588,8.14910121 C7.67104182,9.09418404 7,10.4912085 7,12 C7,13.5137472 7.67549895,14.9149052 8.82150222,15.8599014 L7.54910207,17.4029496 Z M4.9788888,20.4681628 C2.47692603,18.3916375 1,15.3153667 1,12 C1,8.68922234 2.47281829,5.61676443 4.96885102,3.54017812 L6.24796852,5.07766256 C4.20393339,6.77820732 3,9.28974232 3,12 C3,14.7140097 4.20729644,17.2286725 6.25618985,18.929169 L4.9788888,20.4681628 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                        <path d="M11,14.2919782 C10.1170476,13.9061998 9.5,13.0251595 9.5,12 C9.5,10.6192881 10.6192881,9.5 12,9.5 C13.3807119,9.5 14.5,10.6192881 14.5,12 C14.5,13.0251595 13.8829524,13.9061998 13,14.2919782 L13,20 C13,20.5522847 12.5522847,21 12,21 C11.4477153,21 11,20.5522847 11,20 L11,14.2919782 Z" fill="#000000"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                            BUY DATA</div>

                    </div>
                </div>
            </a>
        </div>


        <div class="col-xl-3 col-md-6 col-6">
            <a href="{{ route('lightpurchase') }}">

                <div style='border-left:5px solid #993366;background:#f9ebf4' class="widget widget-stats">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-archive fa-fw"></i></div>
                    <div class="stats-content">
                        <div class="stats-title" style='color:#721c24'>
                             <span class="svg-icon svg-icon-primary svg-icon-2x">
                                    <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo8/dist/../src/media/svg/icons/Electric/Highvoltage.svg--><svg
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <path
                                                d="M2.76702366,20 C2.59202225,20 2.4200849,19.9540749 2.2683913,19.8668136 C1.78966338,19.5914265 1.62482304,18.9800956 1.90021009,18.5013676 L11.1332403,2.45083309 C11.221302,2.29774818 11.3483346,2.17071522 11.5014193,2.08265312 C11.9801465,1.80726488 12.5914779,1.97210369 12.8668662,2.45083092 L22.0999499,18.5013655 C22.187212,18.6530596 22.2331375,18.8249977 22.2331375,19 C22.2331375,19.5522847 21.7854223,20 21.2331375,20 L2.76702366,20 Z M11,18 L15,12 L12.9444444,12 L12.9444444,8 L9,14 L11,14 L11,18 Z"
                                                fill="#000000" />
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon-->
                                </span>
                            BUY ELECTRICITY</div>

                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 col-6">
            <a href="{{ route('tvsub') }}">

                <div style='border-left:5px solid #4a235a;background:#f5eef8' class="widget widget-stats">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-archive fa-fw"></i></div>
                    <div class="stats-content">
                        <div class="stats-title" style='color:#4a235a'>
                             <span class="svg-icon svg-icon-primary svg-icon-2x">
                                    <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo8/dist/../src/media/svg/icons/Devices/TV2.svg--><svg
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <path
                                                d="M3,5 L21,5 C21.5522847,5 22,5.44771525 22,6 L22,17 C22,17.5522847 21.5522847,18 21,18 L3,18 C2.44771525,18 2,17.5522847 2,17 L2,6 C2,5.44771525 2.44771525,5 3,5 Z M9.632,10.066 L11.032,10.066 L11.032,9.044 L7.035,9.044 L7.035,10.066 L8.435,10.066 L8.435,14 L9.632,14 L9.632,10.066 Z M14.935,14 L16.846,9.044 L15.523,9.044 L14.382,12.558 L14.354,12.558 L13.206,9.044 L11.862,9.044 L13.738,14 L14.935,14 Z"
                                                fill="#000000" />
                                            <rect fill="#000000" opacity="0.3" x="3" y="19" width="18" height="1"
                                                rx="0.5" />
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon-->
                                </span>
                            BUY CABLE TV</div>

                    </div>
                </div>
            </a>
        </div>
    @if (auth()->user()->package == 'Basic')
            <div class="col-xl-3 col-md-6 col-6">
            <a href="{{ route('samplecards') }}">
                <div style='border-left:5px solid #3b5998;background:#dfe3ee' class="widget widget-stats">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-archive fa-fw"></i></div>
                    <div class="stats-content">
                        <div class="stats-title" style='color:#3b5998'>
                             <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo2/dist/../src/media/svg/icons/Shopping/ATM.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <rect fill="#000000" opacity="0.3" x="2" y="4" width="20" height="5" rx="1"/>
                                        <path d="M5,7 L8,7 L8,21 L7,21 C5.8954305,21 5,20.1045695 5,19 L5,7 Z M19,7 L19,19 C19,20.1045695 18.1045695,21 17,21 L11,21 L11,7 L19,7 Z" fill="#000000"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                            PRINT RECHARGE</div>

                    </div>
                </div>
            </a>
        </div>

    @else
        <div class="col-xl-3 col-md-6 col-6">
            <a href="{{ route('rechargeprinting') }}">
                <div style='border-left:5px solid #3b5998;background:#dfe3ee' class="widget widget-stats">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-archive fa-fw"></i></div>
                    <div class="stats-content">
                        <div class="stats-title" style='color:#3b5998'>
                             <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo2/dist/../src/media/svg/icons/Shopping/ATM.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <rect fill="#000000" opacity="0.3" x="2" y="4" width="20" height="5" rx="1"/>
                                        <path d="M5,7 L8,7 L8,21 L7,21 C5.8954305,21 5,20.1045695 5,19 L5,7 Z M19,7 L19,19 C19,20.1045695 18.1045695,21 17,21 L11,21 L11,7 L19,7 Z" fill="#000000"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                            PRINT RECHARGE</div>

                    </div>
                </div>
            </a>
        </div>
    @endif
        
        <div class="col-xl-3 col-md-6 col-6">
            <a href="{{ route('smshome') }}">
                <div style='border-left:5px solid #6c3483;background:#f5eef8' class="widget widget-stats">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-archive fa-fw"></i></div>
                    <div class="stats-content">
                        <div class="stats-title" style='color:#6c3483'>
                              <span class="svg-icon svg-icon-primary svg-icon-2x">
                                    <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo8/dist/../src/media/svg/icons/Communication/Clipboard-list.svg--><svg
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <path
                                                d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z"
                                                fill="#000000" opacity="0.3" />
                                            <path
                                                d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z"
                                                fill="#000000" />
                                            <rect fill="#000000" opacity="0.3" x="10" y="9" width="7" height="2"
                                                rx="1" />
                                            <rect fill="#000000" opacity="0.3" x="7" y="9" width="2" height="2"
                                                rx="1" />
                                            <rect fill="#000000" opacity="0.3" x="7" y="13" width="2" height="2"
                                                rx="1" />
                                            <rect fill="#000000" opacity="0.3" x="10" y="13" width="7" height="2"
                                                rx="1" />
                                            <rect fill="#000000" opacity="0.3" x="7" y="17" width="2" height="2"
                                                rx="1" />
                                            <rect fill="#000000" opacity="0.3" x="10" y="17" width="7" height="2"
                                                rx="1" />
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon-->
                                </span>
                            SEND BULKSMS</div>

                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6 col-6">
            <a href="http://shop.abovemarts.com/marketplace">
                <div style='border-left:5px solid #943126;background:#f5eef8' class="widget widget-stats">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-archive fa-fw"></i></div>
                    <div class="stats-content">
                        <div class="stats-title" style='color:#943126'>
                               <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                 <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                     <rect id="bound" x="0" y="0" width="24" height="24"/>
                                     <path d="M18.1446364,11.84388 L17.4471627,16.0287218 C17.4463569,16.0335568 17.4455155,16.0383857 17.4446387,16.0432083 C17.345843,16.5865846 16.8252597,16.9469884 16.2818833,16.8481927 L4.91303792,14.7811299 C4.53842737,14.7130189 4.23500006,14.4380834 4.13039941,14.0719812 L2.30560137,7.68518803 C2.28007524,7.59584656 2.26712532,7.50338343 2.26712532,7.4104669 C2.26712532,6.85818215 2.71484057,6.4104669 3.26712532,6.4104669 L16.9929851,6.4104669 L17.606173,3.78251876 C17.7307772,3.24850086 18.2068633,2.87071314 18.7552257,2.87071314 L20.8200821,2.87071314 C21.4717328,2.87071314 22,3.39898039 22,4.05063106 C22,4.70228173 21.4717328,5.23054898 20.8200821,5.23054898 L19.6915238,5.23054898 L18.1446364,11.84388 Z" id="Combined-Shape" fill="#000000" opacity="0.3"/>
                                     <path d="M6.5,21 C5.67157288,21 5,20.3284271 5,19.5 C5,18.6715729 5.67157288,18 6.5,18 C7.32842712,18 8,18.6715729 8,19.5 C8,20.3284271 7.32842712,21 6.5,21 Z M15.5,21 C14.6715729,21 14,20.3284271 14,19.5 C14,18.6715729 14.6715729,18 15.5,18 C16.3284271,18 17,18.6715729 17,19.5 C17,20.3284271 16.3284271,21 15.5,21 Z" id="Combined-Shape" fill="#000000"/>
                                </g>
                            </svg>
                            E-COMMERCE</div>

                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6 col-6">
            <a href="https://learn.abovemarts.com/allebooks">
                <div style='border-left:5px solid #155724;background:#d4edda' class="widget widget-stats">
                    <div class="stats-icon stats-icon-lg"><i class="fa fa-archive fa-fw"></i></div>
                    <div class="stats-content">
                        <div class="stats-title" style='color:#155724'>
                          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <circle id="Combined-Shape" fill="#000000" opacity="0.3" cx="12" cy="9" r="8"/>
        <path d="M14.5297296,11 L9.46184488,11 L11.9758349,17.4645458 L14.5297296,11 Z M10.5679953,19.3624463 L6.53815512,9 L17.4702704,9 L13.3744964,19.3674279 L11.9759405,18.814912 L10.5679953,19.3624463 Z" id="Path-69" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
        <path d="M10,22 L14,22 L14,22 C14,23.1045695 13.1045695,24 12,24 L12,24 C10.8954305,24 10,23.1045695 10,22 Z" id="Rectangle-72-Copy-2" fill="#000000" opacity="0.3"/>
        <path d="M9,20 C8.44771525,20 8,19.5522847 8,19 C8,18.4477153 8.44771525,18 9,18 C8.44771525,18 8,17.5522847 8,17 C8,16.4477153 8.44771525,16 9,16 L15,16 C15.5522847,16 16,16.4477153 16,17 C16,17.5522847 15.5522847,18 15,18 C15.5522847,18 16,18.4477153 16,19 C16,19.5522847 15.5522847,20 15,20 C15.5522847,20 16,20.4477153 16,21 C16,21.5522847 15.5522847,22 15,22 L9,22 C8.44771525,22 8,21.5522847 8,21 C8,20.4477153 8.44771525,20 9,20 Z" id="Combined-Shape" fill="#000000"/>
    </g>
</svg>
                            E-LEARNING</div>

                    </div>
                </div>
            </a>
        </div>
        <!--<div class="col-xl-3 col-md-6 col-6">-->
        <!--    <a href="https://learn.abovemarts.com/allebooks">-->
        <!--        <div class="widget widget-stats  bg-gray-900">-->
        <!--            <div class="stats-icon stats-icon-lg"><i class="fa fa-archive fa-fw"></i></div>-->
        <!--            <div class="stats-content">-->
        <!--                <div class="stats-title">E-LEARNING</div>-->

        <!--            </div>-->
        <!--        </div>-->
        <!--    </a>-->
        <!--</div>-->
        <div class="col-xl-3 col-md-6 col-6">

        </div>
        <div class="col-xl-3 col-md-6 col-6">

        </div>


        <div class="col-xl-4 col-md-6">
            <div style='border-top:5px solid black' class="widget widget-stats bg-teal">
                <div class="stats-icon stats-icon-lg"><i class="fa fa-globe fa-fw"></i></div>
                <div class="stats-content">
                    
                    <div class="stats-title">
                        <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo8/dist/../src/media/svg/icons/Shopping/Wallet.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <circle fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>
        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>
        <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" fill="#000000"/>
    </g>
</svg><!--end::Svg Icon--></span>
                        MY BALANCE</div>
                    <div class="stats-number">₦ {{ number_format($capital - $expenses, 2) }}</div>
                    <!--<div class="stats-progress progress">-->
                    <!--    <div class="progress-bar" style="width: 70.1%;"></div>-->
                    <!--</div>-->
                    <div>
                        <!--  @if(Auth::user()->package == "Bronze" || Auth::user()->package == "Silver" || Auth::user()->package == "Gold" || Auth::user()->package == "Platinum")-->
                
                        <!--@endif-->
                        <a href="https://abovemarts.com/member-transfer" style='color:#fff'>Share Balance <i class="fa fa-arrow-right"></i></a><br>
                        <a href="https://abovemarts.com/deposithistory" style='color:#fff'>Deposit History <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>



        <div class="col-xl-4 col-md-6">
            <div style='border-top:5px solid black' class="widget widget-stats bg-success">
                <div class="stats-icon stats-icon-lg"><i class="fa fa-archive fa-fw"></i></div>
                <div class="stats-content">
                    <div class="stats-title">
                         <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo8/dist/../src/media/svg/icons/Shopping/Wallet.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <circle fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>
        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>
        <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" fill="#000000"/>
    </g>
</svg><!--end::Svg Icon--></span>
                        TOTAL EARNING</div>
                    <div class="stats-number">₦ {{ number_format($bonusamount, 2) }}</div>
                  <div>
                      <a href="https://abovemarts.com/teammembers" style='color:#fff'>My Connections <i class="fa fa-arrow-right"></i></a><br>
                      <a href="https://abovemarts.com/serviceshistory" style='color:#fff'>Purchase History <i class="fa fa-arrow-right"></i></a>
                  </div>
                </div>
            </div>
        </div>


        <div class="col-xl-4 col-md-6">
            <div style='border-top:5px solid black' class="widget widget-stats bg-danger">
                <div class="stats-icon stats-icon-lg"><i class="fa fa-archive fa-fw"></i></div>
                <div class="stats-content">
                    <div class="stats-title">
                        <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo8/dist/../src/media/svg/icons/Shopping/Wallet.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <circle fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>
        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>
        <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" fill="#000000"/>
    </g>
</svg><!--end::Svg Icon--></span>
                       MAXIMUM CASHOUT</div>
                    <div class="stats-number">₦ {{ auth()->user()->expectedEarning + 0 }}</div>
                     <div>
                      <a href="https://abovemarts.com/userpackages"  style='color:#fff'>Buy Package <i class="fa fa-arrow-right"></i></a><br>
                      <a href="https://abovemarts.com/packagehistory" style='color:#fff'>Package History <i class="fa fa-arrow-right"></i></a><br>
                  </div>
                </div>
            </div>
        </div>



        <div class="col-xl-4 col-md-6">
            <div style='border-top:5px solid black' class="widget widget-stats bg-indigo">
                <div class="stats-icon stats-icon-lg"><i class="fa fa-archive fa-fw"></i></div>
                <div class="stats-content">
                    <div class="stats-title">
                        <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo2/dist/../src/media/svg/icons/Shopping/Chart-line1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"/>
        <path d="M8.7295372,14.6839411 C8.35180695,15.0868534 7.71897114,15.1072675 7.31605887,14.7295372 C6.9131466,14.3518069 6.89273254,13.7189711 7.2704628,13.3160589 L11.0204628,9.31605887 C11.3857725,8.92639521 11.9928179,8.89260288 12.3991193,9.23931335 L15.358855,11.7649545 L19.2151172,6.88035571 C19.5573373,6.44687693 20.1861655,6.37289714 20.6196443,6.71511723 C21.0531231,7.05733733 21.1271029,7.68616551 20.7848828,8.11964429 L16.2848828,13.8196443 C15.9333973,14.2648593 15.2823707,14.3288915 14.8508807,13.9606866 L11.8268294,11.3801628 L8.7295372,14.6839411 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
    </g>
</svg><!--end::Svg Icon--></span>
                        MY INCOME</div>
                    <div class="stats-number">₦ {{ number_format($bonusamount - $withdrawbonus, 2) }}</div>
                      <div>
                      <a href="https://abovemarts.com/transfer" style='color:#fff'>Remit Income  <i class="fa fa-arrow-right"></i></a><br>
                      <a href="https://abovemarts.com/bonushistory" style='color:#fff'>Earning History  <i class="fa fa-arrow-right"></i></a>
                  </div>
                </div>
            </div>
        </div>


        <div class="col-xl-4 col-md-6">
            <div style='border-top:5px solid black' class="widget widget-stats bg-success">
                <div class="stats-icon stats-icon-lg"><i class="fa fa-comment-alt fa-fw"></i></div>
                <div class="stats-content">
                    <div class="stats-title">
                        <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo2/dist/../src/media/svg/icons/Communication/Outgoing-box.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M22,17 L22,21 C22,22.1045695 21.1045695,23 20,23 L4,23 C2.8954305,23 2,22.1045695 2,21 L2,17 L6.27924078,17 L6.82339262,18.6324555 C7.09562072,19.4491398 7.8598984,20 8.72075922,20 L15.381966,20 C16.1395101,20 16.8320364,19.5719952 17.1708204,18.8944272 L18.118034,17 L22,17 Z" fill="#000000"/>
        <path d="M2.5625,15 L5.92654389,9.01947752 C6.2807805,8.38972356 6.94714834,8 7.66969497,8 L16.330305,8 C17.0528517,8 17.7192195,8.38972356 18.0734561,9.01947752 L21.4375,15 L18.118034,15 C17.3604899,15 16.6679636,15.4280048 16.3291796,16.1055728 L15.381966,18 L8.72075922,18 L8.17660738,16.3675445 C7.90437928,15.5508602 7.1401016,15 6.27924078,15 L2.5625,15 Z" fill="#000000" opacity="0.3"/>
        <path d="M11.1288761,0.733697713 L11.1288761,2.69017121 L9.12120481,2.69017121 C8.84506244,2.69017121 8.62120481,2.91402884 8.62120481,3.19017121 L8.62120481,4.21346991 C8.62120481,4.48961229 8.84506244,4.71346991 9.12120481,4.71346991 L11.1288761,4.71346991 L11.1288761,6.66994341 C11.1288761,6.94608579 11.3527337,7.16994341 11.6288761,7.16994341 C11.7471877,7.16994341 11.8616664,7.12798964 11.951961,7.05154023 L15.4576222,4.08341738 C15.6683723,3.90498251 15.6945689,3.58948575 15.5161341,3.37873564 C15.4982803,3.35764848 15.4787093,3.33807751 15.4576222,3.32022374 L11.951961,0.352100892 C11.7412109,0.173666017 11.4257142,0.199862688 11.2472793,0.410612793 C11.1708299,0.500907473 11.1288761,0.615386087 11.1288761,0.733697713 Z" fill="#000000" fill-rule="nonzero" transform="translate(11.959697, 3.661508) rotate(-90.000000) translate(-11.959697, -3.661508) "/>
    </g>
</svg><!--end::Svg Icon--></span>
                        MY PAYOUT</div>
                    <div class="stats-number">₦ {{ round($withdrawamount + 0, 2) }}</div>
                   
                     <div>
                         @if(Auth::user()->package == "Bronze" || Auth::user()->package == "Silver" || Auth::user()->package == "Gold" || Auth::user()->package == "Platinum")
                      <a href="https://abovemarts.com/withdraw" style='color:#fff'>Get Payout <i class="fa fa-arrow-right"></i></a><br>
                      <a href="https://abovemarts.com/withdrawhistory" style='color:#fff'>Payout History <i class="fa fa-arrow-right"></i></a><br>
                      
                      @else 
                       <a href="#" style='color:#fff'>Get Payout  <i class="fa fa-arrow-right"></i></a><br>
                      <a href="https://abovemarts.com/withdrawhistory" style='color:#fff'>Payout History <i class="fa fa-arrow-right"></i></a>
                      
                      @endif
                  </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div style='border-top:5px solid black' class="widget widget-stats bg-blue">
                <div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
                <div class="stats-content">
                    <div class="stats-title">
<span class="svg-icon svg-icon-primary svg-icon-2x">
    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M5.3618034,16.2763932 L5.8618034,15.2763932 C5.94649941,15.1070012 6.11963097,15 6.30901699,15 L16.190983,15 C16.4671254,15 16.690983,15.2238576 16.690983,15.5 C16.690983,15.5776225 16.6729105,15.6541791 16.6381966,15.7236068 L16.1381966,16.7236068 C16.0535006,16.8929988 15.880369,17 15.690983,17 L5.80901699,17 C5.53287462,17 5.30901699,16.7761424 5.30901699,16.5 C5.30901699,16.4223775 5.32708954,16.3458209 5.3618034,16.2763932 Z" fill="#000000" opacity="0.3"/>
        <path d="M8,3.716 L13.107,3.716 C14.042338,3.716 14.8856629,3.80033249 15.637,3.969 C16.3883371,4.13766751 17.0323306,4.41366475 17.569,4.797 C18.1056693,5.18033525 18.5196652,5.67099701 18.811,6.269 C19.1023348,6.86700299 19.248,7.58766245 19.248,8.431 C19.248,9.33567119 19.079335,10.0946636 18.742,10.708 C18.404665,11.3213364 17.9485029,11.8158315 17.3735,12.1915 C16.7984971,12.5671685 16.1276705,12.8393325 15.361,13.008 C14.5943295,13.1766675 13.781671,13.261 12.923,13.261 L10.692,13.261 L10.692,20 L8,20 L8,3.716 Z M12.716,10.823 C13.1913357,10.823 13.6436645,10.7885003 14.073,10.7195 C14.5023355,10.6504997 14.885665,10.5278342 15.223,10.3515 C15.560335,10.1751658 15.8286657,9.9336682 16.028,9.627 C16.2273343,9.3203318 16.327,8.92166912 16.327,8.431 C16.327,7.95566429 16.2273343,7.5685015 16.028,7.2695 C15.8286657,6.97049851 15.5641683,6.73666751 15.2345,6.568 C14.9048317,6.39933249 14.5291688,6.28816694 14.1075,6.2345 C13.6858312,6.18083307 13.2526689,6.154 12.808,6.154 L10.692,6.154 L10.692,10.823 L12.716,10.823 Z" fill="#000000"/>
    </g>
</svg><!--end::Svg Icon--></span>
                        REWARD POINT</div>
                    <div class="stats-number">{{ auth()->user()->point }}</div>
                     <div>
                      <a href="https://abovemarts.com/userpackages" style='color:#fff'>Buy Upgrade <i class="fa fa-arrow-right"></i></a><br>
                      <a href="https://abovemarts.com/pointhistory" style='color:#fff'>Point History <i class="fa fa-arrow-right"></i></a>
                  </div>
                </div>
            </div>
        </div>

    </div>
    <!--<div class="row mb-15px">-->
    <!--    <label class="form-label col-form-label col-md-2">Affliate Link</label>-->
    <!--    <div class="col-md-10">-->
    <!--        <input type="text" class="form-control"-->
    <!--            placeholder="{{ Route('register', ['ref' => auth()->user()->mySponsorId]) }}"-->
    <!--            value="{{ Route('register', ['ref' => auth()->user()->mySponsorId]) }}" disabled>-->
    <!--    </div>-->
    <!--</div>-->
    <div class="row">


        {{-- <div class="col-xl-6 col-lg-6">

<div class="panel panel-inverse" data-sortable-id="index-3">
<div class="panel-heading">
<h4 class="panel-title">Today's Schedule</h4>
</div>
<div id="schedule-calendar" class="simple-calendar"></div>
<hr class="m-0 bg-gray-500" />
</div>

</div> --}}

    </div>

    <!--<div class="alert alert-secondary alert-dismissible rounded-0 mb-0 fade show">-->
    <!--    <button type="button" class="btn-close" data-bs-dismiss="alert">-->
    <!--    </button>-->
    <!--    Transaction Activity-->
    <!--</div>-->

    <div class="panel-body">
        <div style='overflow:auto'>
        <table id="data-table-responsive" class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th class="text-nowrap">Transaction ID</th>
                    <th class="text-nowrap">Amount</th>
                    <th class="text-nowrap">Service Number</th>
                    <th class="text-nowrap">Transaction Type</th>
                    <th class="text-nowrap">Status</th>
                    <th class="text-nowrap">Date</th>

                </tr>
            </thead>
            @foreach ($data as $dat)
                <tbody>
                    <tr class="odd gradeX">
                        <td>{{ $dat->transactionId }}</td>
                        <td>{{ $dat->amount }}</td>
                        <td>{{ $dat->phoneNumber }}</td>
                        <td>{{ $dat->transactionType }}</td>
                        <td>{{ $dat->status }}</td>
                        <td>{{ $dat->created_at }}</td>
                    </tr>
                    </tr>
                </tbody>
            @endforeach
        </table>
        </div>
    </div>

</div>

</div>

 <script>
        function copyReferralCode() {
            // Get the input field
            var referralCodeField = document.getElementById("referralCode");

            // Select the text in the input field
            referralCodeField.select();
            referralCodeField.setSelectionRange(0, 99999); /*For mobile devices*/

            // Copy the selected text to the clipboard
            document.execCommand("copy");

            // Deselect the text
            referralCodeField.setSelectionRange(0, 0);

            // Optionally, provide some visual feedback to the user (e.g., changing button text)
            // For simplicity, this example changes the button text briefly
            var copyButton = document.getElementById("referalButton");
            copyButton.textContent = "Copied!";
            setTimeout(function() {
                copyButton.textContent = "Copy";
            }, 1500);
        }
    </script>
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script src="assets/js/vendor.min.js" type="23df6835d8d0c3403c3bd6d9-text/javascript"></script>
<script src="assets/js/app.min.js" type="23df6835d8d0c3403c3bd6d9-text/javascript"></script>


<script src="assets/plugins/d3/d3.min.js" type="23df6835d8d0c3403c3bd6d9-text/javascript"></script>
<script src="assets/plugins/nvd3/build/nv.d3.min.js" type="23df6835d8d0c3403c3bd6d9-text/javascript"></script>
<script src="assets/plugins/jvectormap-next/jquery-jvectormap.min.js" type="23df6835d8d0c3403c3bd6d9-text/javascript"></script>
<script src="assets/plugins/jvectormap-next/jquery-jvectormap-world-mill.js" type="23df6835d8d0c3403c3bd6d9-text/javascript"></script>
<script src="assets/plugins/simple-calendar/dist/jquery.simple-calendar.min.js" type="23df6835d8d0c3403c3bd6d9-text/javascript"></script>
<script src="assets/plugins/gritter/js/jquery.gritter.js" type="23df6835d8d0c3403c3bd6d9-text/javascript"></script>
<script src="assets/js/demo/dashboard-v2.js" type="23df6835d8d0c3403c3bd6d9-text/javascript"></script>

<script type="23df6835d8d0c3403c3bd6d9-text/javascript">
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-53034621-1', 'auto');
		ga('send', 'pageview');

	</script>
<script src="cdn-cgi/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js"
    data-cf-settings="23df6835d8d0c3403c3bd6d9-|49" defer=""></script>
<script defer src="https://static.cloudflareinsights.com/beacon.min.js/v652eace1692a40cfa3763df669d7439c1639079717194"
    integrity="sha512-Gi7xpJR8tSkrpF7aordPZQlW2DLtzUlZcumS8dMQjwDHEnw9I7ZLyiOj/6tZStRBGtGgN6ceN6cMH8z7etPGlw=="
    data-cf-beacon='{"rayId":"71e2e2eb0db741a2","version":"2022.6.0","r":1,"token":"4db8c6ef997743fda032d4f73cfeff63","si":100}'
    crossorigin="anonymous"></script>
</body>

<!-- Mirrored from seantheme.com/color-admin/admin/html/index_v2.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 20 Jun 2022 07:46:58 GMT -->

</html>
<script type="23df6835d8d0c3403c3bd6d9-text/javascript">

</script>
