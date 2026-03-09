<!DOCTYPE html>
<html lang="en">

<head>
    <title>Above Mart Bulk SMS</title>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/icons/favicon.ico" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('bulkasset/vendor/bootstrap/css/bootstrap.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('bulkasset/vendor/animate/animate.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('bulkasset/vendor/css-hamburgers/hamburgers.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('bulkasset/vendor/select2/select2.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('bulkasset/css/util.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('bulkasset/css/main.css')}}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&family=Roboto:wght@300&family=Ubuntu&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.min.css"
        integrity="sha256-VJuwjrIWHWsPSEvQV4DiPfnZi7axOaiWwKfXaJnR5tA=" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.all.min.js"></script>
    <!--===============================================================================================-->
    @yield('header')
</head>

<body style="font-family: 'Lato', sans-serif;">

    <div class="contact1">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Above Mart</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="/home">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact_group">Contact Groups</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/transactions">Transactions</a>
                    </li>
                </ul>
            </div>
        </nav>
        @yield('content')
    </div>


    <style>
        /* Style for the container div */
        #output-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        /* Style for each appended number div */
        .appended-number {
            background-color: #f2f2f2;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px 10px;
            position: relative;
            display: inline-flex;
            align-items: center;
        }

        /* Style for the "X" button */
        .remove-number {
            background-color: #ff6b6b;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            padding: 4px 6px;
            font-size: 12px;
            line-height: 1;
            margin-left: 5px;
            position: absolute;
            top: 0;
            right: 0;
        }

        /* Style for the input field */
        #contact_field {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>

    <!--===============================================================================================-->
    <script src="{{ asset('bulkasset/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('bulkasset/vendor/bootstrap/js/popper.js')}}"></script>
    <script src="{{ asset('bulkasset/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('bulkasset/vendor/select2/select2.min.js')}}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('bulkasset/vendor/tilt/tilt.jquery.min.js')}}"></script>
    <script>
        $('.js-tilt').tilt({
			scale: 1.1
		})
    </script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
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

    <!--===============================================================================================-->
    <script src="{{ asset('bulkasset/js/main.js')}}"></script>
    @yield('script')
</body>

</html>