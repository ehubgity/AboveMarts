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

        <div class="tab-content p-0">

            <div class="tab-pane fade show active" id="profile-about">
                <h4> Electricity Purchase (Verify Details)</small></h4>

                <marquee behavior="" direction="">Please, Kindly Note We Only Transact With Prepaid Meter</marquee>

                <form action="{{ route('verifystore') }}" id="my-form" method="post">
                    @csrf

                    <div class="row mb-15px">
                        <label class="form-label col-form-label col-md-2">Select Electricity Service</label>
                        <div class="col-md-6">
                            <select class="form-select" name="package" id="package" required>
                                <option value="none">Select Electricity</option>
                                <option value="jos-electric">JEDC PREPAID</option>
                                <option value="abuja-electric">AEDC PREPAID</option>
                                <option value="benin-electric">BEDC PREPAID</option>
                                <option value="enugu-electric">EEDC PREPAID</option>

                                <option value="portharcourt-electric">PHED PREPAID</option>
                                <option value="ibadan-electric">IBEDC PREPAID</option>
                                <option value="ikeja-electric">IKEDC PREPAID</option>
                                <option value="eko-electric">EKEDC PREPAID</option>
                               
                                <option value="kano-electric">KEDCO PREPAID</option>
                                <option value="kaduna-electric">KEDC PREPAID</option>
                                <option value="yola-electric">YEDC PREPAID</option>
                                <option value="aba-electric">ABEDC PREPAID</option>

                            </select>
                            <small class="fs-12px text-gray-500-darker">Kindly select a electricity service.</small>
                        </div>
                    </div>

                    <div class="row mb-15px" id="amount">
                        <label class="form-label col-form-label col-md-2">Meter Number</label>
                        <div class="col-md-6">
                            <input class="form-control" type="number" id="meter" name ="meterNumber"
                                placeholder="Enter Meter Number" required />
                        </div>
                    </div>

                    <div class="row mb-15px" id="amount">
                        <label class="form-label col-form-label col-md-2">Amount (#)</label>
                        <div class="col-md-6">
                            <input class="form-control" type="number" id="amountV" name ="amount"
                                placeholder="Enter Amount" required />
                        </div>
                    </div>
                    <div class="row mb-15px" id="amount">
                        <label class="form-label col-form-label col-md-2">Service Fee (#)</label>
                        <div class="col-md-6">
                            <input class="form-control" type="number" value="50" name ="serviceFee" readonly />
                        </div>
                    </div>
                    <div class="row mb-15px">
                        <label class="form-label col-form-label col-md-2">Select Method Payment</label>
                        <div class="col-md-6">
                            <select class="form-select" name="payment" id="payment" onChange="updatepaymentMethod()">
                                <option value="wallet">Main Wallet</option>
                                {{-- <option value="epin">E-Pin</option>
                <option value="promo">Promo Wallet</option> --}}
                            </select>
                        </div>
                    </div>


                    <div class="row mb-15px">
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-9">
                            <button type="submit" id="submit-button" class="btn btn-primary w-250px">Verify</button>
                        </div>
                    </div>


                </form>
            </div>

        </div>

    </div>


</div>
</div>

</div>

</div>

</div>

</div>

</div>

@include('user.footer')
