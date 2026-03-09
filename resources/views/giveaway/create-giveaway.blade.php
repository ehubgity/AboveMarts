@include('user.head')
@include('user.header')
@include('user.sidebar')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="app-sidebar-bg"></div>
<div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a>
</div>

<div id="content" class="app-content p-0">

    <div class="profile">
        <div class="profile-header">

            <div class="profile-header-cover"></div>


            <div class="profile-header-content">

                <div class="profile-header-img">
                    <img src="{{ auth()->user()->photo }}" alt="" />
                </div>


                <div class="profile-header-info ">
                    <h4 class="mt-0 mb-1"> {{ auth()->user()->firstName }} {{ auth()->user()->lastName }}</h4>
                    <p class="mb-2">{{ auth()->user()->rank }}</p>

                </div>
            </div>


        </div>
    </div>



 <div class="profile-content">

    <!--begin::Container-->
      <div class="tab-content p-0">
        <!--begin::Profile Account Information-->
        <div class="tab-pane fade show active" id="profile-about">
            <!--begin::Aside-->

            <!--end::Aside-->
            <!--begin::Content-->
            <div>
                <div class="col-md-12">
                    <!--begin::Card-->
                    <div class="card card-custom">
                        <!--begin::Header-->

                        <!--end::Header-->
                        <!--begin::Form-->

                        <div class="card-body">
                            <!--begin::Heading-->

                            <div class="row">

                                <div class="col">
                                    <h4 class="font-weight-bold"><b>Abovemart Giveaway</b></h4>
                                </div>
                                <div class="col text-end">
                                  
                                    <a href='/my-giveaway' class="btn btn-secondary">Back</a>
                                </div>
                            </div>

                            <!--begin::Form Group-->
                            <div class="form-group row m-2">
                                <h4 class='fw-bolder text-center'>Create New Giveaway</h4>

                               
                            </div>
                            <div id='raffle'>
                                <form id='raffle_form'>
                                    <div class="form-group row m-2">

                                        <div class="card-body">
                                            <h4 class="card-title mb-0">New to creating raffle draw giveaways? Click<a style='color:red;cursor:pointer' id='raffle_quick_quide_btn'> here</a> for a Quick Guide</h4>

                                            <div style='display:none' class="row" id='raffle_quick_quide'>
                                                <div class="col-xl-12">
                                                    <div class="mt-4">

                                                        <div class="accordion" id="accordionExample">
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingOne">
                                                                    <button class="accordion-button fw-medium"
                                                                        type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#collapseOne"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseOne">
                                                                     <b>How does Raffle Draw
                                                                        works?</b>
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseOne"
                                                                    class="accordion-collapse collapse"
                                                                    aria-labelledby="headingOne"
                                                                    data-bs-parent="#accordionExample" style="">
                                                                    <div class="accordion-body">
                                                                        <div class="text-muted">
                                                                            Raffle draw giveaway give each of your giveaway participant a random number, and pick winners randomly.
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingTwo">
                                                                    <button class="accordion-button fw-medium collapsed"
                                                                        type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#collapseTwo"
                                                                        aria-expanded="false"
                                                                        aria-controls="collapseTwo">
                                                                        <b>How do I create a raffle draw giveaway?</b>
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseTwo"
                                                                    class="accordion-collapse collapse"
                                                                    aria-labelledby="headingTwo"
                                                                    data-bs-parent="#accordionExample" style="">
                                                                    <div class="accordion-body">
                                                                        <div class="text-muted">
                                                                           <ol>
                                                                            <li>Fill all the neccessary fields below appropriately.</li>
                                                                            <li>A live link will be generated after clicking on the create button.</li>
                                                                            <li>Share the link for your audience to participate.</li>
                                                                            
                                                                           </ol>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingThree">
                                                                    <button class="accordion-button fw-medium collapsed"
                                                                        type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#collapseThree"
                                                                        aria-expanded="false"
                                                                        aria-controls="collapseThree">
                                                                        <b>What are the security measures put in place?</b>
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseThree"
                                                                    class="accordion-collapse collapse"
                                                                    aria-labelledby="headingThree"
                                                                    data-bs-parent="#accordionExample">
                                                                    <div class="accordion-body">
                                                                        <div class="text-muted">
                                                                            <ul>
                                                                                <li>No single individuals can participate in the giveaway more than once.</li>
                                                                                <li>The giveaway automatically close once the maximum number of participant specified by you is reached.</li>
                                                                                <li>You get to manage your giveaway participants by clicking on the <b>View Giveaway Details</b> button after you create the giveaway.</li>
                                                                            </ul>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- end accordion -->
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- end row -->
                                        </div>


                                    </div>
                                    <div class="form-group row m-2">

                                        <div class="col-md-12">
                                            <label class='fw-bolder'>Giveaway Name</label>
                                            <input required name="raffle_name" id='raffle_name'
                                                class="form-control form-control-lg form-control-solid" type="text"
                                                placeholder="Enter a be-fitting name" />

                                        </div>
                                    </div>
                                    <div class="form-group row m-2">

                                        <div class="col-md-12">
                                            <label class='fw-bolder'>Participant No.</label>
                                            <input required name="raffle_part_no" id='raffle_part_no'
                                                class="form-control form-control-lg form-control-solid" type="number"
                                                placeholder="Max. No. Of Participant" />

                                        </div>
                                    </div>
                                    <div class="form-group row m-2">

                                        <div class="col-md-12">
                                            <label class='fw-bolder'>No. Of Lucky Winners</label>
                                            <input required name="no_of_winners" id='raffle_no_of_winners'
                                                class="form-control form-control-lg form-control-solid" type="number"
                                                placeholder="Total No. Of Winners expected" />

                                        </div>
                                    </div>
                                      <div class="form-group row m-2">

                                        <div class="col-md-12">
                                            <label class='fw-bolder'>Entry Fee</label>
                                            <input required name="entryfee" id='entryfee'
                                                class="form-control form-control-lg form-control-solid" type="number"
                                                placeholder="Input Entry Fee" />

                                        </div>
                                    </div>
                                    <div class="form-group row m-2">
                                        <label class='fw-bolder'>Select Giveaway Price</label>
                                        <div class='col-md-6'>
                                            <select class='form-control form-control-lg' name='type' id='type'>
                                                <!--<option value='Data'>Data Giveaway</option>-->
                                                <option value='Airtime'>Cash/Airtime/Data</option>
                                                <option value='Cash'>Physical Gift Item</option>
                                            </select>
                                        </div>
                                       
                                        <div id='airtime_plan'  class="col-md-6 mt-2">
                                            <input id='raffle_airtime_price' name='raffle_airtime_price'
                                                class='form-control form-control-lg' type='number'
                                                placeholder='Input Giveaway Amount' />
                                        </div>
                                        <div id='cash_plan' style='display:none' class="col-md-6 mt-2">
                                            <input readonly value='0' id='raffle_cash_price' name='raffle_cash_price'
                                                class='form-control form-control-lg' type='number'
                                                placeholder='Input Cash Prize Amount' />
                                        </div>
                                    </div>
                                    <div style='display:none' id='raffle_estimated_amount_block'
                                        class='alert alert-success'>
                                        <input type='hidden' id='raffle_amount' />
                                        Amount To Be Charged : NGN<span id='raffle_estimated_amount'></span>
                                    </div>
                                    
                                     <div class="form-group row m-2">

                                        <div class="col-md-12">
                                            <label class='fw-bolder'>Affliate Link</label>
                                            <input required name="link" id='link'
                                                class="form-control form-control-lg form-control-solid" type="text"
                                                placeholder="Enter affliate link" />

                                        </div>
                                    </div>

                                    <div class="form-group row m-2">

                                        <div class="col-md-12">
                                            <button type='submit' class='btn btn-success btn-lg mb-3 col-md-12'>Create
                                                Giveaway</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                          


                        </div>

                    </div>
                    <!--end::Card-->
                </div>
            </div>




        </div>
        <!--end::Profile Account Information-->
    </div>
    <!--end::Container-->
