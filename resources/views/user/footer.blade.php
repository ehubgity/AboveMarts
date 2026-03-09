

<script src="{{asset('assets/js/vendor.min.js')}}" type="df5f205cfe6643099cf45599-text/javascript"></script>
<script src="{{asset('assets/js/app.min.js')}}" type="df5f205cfe6643099cf45599-text/javascript"></script>
<script src="{{ asset('assets/js/main.js') }}?v={{ time() }}" type="text/javascript"></script>
 <!--<script src="js/professionallocker.js"></script>-->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var submitButton = document.getElementById('submit-button');
    var form = document.getElementById('my-form');

    form.addEventListener('submit', function() {
      // Disable the submit button
      submitButton.disabled = true;

      // Enable the submit button after a specific time (1 minute = 60000 milliseconds)
      setTimeout(function() {
        submitButton.disabled = false;
      }, 60000);
    });
  });
  
    // Check if the form submitted flag exists in the session
if ({!! json_encode(session()->has('form_submitted')) !!}) {
  // Display the pop-up message to the user
  alert("Please wait for 1 minute before submitting the form again.");
  
  // Remove the form submitted flag from the session
  {!! json_encode(session()->forget('form_submitted')) !!};
}
</script>

@include('sweetalert::alert')
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false" type="df5f205cfe6643099cf45599-text/javascript"></script>
<script src="{{ asset("assets/plugins/superbox/jquery.superbox.min.js") }}" type="df5f205cfe6643099cf45599-text/javascript"></script>
<script src="{{ asset('assets/plugins/lity/dist/lity.min.js') }}" type="df5f205cfe6643099cf45599-text/javascript"></script>
<script src="{{ asset('assets/js/demo/profile.demo.js') }}" type="df5f205cfe6643099cf45599-text/javascript"></script>
<script>
	function printTable() {
		window.print();
	}
</script>
<script type="df5f205cfe6643099cf45599-text/javascript">
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-53034621-1', 'auto');
		ga('send', 'pageview');

	</script>
<script src="{{asset('cdn-cgi/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js')}}" data-cf-settings="df5f205cfe6643099cf45599-|49" defer=""></script><script defer src="https://static.cloudflareinsights.com/beacon.min.js/v652eace1692a40cfa3763df669d7439c1639079717194" integrity="sha512-Gi7xpJR8tSkrpF7aordPZQlW2DLtzUlZcumS8dMQjwDHEnw9I7ZLyiOj/6tZStRBGtGgN6ceN6cMH8z7etPGlw==" data-cf-beacon='{"rayId":"71e2e3eb6cb041bc","version":"2022.6.0","r":1,"token":"4db8c6ef997743fda032d4f73cfeff63","si":100}' crossorigin="anonymous"></script>
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
</body>

<!-- Mirrored from seantheme.com/color-admin/admin/html/extra_profile.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 20 Jun 2022 07:55:42 GMT -->
</html>