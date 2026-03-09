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
                            <h4 class="mb-sm-0 font-size-18">My Abovefinex Tokens</h4>
                        </div>
                        <div class='col text-end'>
                            <a href='/buy-voucher' class="btn-sm btn btn-success">Buy Token</a>
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

                                @foreach($vouchers as $voucher)




                                <tr class="even">
                                    <td class="min-w-175px">
                                        <div class="position-relative ps-6 pe-3 py-2">
                                            <div
                                                class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-success">
                                            </div>
                                          
                                            <div class="fs-7 text-muted fw-bold">
                                              
                                              
                                                <div class="d-flex">
                                                    <input id="copy_content_{{ $loop->iteration }}" type="text"
                                                        class="form-control form-control-solid me-3 flex-grow-1"
                                                        name="search"
                                                        value="{{ $voucher->voucher }}">

                                                    <button id="copy_btn"
                                                        class="btn btn-light btn-light-primary fw-bold flex-shrink-0 copy-btn"
                                                        data-clipboard-target="#copy_content_{{ $loop->iteration }}"><i class='fa fa-copy'></i></button>
                                                </div>
                                                  <a href="#" class="mb-1 text-gray-900 text-hover-primary fw-bold"> Token Price <b>${{ number_format($voucher->price) }}</b><br>
                                            </a>
                                            <!--<span class='text-success'> Status : @if($voucher->status == 1) Not Used Yed @else Used @endif</span>-->
                                                
                                            </div>


                                            <!--<a onclick="return confirm('Are you sure you want to delete this voucher?')"-->
                                            <!--    href='{{ $voucher->slug }}'-->
                                            <!--    class='btn btn-sm btn-danger'><i class='fa fa-trash'></i>-->
                                            <!--</a>-->
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
   
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
              @if (session('message'))
            Swal.fire('Success!', "{{ session('message') }}", 'success');
        @endif
          @if (session('error'))
            Swal.fire('Error!', "{{ session('error') }}", 'error');
        @endif
         })
    </script>

    @include('user.footer')
