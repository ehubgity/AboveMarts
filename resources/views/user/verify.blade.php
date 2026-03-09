@include('user.head')
@include('user.header')
@include('user.sidebar')
<div class="app-sidebar-bg"></div>
<div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>


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
<h4> Electricity Purchase</small></h4>
<form action="{{ route('lightpurchase') }}" id="my-form" method="post">
@csrf

    <div class="row mb-15px" id="amount">
        <label class="form-label col-form-label col-md-2">Transaction ID</label>
        <div class="col-md-6">
        <input class="form-control" type="text" name="id" value ="{{ $data->transactionId }}" readonly />
        </div>
    </div>

    <div class="row mb-15px" id="amount">
        <label class="form-label col-form-label col-md-2">Meter Number</label>
        <div class="col-md-6">
        <input class="form-control" type="number" id="meter" value ="{{ $data->meter }}" readonly placeholder="Enter Meter Number" required />
        </div>
    </div>
    <div class="row mb-15px" id="amount">
        <label class="form-label col-form-label col-md-2">Name</label>
        <div class="col-md-6">
        <input class="form-control" type="text" id="meter"  value ="{{ $data->meterName }}" readonly placeholder="Enter Meter Number" required />
        </div>
    </div>
    <div class="row mb-15px" id="amount">
        <label class="form-label col-form-label col-md-2">Address</label>
        <div class="col-md-6">
        <input class="form-control" type="text" id="meter"  value ="{{ $data->meterAddress }}" readonly placeholder="Enter Meter Number" required />
        </div>
    </div>
    
    <div class="row mb-15px" id="amount">
    <label class="form-label col-form-label col-md-2">Amount (#)</label>
    <div class="col-md-6">
    <input class="form-control" type="number" value ="{{ $data->amount }}" name ="amount" readonly placeholder="Enter Amount" required />
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
                <option value="epin">E-Pin</option>
                <option value="promo">Promo Wallet</option>
            </select>
        </div>
    </div>
    <div id="walletpayment" style="display:none;">
    </div>
    <div id="promopayment"  style="display:none;">
        <div class="mb-3">
        <label class="col-sm-6 col-form-label">Promo Amount($)</label>
            <div class="col-sm-6">
                <input type="text" name="promoamount" class="form-control" id="promoamount" placeholder="0" value="0" >
            </div>
        </div> 
        <p style ="color:red; font-size:14px;">Note: Maximum 50% can be use from promo wallet !</p>
    </div>
    <div id="epinpayment"  style="display:none;">
        <div class="mb-3">
            <label class="col-sm-6 col-form-label">E-Pin</label>
        <div class="col-sm-6">
            <input type="text" name="epin" class="form-control" id="epin" >
        </div>
        </div>
    </div>
    
    <div class="row mb-15px">
    <div class="col-md-2">
    </div>
    <div class="col-md-9">
    <button type="submit" id="submit-button" class="btn btn-primary w-250px">Buy</button>
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