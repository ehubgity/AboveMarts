<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from seantheme.com/color-admin/admin/html/login_v2.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 20 Jun 2022 07:55:46 GMT -->
<head>
<meta charset="utf-8" />
<title>Giveaway Login to your Account | AboveMarts </title>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
<meta name="description" content="Create a free Account on AboveMarts Platform" />
<meta content="AboveMarts" name="author" />

<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
<link href="assets/css/vendor.min.css" rel="stylesheet" />
<link href="assets/css/default/app.min.css" rel="stylesheet" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
<body class='pace-top'>

<div id="loader" class="app-loader">
<span class="spinner"></span>
</div>


<div id="app" class="app">

<div class="login login-v2 fw-bold">

<div class="login-cover">
<div class="login-cover-img" style="background-image: url(assets/img/login-bg/login-bg-17.jpg)" data-id="login-cover-image"></div>
<div class="login-cover-bg"></div>
</div>


<div class="login-container">
    
<!--<a href='https://learn.abovemarts.com/login' type="submit" class="btn btn-primary d-block w-100 h-45px btn-lg">Academy Login →</a>-->

<div class="login-header">
<div class="brand">
<div class="d-flex align-items-center">
<span class="logo"></span> <b>Above</b> Marts
</div>
<small>Abovemarts Giveaway Program</small>
</div>
<div class="icon">
<i class="fa fa-lock"></i>
</div>
</div>


<div class="login-content">

	<h3>Welcome to the {{ $giveaway->name }}!</h3>
<div style='display:none' id='allbody' class="form-floating mb-20px">
 
  <div class="card-body pt-0">
                                    <div class="d-flex flex-column gap-10">
                                        <!--begin::Input group-->
                                        <div class="fv-row text-center">
                                            <!--begin::Label-->
                                            <!--end::Label-->
                                            @if($rand_no == 'xxx')
                                            <div class="fw-bolder fs-3 text-center" ><span style="font-size:30px;font-family: 'Courier New', Courier, monospace;" id='counter2'>
                                                Give away ended, you came late.😢</span></div>

                                            @else 
                                            <div class="fw-bolder fs-3 text-center" ><span style="font-size:30px;font-family: 'Courier New', Courier, monospace;" id='counter2'>
                                            {{ $rand_no }}</span></div>
                                            <input id='rand_no' value='{{ $rand_no }}' type='hidden'/>
                                            @endif
                                            <label class="form-label" style='color:#ebab21'>Your Lucky Number</label>

                                           <br>
                                           @if($won == 1)
                                            <p>Congratulations, You Won.<br> Today is indeed your lucky day!</p><br>
                                            <div class='alert alert-success'>You will be getting your gift shortly!💃😊</div>
                                            @else 
                                            <p>Opps, You didn't win the giveaway, so sorry about that!</p><br>
                                            <label class="form-label" style='color:#ebab21'>Giveaway Status</label><br>
                                          
                                            @endif
                                            <p>Do you know that you can as well create your own giveaway?</p>
                                            <p>Click <a href='https://abovemarts.com/login'>here</a> to login and create yours.</p>
                                            <!--end::Input-->
                                           

                                        </div>

                                    </div>
                                </div>
                                
                                  <div class='row'>
                            <div class='col-md-12'>
                                <div class="d-flex flex-column flex-lg-row-fluid p-8 ">

                                    <div class="card card-flush py-4 bg-dark">
                                        <!--begin::Card header-->
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h2 class='text-white'>Winners So Far</h2>
                                            </div>

                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="d-flex flex-column gap-10">
                                                <!--begin::Input group-->

                                                <!--end::Input group-->
                                                <!--begin::Separator-->
                                                <div class="separator"></div>
                                                <table class="table table-dark table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" class='p-4'>#</th>
                                                            <th scope="col" class='p-4'>Winner Name</th>
                                                            <th scope="col" class='p-4'>Lucky Number</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($lucky_winners as $key => $winner)
                                                        <tr>
                                                            <td scope="col" class='p-4'>{{ ++$key }}</td>
                                                              <td scope="col" class='p-4'>{{ substr($winner->name, 0, 5) . str_repeat('X', max(0, strlen($winner->name) - 5)) }}</td>
     
                                                            <td scope="col" class='p-4'>{{ $winner->lucky_number }}</td>
                                                           
                                                        </tr>
                                                        @endforeach


                                                    </tbody>
                                                </table>

                                                <!--end::Table-->
                                            </div>
                                        </div>
                                        <!--end::Card header-->
                                    </div>


                                </div>
                            </div>
                         
                        </div>
</div>

</div>

</div>





