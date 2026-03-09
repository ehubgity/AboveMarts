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
                                        <a href='/my-vouchers' class="btn btn-secondary">My Tokens</a>
                                        <a href='/dashboard' class="btn btn-secondary">Back</a>
                                    </div>
                                </div>

                                <!--begin::Form Group-->
                                <div class="form-group row m-2">
                                    <h4 class='fw-bolder text-center'>Buy Token</h4>


                                </div>
                                <div>
                                    <form method='post' action='{{route("purchase_voucher")}}'>@csrf

                                        <div class="form-group row m-2">

                                            <div class="col-md-12">
                                                <label class='fw-bolder'>Select The Price</label>

                                                <select required name="price"
                                                    class="form-control form-control-lg form-control-solid">
                                                    <option>--Select Token Price--</option>
                                                    @foreach($prices as $price)
                                                    <option value='{{$price}}'>${{$price}}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="col-md-12">
                                                <label class='fw-bolder'>Input Unit</label>
                                                <div id="voucher-list" class="mb-2"></div>
                                                <input required name="unit"  min="1" 
                                                    class="form-control form-control-lg form-control-solid" type="number"
                                                    placeholder="Enter the amount of token" />
                                            </div>
                                        </div>
                                         <div class="form-group row m-2">
                                        <div class='col-12 m-2 alert alert-success'>
                                             <p>
                                                @if(Auth::user()->package == 'Bronze')
                                                You are currently enjoying a 5% discount based on your Bronze plan. <a href='https://abovemarts.com/userpackages'>Upgrade now</a> to enjoy a better discount rate!
                                                @elseif(Auth::user()->package == 'Silver')
                                                You are currently enjoying a 10% discount based on your Silver plan.  <a href='https://abovemarts.com/userpackages'>Upgrade now</a> to enjoy a better discount rate!
                                                @elseif(Auth::user()->package == 'Gold')
                                                You are currently enjoying a 15% discount based on your Gold plan.  <a href='https://abovemarts.com/userpackages'>Upgrade now</a> to enjoy a better discount rate!
                                                @elseif(Auth::user()->package == 'Platinum')
                                                You are currently enjoying a 20% discount based on your Platinum plan. 
                                                @else
                                                You are currently not enrolled in any discount plan.
                                                @endif
                                            </p>
                                            <p>
                                                Discount Rate
                                            </p>
                                            <ul>
                                                <li>Bronze : 5%</li>
                                                <li>Sliver : 10%</li>
                                                <li>Gold : 15%</li>
                                                <li>Platinum : 20%</li>
                                            </ul>
                                           

                                           
                                        </div>
                                        </div>


                                        <div class="form-group row m-2">

                                            <div class="col-md-12">
                                                <button type='submit'
                                                    class='btn btn-success btn-lg mb-3 col-md-12'>Buy
                                                    Token</button>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {

        @if(session('message'))
        Swal.fire('Success!', "{{ session('message') }}", 'success');
        @endif
        @if(session('error'))
        Swal.fire('error!', "{{ session('error') }}", 'error');
        @endif
    })
</script>

@include('user.footer')