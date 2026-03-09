@include('user.head')
@include('user.header')
@include('user.sidebar')
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

            <div class="card-header flex-wrap border-0 pt-6 pb-0">

                <div class="card-title">
                    <div class="page-title-box align-items-center justify-content-between">

                        <div class='col'>
                            <h4 class="mb-sm-0 font-size-18">My Giveaways</h4>
                        </div>
                        <div class='col text-end'>
                            <a href='/create-giveaway' class="btn-sm btn btn-success">Create Giveaway</a>
                            <a onclick="window.history.back()" class="btn-sm btn btn-secondary">Back</a>
                        </div>


                    </div>

                </div>

                <!-- end card body -->
            </div>

            <div class="card-body pt-1">

                <!--begin::Table-->
                <div id="kt_widget_table_3_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="table-responsive">
                        <table
                            class="table datatable table-row-dashed align-middle fs-6 gy-4 my-0 pb-3"
                            data-kt-table-widget-3="all">
                            <thead class="d-none">
                                <tr>
                                    <th class="sorting" tabindex="0" aria-controls="kt_widget_table_3" rowspan="1"
                                        colspan="1" aria-label="Campaign: activate to sort column ascending"
                                        style="width: 0px;">Details</th>


                                </tr>
                            </thead>

                            <tbody>

                                @foreach($giveaway as $group)




                                <tr class="even">
                                    <td class="min-w-175px">
                                        <div class="position-relative ps-6 pe-3 py-2">
                                            <div
                                                class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-warning">
                                            </div>
                                            <a href="#" class="mb-1 text-gray-900 text-hover-primary fw-bold"> <b>{{
                                                    $group->name }} (NGN{{ number_format($group->estimated_amount)
                                                    }})</b><br>
                                            </a>
                                            <div class="fs-7 text-muted fw-bold">
                                              
                                                @if($group->type == "question_data" || $group->type=='question_airtime'
                                                ||
                                                $group->type ==
                                                'question_cash')
                                                @if(count($group->all_questions->all()) == 0)

                                                <span class='text-danger'>Kindly add questions to display the giveaway
                                                    live
                                                    link!</span><br>
                                                @else
                                                <div class="d-flex">
                                                    <input id="copy_content_{{ $loop->iteration }}" type="text"
                                                        class="form-control form-control-solid me-3 flex-grow-1"
                                                        name="search"
                                                        value="https://abovemarts.com/{{ $group->slug }}">

                                                    <button id="copy_btn"
                                                        class="btn btn-light btn-light-primary fw-bold flex-shrink-0 copy-btn"
                                                        data-clipboard-target="#copy_content_{{ $loop->iteration }}"><i class='fa fa-copy'></i></button>
                                                </div>
                                               
                                                @endif
                                                @else
                                                <div class="d-flex">
                                                    <input id="copy_content_{{ $loop->iteration }}" type="text"
                                                        class="form-control form-control-solid me-3 flex-grow-1"
                                                        name="search"
                                                        value="https://abovemarts.com/{{ $group->slug }}">

                                                    <button id="copy_btn"
                                                        class="btn btn-light btn-light-primary fw-bold flex-shrink-0 copy-btn"
                                                        data-clipboard-target="#copy_content_{{ $loop->iteration }}"><i class='fa fa-copy'></i></button>
                                                </div>
                                                
                                                @endif

                                            </div>


                                            @if($group->type == 'question_data' || $group->type == 'question_airtime' ||
                                            $group->type ==
                                            'question_cash')
                                            <a href='/add_question/{{ $group->slug }}'
                                                class='btn btn-sm btn-primary'>Add
                                                Questions</a>
                                            @endif
                                            {{-- <a href='https://abovemarts.com/{{ $group->slug }}'
                                                class='btn btn-sm btn-primary'>Copy Link</a> --}}

                                            <a href='/giveaway_participant/{{ $group->slug }}'
                                                data-total_amount="{{ number_format($group->estimated_amount) }}"
                                                class='btn btn-sm btn-info'>More Info</a>
                                            <a onclick="return confirm('Are you sure you want to delete this giveaway?')"
                                                href='/delete_giveaway/{{ $group->slug }}'
                                                class='btn btn-sm btn-danger'><i class='fa fa-trash'></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                @endforeach
                            </tbody>
                            <!--end::Table-->
                        </table>
                    </div>
                  
                </div>
                <!--end::Table-->
            </div>
          
        </div>
        <!-- end row -->


        <!-- end row -->
    </div>
</div>
</div>
   
    <script>
        $(document).ready(function() {
    var oTable = $('.datatable').DataTable({
            ordering: false,
            searching: true
            });   

            var clipboard = new ClipboardJS('.copy-btn');

clipboard.on('success', function (e) {
    e.clearSelection();
    var btn = e.trigger;
    btn.innerHTML = 'Copied!';
            setTimeout(function () {
                btn.innerHTML = '<i class="fa fa-copy"></i>';
            }, 2000); // Reset to 'Copy' after 2 seconds
});

clipboard.on('error', function (e) {
    console.error('Action:', e.action);
    console.error('Trigger:', e.trigger);
});
        $(".recharge").click(function() {
            return Swal.fire({
    title: 'Input your four(4) digit pin to confirm purchase!',
    text: 'Total Price: NGN'+$(this).data('total_amount'),
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Proceed',
    cancelButtonText: 'Cancel',
    input :"password",
    inputAttributes: {
            inputmode: "numeric",
            maxlength: 4,
            autocomplete: "new-password",
            name: "my-pin",
            autocapitalize: "off",
            pattern: "[0-9]*",
            style: "text-align:center;font-size:24px;letter-spacing: 20px",
          },
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
    if (result.isConfirmed == false) {
        return Swal.fire('Transaction Declined', '', 'error');
    } else {
           
        Swal.fire({
          title: "Making bulk purchase, please wait...",
          // html: '<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>',
          showConfirmButton: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          didOpen: () => {
            Swal.showLoading();
          },
        });

        let fd = new FormData();
        fd.append("group_id", $(this).data('group_id'));
      
        fd.append("pin", result.value);
        axios
          .post("/recharge_group", fd)
          .then((response) => {
            console.log(response, 'the res')
            if (response.data.success == "true") {
              Swal.fire({
                icon: "success",
                title: "Purchase successful! Check group transaction table to confirm.",
                showConfirmButton: true, // updated
                confirmButtonColor: "#3085d6", // added
                confirmButtonText: "Ok", // added
                allowOutsideClick: false, // added to prevent dismissing the modal by clicking outside
                allowEscapeKey: false, // added to prevent dismissing the modal by pressing Esc key
              }).then((result) => {
                if (result.isConfirmed) {
                //   location.reload();
                }
              });
            } else {
              Swal.fire({
                icon: "error",
                title: response.data.message,
                // title: "Opps, service currently not available and we are currently working on it, try again in 30Min time😢🙏",
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
        // window.location.href = '/recharge_group/'+$(this).data('group_id')
      return true; // User clicked "Yes"
    
    }
  });
        })
        $("#type").on('change',function() {
            $("#show_notify").show()
            $("#title").val($("#type").find(':selected').data('title'))
            $("#description").val($("#type").find(':selected').data('description'))
            $("#notf_id").val($("#type").find(':selected').val())
        })
        const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
                })

                @if (session('success'))
        Toast.fire({
                        icon: 'success',
                        title: '{{ session("success") }}'
                        }) 
           
        @endif
                    })
    </script>

    @include('user.footer')