<script src="assets/js/vendor.min.js" type="a7059b533cf10b1ac9aabf4f-text/javascript"></script>
<script src="assets/js/app.min.js" type="a7059b533cf10b1ac9aabf4f-text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@include('sweetalert::alert')

<script src="assets/js/demo/login-v2.demo.js" type="a7059b533cf10b1ac9aabf4f-text/javascript"></script>

<script type="a7059b533cf10b1ac9aabf4f-text/javascript">
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','../../../../www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-53034621-1', 'auto');
		ga('send', 'pageview');

	</script>
<script src="cdn-cgi/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js" data-cf-settings="a7059b533cf10b1ac9aabf4f-|49" defer=""></script><script defer src="https://static.cloudflareinsights.com/beacon.min.js/v652eace1692a40cfa3763df669d7439c1639079717194" integrity="sha512-Gi7xpJR8tSkrpF7aordPZQlW2DLtzUlZcumS8dMQjwDHEnw9I7ZLyiOj/6tZStRBGtGgN6ceN6cMH8z7etPGlw==" data-cf-beacon='{"rayId":"71e2e3faad9541bc","version":"2022.6.0","r":1,"token":"4db8c6ef997743fda032d4f73cfeff63","si":100}' crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>


    <script>
       
        $(document).ready(function() {
            Swal.fire({
       
        html:
          '<p class="animate__animated animate__bounce animate__delay-1s" style="color:black">You are now a participant, click OK to see if you win.</p><br><b></b>',
        width: 600,
        padding: '3em',
        color: '#716add',
        background: '#fff ', // Set a solid background color
        showClass: {
          popup: 'animate__animated animate__jackInTheBox'
        },
        hideClass: {
          popup: 'animate__animated animate__flipOutY'
        },
        backdrop: `
                        rgba(0,0,123,0.4)
                        url("/assets/images/ballon.png")
                        left top
                        repeat

                      `,
        didOpen: () => {
           
          // Create and append confetti elements
          const confettiContainer = document.createElement('div');
          confettiContainer.classList.add('confetti-container');
          document.body.appendChild(confettiContainer);

          for (let i = 0; i < 50; i++) {
            const confetti = document.createElement('div');
            confetti.classList.add('confetti');
            confetti.style.left = Math.random() * 100 + 'vw';
            confetti.style.animationDuration = Math.random() * 2 + 1 + 's';
            confettiContainer.appendChild(confetti);
          }
        },
        willClose: () => {
             Swal.fire({
                title: "Let's see if you're lucky enough",
                text: "Hold tight, we're loading...",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading(); // Start the loading animation
                }
            });

           
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Check your luck now!',
                    text: 'The wait is over. Check your luck!'
                });
                $("#allbody").show()
            }, 10000); // Wait for 30 seconds

             
          // Remove confetti elements when the SweetAlert is closed
          const confettiContainer = document.querySelector('.confetti-container');
          if (confettiContainer) {
            confettiContainer.remove();
          }
          startCounter()
        }
      });
        function startCounter() {     
       
        var counterElement2 = document.getElementById('counter2');
       
        count_num2 = $("#rand_no").val()
      

        var count = 0;
        var totalTime = 100; // 10 seconds in milliseconds
         var delay2 = Math.floor(totalTime / count_num2); // Adjust delay based on total time and count number
        var startTime = performance.now();
        function updateCounter2() {
            var currentTime = performance.now();
            var elapsed = currentTime - startTime;
            var progress = Math.min(1, elapsed / totalTime);
            count = Math.floor(progress * count_num2);            

            counterElement2.innerText = count;
            if (progress < 1) {
                requestAnimationFrame(updateCounter2);
            }
           
        }

    
       
        updateCounter2();
    }
  

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
          // Function to create confetti elements
function createConfetti() {
  const confettiContainer = document.createElement('div');
  confettiContainer.classList.add('confetti-container');
  document.body.appendChild(confettiContainer);

  for (let i = 0; i < 50; i++) {
    const confetti = document.createElement('div');
    confetti.classList.add('confetti');
    confetti.style.left = Math.random() * 100 + 'vw';
    confetti.style.animationDuration = Math.random() * 2 + 1 + 's';
    confettiContainer.appendChild(confetti);
  }

  // Optionally return the container element if you need to manipulate it later
  return confettiContainer;
}

// Function to remove confetti elements
function removeConfetti(confettiContainer) {
  if (confettiContainer) {
    confettiContainer.remove();
  }
}

const confettiContainer = createConfetti();


          })
    </script>
</body>

<!-- Mirrored from seantheme.com/color-admin/admin/html/login_v2.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 20 Jun 2022 07:55:52 GMT -->
</html>