</div>
</div>

<script>
    $(document).ready(function() {
        	$.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        
        $("#raffle_quick_quide_btn").click(function() {
            if ($("#raffle_quick_quide").is(":visible")) {
                // If the element is currently visible, hide it
                $("#raffle_quick_quide").hide();
            } else {
                // If the element is currently hidden, show it
                $("#raffle_quick_quide").show();
            }
        });
        $("#q_quick_quide_btn").click(function() {
            if ($("#q_quick_quide").is(":visible")) {
                // If the element is currently visible, hide it
                $("#q_quick_quide").hide();
            } else {
                // If the element is currently hidden, show it
                $("#q_quick_quide").show();
            }
        });

       
     $("#raffle_form").on('submit', async function(e) {
       e.preventDefault();      
       $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        
       var amount = $("#raffle_amount").val()           
    
         Swal.fire({
          
           title: `You will be charged NGN${amount.toLocaleString()} for this giveaway.`,
           icon: "warning",
           text:'A charge of NGN100 will be deducted to create this giveaway!',
          
           showCancelButton: true,
           confirmButtonColor: "#ebab21",
           cancelButtonColor: "#d4edda",
           confirmButtonText: "Create Giveaway",
           allowOutsideClick: false,
           allowEscapeKey: false,
           preConfirm: () => {
             const confirmButton = Swal.getConfirmButton();
             confirmButton.textContent = "Validating ";
             confirmButton.disabled = true;
             confirmButton.insertAdjacentHTML(
               "beforeend",
               `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`
             );
             return new Promise((resolve) => {
               // You can perform any necessary validation here, e.g. making a server call.
               // Once validation is complete, call resolve() to close the modal.
               setTimeout(() => {
                 resolve();
               }, 500);
             });
           },
 
         
         }).then((result) => {
           
           // const swalMessage =
           //   SwalContent !== null
           //     ? SwalContent
           //     : "Creating Giveaway, please wait...";
           Swal.fire({
             title: 'Creating Giveaway, please wait...',
             // html: '<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>',
             showConfirmButton: false,
             allowOutsideClick: false,
             allowEscapeKey: false,
             didOpen: () => {
               Swal.showLoading();
             },
           });
           let fd = new FormData();
           fd.append("name", $("#raffle_name").val());
           fd.append("part_no", $("#raffle_part_no").val());
           fd.append("no_winner", $("#raffle_no_of_winners").val());
             fd.append("entryfee", $("#entryfee").val());
           fd.append("winner_price", $("#raffle_winner_price").val());
           fd.append("winner_real_price",$("#raffle_winner_price").find('option:selected').data('price'));
           fd.append("amount", $("#raffle_amount").val());
           fd.append('type','raffle');
           fd.append('giveaway_type',$("#type").val());
           fd.append('raffle_airtime_price',$("#raffle_airtime_price").val());
           fd.append('raffle_cash_price',$("#raffle_cash_price").val());
            fd.append('link',$("#link").val());
          
           fd.append("pin", result.value);
          $.ajax({
  url: "/createDataGiveaway",
  type: "POST",
  data: fd,
  processData: false,
  contentType: false,
 
  success: function (response) {
    console.log(response, "the res");
    if (response.success == "true" || response.success == true) {
      Swal.fire({
        icon: "success",
        title: "Giveaway Created Successful!",
        showConfirmButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Ok",
        allowOutsideClick: false,
        allowEscapeKey: false,
      }).then((result) => {
        window.location.href = '/my-giveaway';
      });
    } else {
      Swal.fire({
        icon: "error",
        title: response.message,
        showConfirmButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Ok",
        allowOutsideClick: false,
        allowEscapeKey: false,
      }).then((result) => {
        if (result.isConfirmed) {
          // location.reload();
        }
      });
    }
  },
  error: function (error) {
    console.log(error.message);
    Swal.fire(error.message);
  }
});

         });
         });
     $("#q_form").on('submit', async function(e) {
       e.preventDefault();                 
    
       var amount = $("#q_amount").val()           
    
        Swal.fire({
     
           title: `You will be charged NGN${amount.toLocaleString()} for this giveaway. Input your four(4) digit pin to proceed`,
            
           icon: "warning",
           input: "password",
           inputAttributes: {
             inputmode: "numeric",
             maxlength: 4,
             autocomplete: "new-password",
             name: "my-pin",
             autocapitalize: "off",
             pattern: "[0-9]*",
             style: "text-align:center;font-size:24px;letter-spacing: 20px",
           },
           showCancelButton: true,
           confirmButtonColor: "#ebab21",
           cancelButtonColor: "#d4edda",
           confirmButtonText: "Create Giveaway",
           allowOutsideClick: false,
           allowEscapeKey: false,
           preConfirm: () => {
             const confirmButton = Swal.getConfirmButton();
             confirmButton.textContent = "Validating ";
             confirmButton.disabled = true;
             confirmButton.insertAdjacentHTML(
               "beforeend",
               `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`
             );
             return new Promise((resolve) => {
               // You can perform any necessary validation here, e.g. making a server call.
               // Once validation is complete, call resolve() to close the modal.
               setTimeout(() => {
                 resolve();
               }, 500);
             });
           },
 
           inputValidator: (text) => {
             if (!/^\d{4}$/.test(text)) {
               return "Please enter a four-digit PIN";
             }
           },
         }).then((result) => {
           
           // const swalMessage =
           //   SwalContent !== null
           //     ? SwalContent
           //     : "Creating Giveaway, please wait...";
           Swal.fire({
             title: 'Creating Giveaway, please wait...',
             // html: '<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>',
             showConfirmButton: false,
             allowOutsideClick: false,
             allowEscapeKey: false,
             didOpen: () => {
               Swal.showLoading();
             },
           });
           let fd = new FormData();
           fd.append("name", $("#q_name").val());
           fd.append("time", $("#q_time").val());
           fd.append("part_no", $("#q_no_of_winners").val());
           fd.append("no_winner", $("#q_no_of_winners").val());
           fd.append("winner_price", $("#q_winner_price").val());
           fd.append("winner_real_price",$("#q_winner_price").find('option:selected').data('price'));
           fd.append("amount", $("#q_amount").val());
           fd.append('type','question');
           fd.append('giveaway_type',$("#qtype").val());
           fd.append('q_airtime_price',$("#q_airtime_price").val());
           fd.append('q_cash_price',$("#q_cash_price").val());
          
          
           fd.append("pin", result.value);
          
           axios
             .post("/createDataGiveaway", fd)
             .then((response) => {
               console.log(response, "the res");
               if (response.data.success == "true" || response.data.success == true) {
                 Swal.fire({
                   icon: "success",
                   title: "Giveaway Created Successful!",
                   showConfirmButton: true, // updated
                   confirmButtonColor: "#3085d6", // added
                   confirmButtonText: "Ok", // added
                   allowOutsideClick: false, // added to prevent dismissing the modal by clicking outside
                   allowEscapeKey: false, // added to prevent dismissing the modal by pressing Esc key
                 }).then((result) => {
                    window.location.href = '/my-giveaway';
                 });
               } else {
                
              
                 Swal.fire({
                 icon: "error",
                 // title: response.data.message,
                 title: response.data.message,
                 // text: "Updating...",
                 showConfirmButton: true, // updated
                 confirmButtonColor: "#3085d6", // added
                 confirmButtonText: "Ok", // added
                 allowOutsideClick: false, // added to prevent dismissing the modal by clicking outside
                 allowEscapeKey: false, // added to prevent dismissing the modal by pressing Esc key
               }).then((result) => {
                 if (result.isConfirmed) {
                   // location.reload();
                 }
               });
               }
             })
             .catch((error) => {
               console.log(error.message);
               Swal.fire(error.message);
             });
         });
         });
         $("#raffle_btn").on('click', function() {
            $("#raffle").show()
            $("#q_and_a").hide()
            $("#raffle_btn").css('background','grey')
            $("#raffle_btn").css('color','#fff')
            $("#q_and_a_button").css('background','white')
            $("#q_and_a_button").css('color','black')
        })
        $("#q_and_a_button").on('click', function() {
            $("#raffle").hide()
            $("#q_and_a").show()
            $("#q_and_a_button").css('background','grey')
            $("#q_and_a_button").css('color','#fff')
            $("#raffle_btn").css('background','white')
            $("#raffle_btn").css('color','black')
        })
        $("#type").on('change', function() {
            var myvalue = $("#type").val()

            if(myvalue == "Data") {
                $("#data_plan").show()
                $("#airtime_plan").hide()
                $("#cash_plan").hide()

            }
            else if(myvalue == 'Cash') {
                $("#cash_plan").show()
                $("#airtime_plan").hide()
                $("#data_plan").hide()
            }
            else {
                $("#airtime_plan").show()
                $("#data_plan").hide()
                $("#cash_plan").hide()
            }
        })
        $("#qtype").on('change', function() {
            var myvalue = $("#qtype").val()

            if(myvalue == "Data") {
                $("#q_data_plan").show()
                $("#q_airtime_plan").hide()
                $("#q_cash_plan").hide()

            } else if(myvalue =='Cash') {
                $("#q_cash_plan").show()
                $("#q_airtime_plan").hide()
                $("#q_data_plan").hide()
            }
            else {
                $("#q_airtime_plan").show()
                $("#q_data_plan").hide()
                $("#q_cash_plan").hide()
            }
        })
        function updateEstimatedAmount() {
            var noOfWinners = $("#raffle_no_of_winners").val();
           
            if($("#type").val() == 'Data') {
                var priceElement = $("#raffle_winner_price");
            } else if($("#type").val() == 'Airtime') {
                var priceElement = $("#raffle_airtime_price");
            }
            else {
                var priceElement = $("#raffle_cash_price");
            }

            if (noOfWinners > 0) {
              $("#raffle_estimated_amount_block").show();
            
              var dataValue = priceElement.is('select')
                ? priceElement.find('option:selected').data('price')
                : priceElement.val();
            
              var amount = dataValue * noOfWinners;
            
              $("#raffle_amount").val(amount);
              $("#raffle_estimated_amount").text(amount.toLocaleString());
            }
        }

        $("#raffle_winner_price, #raffle_airtime_price, #raffle_cash_price").on('change input', updateEstimatedAmount);
        $("#raffle_no_of_winners").on('input', updateEstimatedAmount);

        function updateEstimatedAmountQ() {
            var noOfWinners = $("#q_no_of_winners").val();
            if($("#qtype").val() == 'Data') {
                var priceElement = $("#q_winner_price");
            } else if($("#qtype").val() == 'Airtime') {
                var priceElement = $("#q_airtime_price");
            }
            else {
                var priceElement = $("#q_cash_price");
            }

            if (noOfWinners > 0) {
              $("#q_estimated_amount_block").show();
            
              var dataValue = priceElement.is('select')
                ? priceElement.find('option:selected').data('price')
                : priceElement.val();
            
              var amount = dataValue * noOfWinners;
            
              $("#q_amount").val(amount);
              $("#q_estimated_amount").text(amount.toLocaleString());
            }
        }

        $("#q_winner_price, #q_airtime_price, #q_cash_price").on('change input', updateEstimatedAmountQ);
        $("#q_no_of_winners").on('input', updateEstimatedAmountQ);


        @if (session('message'))
        Swal.fire('Success!',"{{ session('message') }}",'success');
        @endif


     });
      
   
</script>
@include('user.footer')
