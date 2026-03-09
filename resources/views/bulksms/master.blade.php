
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from seantheme.com/color-admin/admin/html/index_v2.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 20 Jun 2022 07:45:42 GMT -->
<head>
<meta charset="utf-8" />
<title>AboveMarts Bulk SMS</title>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
<meta content="" name="description" />
<meta content="" name="author" />
<meta name="csrf-token" content="{{ csrf_token() }}">


<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
<link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/css/default/app.min.css') }}" rel="stylesheet" />


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.min.css"
integrity="sha256-VJuwjrIWHWsPSEvQV4DiPfnZi7axOaiWwKfXaJnR5tA=" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.all.min.js"></script>

<link href="{{ asset("assets/plugins/jvectormap-next/jquery-jvectormap.css") }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/gritter/css/jquery.gritter.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/nvd3/build/nv.d3.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/simple-calendar/dist/simple-calendar.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- plugins -->
    <link rel="stylesheet" href="{{ asset('css/plugins.css') }}" />

    <!-- search css -->
    <link rel="stylesheet" href="{{ asset('search/search.css') }}" />

    <!-- quform css -->
    <link rel="stylesheet" href="{{ asset('quform/css/base.css') }}" />

    <!-- theme core css -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    @yield('header')

</head>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/64a09bce94cf5d49dc60f700/1h49m183c';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <!--End of Tawk.to Script-->
<body>

    {{-- <div id="loader" class="app-loader">
        <span class="spinner"></span>
    </div> --}}
    <body>

        
        
        <div id="app" class="app app-header-fixed app-sidebar-fixed">
        
        <div id="header" class="app-header">
        
        <div class="navbar-header">
        <a href="{{ route('dashboard') }}" class="navbar-brand"><span class="navbar-logo"></span> <b>Above</b> Marts</a>
        <button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        </button>
        </div>
        
        
        <div class="navbar-nav">
        <div class="navbar-item navbar-form">
        {{-- <form action="#" method="POST" name="search">
        <div class="form-group">
        <input type="text" class="form-control" placeholder="Enter keyword" />
        <button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
        </div>
        </form> --}}
        </div>
        
        <div class="navbar-item navbar-user dropdown">
        <a href="#" class="navbar-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
        <img src="{{ auth()->user()->photo }}" alt="" />
        <span>
        <span class="d-none d-md-inline">{{ auth()->user()->username }}</span>
        <b class="caret"></b>
        </span>
        </a>
        <div class="dropdown-menu dropdown-menu-end me-1">
        <a href="{{ route('profile') }}" class="dropdown-item">Edit Profile</a>
        <div class="dropdown-divider"></div>
        <a href="{{ route('logout') }}" class="dropdown-item">Log Out</a>
        </div>
        </div>
        </div>
        
        </div>
        

    @include('user.sidebar')
    <div class="app-sidebar-bg"></div>
<div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>


        @yield('content')


    </div>


<script src="assets/js/vendor.min.js" type="df5f205cfe6643099cf45599-text/javascript"></script>
<script src="assets/js/app.min.js" type="df5f205cfe6643099cf45599-text/javascript"></script>
<script src="assets/js/main.js" type="text/javascript"></script>
    
@include('sweetalert::alert')
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false" type="df5f205cfe6643099cf45599-text/javascript"></script>
<script src="assets/plugins/superbox/jquery.superbox.min.js" type="df5f205cfe6643099cf45599-text/javascript"></script>
<script src="assets/plugins/lity/dist/lity.min.js" type="df5f205cfe6643099cf45599-text/javascript"></script>
<script src="assets/js/demo/profile.demo.js" type="df5f205cfe6643099cf45599-text/javascript"></script>
<script type="df5f205cfe6643099cf45599-text/javascript">
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-53034621-1', 'auto');
    ga('send', 'pageview');

</script>
<script src="cdn-cgi/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js" data-cf-settings="df5f205cfe6643099cf45599-|49" defer=""></script><script defer src="https://static.cloudflareinsights.com/beacon.min.js/v652eace1692a40cfa3763df669d7439c1639079717194" integrity="sha512-Gi7xpJR8tSkrpF7aordPZQlW2DLtzUlZcumS8dMQjwDHEnw9I7ZLyiOj/6tZStRBGtGgN6ceN6cMH8z7etPGlw==" data-cf-beacon='{"rayId":"71e2e3eb6cb041bc","version":"2022.6.0","r":1,"token":"4db8c6ef997743fda032d4f73cfeff63","si":100}' crossorigin="anonymous"></script>
 <!-- Global site tag (gtag.js) - Google Analytics -->
 <script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
<!--===============================================================================================-->
    
    <script src="{{ asset('bulkasset/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
    <script src="{{ asset('bulkasset/vendor/bootstrap/js/popper.js')}}"></script>    
    <script src="{{ asset('bulkasset/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
    <script>

        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-23581568-13');
        function updateHiddenInput() {
            var numbersArray = [];
            $('.appended-number').each(function() {
                var number = $(this).text().trim().replace('X', ''); // Remove 'X' button
                numbersArray.push(number);
            });
            $('#appendedNumbersInput').val(JSON.stringify(numbersArray));
        }
        @if (session('message'))
                Swal.fire({
                        icon: 'success',
                        title: '{{ session("message") }}'
                        }) 
           
        @endif

        @if (session('success'))
                Swal.fire({
                        icon: 'success',
                        title: '{{ session("message") }}'
                        }) 
           
        @endif

        @if (session('error'))
                Swal.fire({
                        icon: 'error',
                        title: '{{ session("error") }}'
                        }) 
           
        @endif

    $('#contact_field').on('input', function(e) {
        // Get the input value
        var page = parseInt($("#pages").text())
        var recipient = parseInt($("#no_of_recipients").text())
        console.log(page, recipient, 'coole')
        //charge is the amount set by the admin to be charged per each transactions
        var charge = 4
        $("#amount_field").val(page * recipient * charge )
        $("#amount").text(page * recipient * charge)
        //start copy
        let inputText = e.target.value;
        // var inputText = $(this).val();

        // Remove all characters that are not numbers, spaces, or commas
        inputText = inputText.replace(/[^0-9,\n ]/g, ''); // Allow numbers, commas, spaces, and line breaks

        // Replace line breaks and spaces with commas
        inputText = inputText.replace(/[\n ]+/g, ',');

        // Remove consecutive commas
        inputText = inputText.replace(/,+/g, ',');

        // Remove leading/trailing commas
        // inputText = inputText.replace(/^,|,$/g, '');

        // Update the input value with the modified text
        e.target.value = inputText;

        // Split the input by commas
        var phoneNumbers = inputText.split(',');

        // Remove any leading/trailing whitespace from each phone number
        phoneNumbers = phoneNumbers.map(function(number) {
          return number.trim();
        });

        // Filter out any empty strings
        phoneNumbers = phoneNumbers.filter(function(number) {
          return number !== "";
        });

        // Update the count
        $("#no_of_recipients").text(phoneNumbers.length);
        //end copy

    });


    
        // Function to sanitize input and allow only numbers and commas
        function sanitizeInput(input) {
      
            var sanitized = input.replace(/[^0-9,]/g, '');
            return sanitized;
        }
    </script>
    @yield('script')
</body>

<!-- Mirrored from seantheme.com/color-admin/admin/html/form_elements.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 17 Oct 2023 08:25:57 GMT -->

</html>