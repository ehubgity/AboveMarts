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
                <h4> Data Share</small></h4>
                <form action="{{ route('datashare') }}" id="my-form" method="post">
                    @csrf
                    <div class="row mb-15px">
                        <label class="form-label col-form-label col-md-2">Phone Number</label>
                        <div class="col-md-6">
                            <input class="form-control" type="number" name ="phoneNumber"
                                placeholder="Enter Phone Number" required />
                            <small class="fs-12px text-gray-500-darker">Kindly enter a valid phone number.</small>
                        </div>
                    </div>
                    <div class="row mb-15px">
                        <label class="form-label col-form-label col-md-2">Select Network</label>
                        <div class="col-md-6">
                            <select class="form-select" name="network" id="network" onChange="updateDataShare()">
                                <option value="">Select Network</option>
                                <option value="mtn">MTN</option>
                                <option value="airtel">AIRTEL</option>
                                <option value="glo">GLO</option>
                                <!--<option value="9mobile">9MOBILE</option>-->
                            </select>
                            <small class="fs-12px text-gray-500-darker">Kindly select a valid network.</small>
                        </div>
                    </div>

                    <div class="row mb-15px" style="display: none;" id="mtn">
                        <label class="form-label col-form-label col-md-2">Select Package</label>
                        <div class="col-md-6">
                            <select class="form-select" name="packageMTN" id="mtnData"
                                onChange="insertMTNDataAmount()">
                                                                <option value="None" selected disabled>Select Package</option>
                                                                                                @foreach($mtnplans as $mtnplan)

                                    <option value="{{ ($mtnplan['plan_id']) }}" data-price="{{ ceil(($mtnplan['price'] + ($mtnplan['price'] * 5 / 100)) / 5) * 5 }}">

                                        {{$mtnplan['name']}} ₦{{ ceil(($mtnplan['price'] + ($mtnplan['price'] * 5 / 100)) / 5) * 5 }} ({{$mtnplan['validity']}})
                                    </option>
                                    @endforeach
                                <!--<option value="data_share_500mb">DATA SHARE 500MB</option>-->
                                <!--<option value="data_share_1gb">DATA SHARE 1GB</option>-->
                                <!--<option value="data_share_2gb">DATA SHARE 2GB</option>-->
                                <!--<option value="data_share_3gb">DATA SHARE 3GB</option>-->
                                <!--<option value="data_share_5gb">DATA SHARE 5GB</option>-->
                                <!--<option value="data_share_10gb">DATA SHARE 10GB</option>-->
                                <!--<option value="mtn_corporate_data_15gb">MTN CORPORATE 15GB</option>-->
                                <!--<option value="mtn_corporate_data_20gb">MTN CORPORATE 20GB</option>-->
                                <!--<option value="mtn_corporate_data_40gb">MTN CORPORATE 40GB</option>-->
                                <!--<option value="mtn_corporate_data_75gb">MTN CORPORATE 75GB</option>-->
                                <!--<option value="mtn_corporate_data_100gb">MTN CORPORATE 100GB</option>-->

                            </select>
                        </div>
                    </div>
                    <div class="row mb-15px" style="display: none;" id="airtel">
                        <label class="form-label col-form-label col-md-2">Select Package</label>
                        <div class="col-md-6">
                            <select class="form-select" name="packageAirtel" id="airtelData"
                                onChange="insertAIRTELDataAmount()">
                                                                <option value="None" selected disabled>Select Package</option>

                                @foreach($airtelplans as $plan)
                                    <option value="{{ ($plan['plan_id']) }}" data-price="{{ ceil(($plan['price'] + ($plan['price'] * 5 / 100)) / 5) * 5 }}">
                                        {{$plan['name']}} ₦{{ ceil(($plan['price'] + ($plan['price'] * 5 / 100)) / 5) * 5 }} ({{$plan['validity']}}) 
                                        </option>
                                @endforeach
                                <!--<option value="None">Select Package</option>-->
                                <!--<option value="airtel_100mb_7days"> Data Share Airtel 100MB 7Days </option>-->
                                <!--<option value="airtel_500mb_30days">Data Share Airtel 500MB 30Days</option>-->
                                <!--<option value="airtel_300mb_7days">Data Share Airtel 300MB 7Days </option>-->
                                <!--<option value="airtel_1gb_30days"> Data Share Airtel 1GB 30Days</option>-->
                                <!--<option value="airtel_2gb_30days">Data Share Airtel 2GB 30Days </option>-->
                                <!--<option value="airtel_5gb_30days">Data Share Airtel 5GB 30Days</option>-->
                                <!--<option value="airtel_10gb_30days">Data Share Airtel 10GB 30Days </option>-->
                                <!--<option value="airtel_15gb_30days">Data Share Airtel 15GB 30Days </option>-->
                                <!--<option value="airtel_20gb_30days">Data Share Airtel 20GB 30Days</option>-->
                            </select>
                        </div>
                    </div>
                    <div class="row mb-15px" style="display: none;" id="glo">
                        <label class="form-label col-form-label col-md-2">Select Package</label>
                        <div class="col-md-6">
                            <select class="form-select" name="packageGLO" id="gloData"
                                onChange="insertGLODataAmount()">
                                                                @foreach($gloplans as $plan)
                                                                <option value="{{ ($plan['plan_id']) }}" data-price="{{ number_format(ceil(($plan['price'] + ($plan['price'] * 5 / 100)) / 5) * 5, 0) }}">{{$plan['name']}} ₦{{ number_format(ceil(($plan['price'] + ($plan['price'] * 5 / 100)) / 5) * 5, 0) }} ({{$plan['validity']}}) </option>
                                @endforeach
                                <!--<option value="None">Select Package</option>-->
                                <!--<option value="glo_cg_200mb_14days">GLO CG 200MB</option>-->
                                <!--<option value="glo_cg_500mb_30days">GLO CG 500MB</option>-->
                                <!--<option value="glo_cg_1gb_30days">GLO CG 1GB</option>-->
                                <!--<option value="glo_cg_2gb_30days">GLO CG 2GB</option>-->
                                <!--<option value="glo_cg_3gb_30days">GLO CG 3GB</option>-->
                                <!--<option value="glo_cg_5gb_30days">GLO CG 5GB</option>-->
                                <!--<option value="glo_cg_10gb_30days">GLO CG 10GB</option>-->


                            </select>
                        </div>
                    </div>
                    <div class="row mb-15px" style="display: none;" id="9mobile">
                        <label class="form-label col-form-label col-md-2">Select Package</label>
                        <div class="col-md-6">
                            <select class="form-select" name="package9Mobile" id="9mobileData"
                                onChange="insert9MOBILEDataAmount()">
                                                                <option value="None" selected disabled>Select Package</option>

                                                                @foreach($mobileplans as $plan)
                                    <option value="{{ ($plan['plan_id']) }}}"  data-price="{{ $plan['price'] + ($plan['price'] * 5 / 100) }}">{{$plan['name']}} ₦{{ ceil(($plan['price'] + ($plan['price'] * 5 / 100)) / 5) * 5 }} ({{$plan['validity']}}) </option>
                                @endforeach
                                <!--<option value="None">Select Package</option>-->
                                <!--<option value="9mobile_sme_1gb"> 9mobile SME 1GB </option>-->
                                <!--<option value="9mobile_sme_1_5gb">9mobile SME 1.5GB </option>-->
                                <!--<option value="9mobile_sme_2gb">9mobile SME 2GB </option>-->
                                <!--<option value="9mobile_sme_3gb">9mobile SME 3GB </option>-->
                                <!--<option value="9mobile_sme_5gb">9mobile SME 5GB </option>-->
                                <!--<option value="9mobile_sme_10gb">9mobile SME 10GB </option>-->
                                <!--<option value="9mobile_sme_15gb">9mobile SME 15GB </option>-->
                                <!--<option value="9mobile_sme_20gb">9mobile SME 20GB </option>-->
                            </select>
                        </div>
                    </div>
                    <div class="row mb-15px">
                        <label class="form-label col-form-label col-md-2">Amount</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="amount" id="amount" readonly>
                        </div>
                    </div>
                    <div class="row mb-15px">
                        <label class="form-label col-form-label col-md-2">Select Method Payment</label>
                        <div class="col-md-6">
                            <select class="form-select" name="payment" id="payment"
                                onChange="updatepaymentMethod()">
                                <option value="wallet">Main Wallet</option>
                                {{-- <option value="epin">E-Pin</option>
                <option value="promo">Promo Wallet</option> --}}
                            </select>
                        </div>
                    </div>
                    <div id="walletpayment" style="display:none;">
                    </div>
                    <div id="promopayment" style="display:none;">
                        <div class="mb-3">
                            <label class="col-sm-6 col-form-label">Promo Amount($)</label>
                            <div class="col-sm-6">
                                <input type="text" name="promoamount" class="form-control" id="promoamount"
                                    placeholder="0" value="0">
                            </div>
                        </div>
                        <p style ="color:red; font-size:14px;">Note: Maximum 50% can be use from promo wallet !</p>
                    </div>
                    <div id="epinpayment" style="display:none;">
                        <div class="mb-3">
                            <label class="col-sm-6 col-form-label">E-Pin</label>
                            <div class="col-sm-6">
                                <input type="text" name="epin" class="form-control" id="epin">
                            </div>
                        </div>
                    </div>

                    <!--<div class="row mb-15px" id="amount" style="display: none;">-->
                    <!--    <label class="form-label col-form-label col-md-2">Amount</label>-->
                    <!--    <div class="col-md-6">-->
                    <!--        <input class="form-control" type="text" id="amountV" name ="amount" value="0"-->
                    <!--            placeholder="Enter Amount" required readonly />-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="row mb-15px">
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-9">
                            <button type="submit" id="submit-button" class="btn btn-primary w-250px">Buy
                                Data</button>
                        </div>
                    </div>

                    <div>
                        <p>
                            <strong> Data Balance Check:</strong><br>

                            MTN: *323*4#<br>
                            Airtel: *323*4#<br>
                            Glo: *127*0#<br>
                            9Mobile: *228#
                        </p>
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
<script>
function insertMTNDataAmount() {
    const select = document.getElementById('mtnData');
    const selectedOption = select.options[select.selectedIndex];
    const price = selectedOption.getAttribute('data-price');

    if (price) {
        document.getElementById('amount').value = price;
    } else {
        document.getElementById('amount').value = '';
    }
}
function insertAIRTELDataAmount() {
    const select = document.getElementById('airtelData');
    const selectedOption = select.options[select.selectedIndex];
    const price = selectedOption.getAttribute('data-price');

    if (price) {
        document.getElementById('amount').value = price;
    } else {
        document.getElementById('amount').value = '';
    }
}
function insertGLODataAmount() {
    const select = document.getElementById('gloData');
    const selectedOption = select.options[select.selectedIndex];
    const price = selectedOption.getAttribute('data-price');

    if (price) {
        document.getElementById('amount').value = price;
    } else {
        document.getElementById('amount').value = '';
    }
}
function insert9MOBILEDataAmount() {
    const select = document.getElementById('9mobileData');
    const selectedOption = select.options[select.selectedIndex];
    const price = selectedOption.getAttribute('data-price');

    if (price) {
        document.getElementById('amount').value = price;
    } else {
        document.getElementById('amount').value = '';
    }
}
</script>

@include('user.footer')
