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
                                        <h4 class="font-weight-bold"><b>Abovefinex Token</b></h4>
                                    </div>
                                    <div class="col text-end">

                                        <a href='/dashboard' class="btn btn-secondary">Back</a>
                                    </div>
                                </div>

                                <!--begin::Form Group-->
                                <div class="form-group row m-2">
                                    <h4 class='fw-bolder text-center'>Create New Token</h4>


                                </div>
                                <div>
                                    <form method='post' action='{{route("store_voucher")}}'>@csrf

                                        <div class="form-group row m-2">

                                            <div class="col-md-12">
                                                <label class='fw-bolder'>Tokens (Separate with comma for multiple
                                                    tokens)</label>
                                                <div id="voucher-list" class="mb-2"></div>
                                                <input name="voucher" id="voucher-input"
                                                    class="form-control form-control-lg form-control-solid" type="text"
                                                    placeholder="Enter the voucher pin" />
                                            </div>

                                        </div>
                                        <div class="form-group row m-2">

                                            <div class="col-md-12">
                                                <label class='fw-bolder'>Price($)</label>
                                                <input required name="price"
                                                    class="form-control form-control-lg form-control-solid"
                                                    type="number" placeholder="Main amount of token" />

                                            </div>
                                        </div>

                                        <div class="form-group row m-2">

                                            <div class="col-md-12">
                                                <button type='submit'
                                                    class='btn btn-success btn-lg mb-3 col-md-12'>Create Token</button>
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
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $('#voucher-input').on('keyup', function (e) {
            // Check if the comma key (or Enter key) was pressed
            if (e.key === ',' || e.key === 'Enter') {
                e.preventDefault(); // Prevent default comma/Enter behavior

                var voucherPin = $(this).val().trim().replace(/,$/, ''); // Remove trailing comma
                if (voucherPin) {
                    // Add the voucher pin to the voucher list div
                    $('#voucher-list').append(
                        '<div class="voucher-item d-inline-block bg-light p-2 mb-1 me-1 border rounded">' +
                        '<span>' + voucherPin + '</span>' +
                        '<button type="button" class="remove-voucher btn btn-sm btn-danger ms-2">&times;</button>' +
                        '</div>'
                    );
                    // Clear the input field
                    $(this).val('');
                }
            }
        });

        // Handle removing a voucher pin from the list
        $(document).on('click', '.remove-voucher', function () {
            $(this).parent().remove();
        });

        // On form submit, collect all the vouchers and append them to a hidden input field
        $('form').on('submit', function () {
            var vouchers = [];
            $('#voucher-list .voucher-item span').each(function () {
                vouchers.push($(this).text());
            });
            $('<input>').attr({
                type: 'hidden',
                name: 'vouchers',
                value: vouchers.join(',')
            }).appendTo('form');
        });


        @if (session('message'))
            Swal.fire('Success!', "{{ session('message') }}", 'success');
        @endif


    });


</script>
@include('user.footer')