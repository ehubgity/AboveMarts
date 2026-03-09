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
<h4>Sample Recharge Card Printing</small></h4>
<form action="{{ route('samplecards') }}" method="post">
@csrf
<div class="row mb-15px">
<label class="form-label col-form-label col-md-2">Enter Business Name</label>
<div class="col-md-6">
<input class="form-control" type="text" name ="businessName" placeholder="Enter Business Name"  required/>
{{-- <small class="fs-12px text-gray-500-darker">Kindly enter a valid phone number.</small> --}}
</div>
</div>

    <div class="row mb-15px">
        <label class="form-label col-form-label col-md-2">Select Network</label>
        <div class="col-md-6">
            <select class="form-select" name="network" id="network">
                <option value="None">Select Network</option>
                <option value="mtn">MTN</option>
                <option value="airtel">AIRTEL</option>
                <option value="glo">GLO</option>
                <option value="9mobile">9MOBILE</option>
            </select>
            <small class="fs-12px text-gray-500-darker">Kindly select a valid network.</small>
        </div>
    </div>
    <div class="row mb-15px">
        <label class="form-label col-form-label col-md-2">Select Amount</label>
        <div class="col-md-6">
            <select class="form-select" name="amount" id="amount">
                <option value="None">Select Amount</option>
                <option value="100">100</option>
                <option value="200">200</option>
                <option value="500">500</option>
            </select>
            {{-- <input class="form-control" type="text" name ="amount" placeholder="Enter Amount" required /> --}}

        </div>
    </div>
    
    <div class="row mb-15px">
        <label class="form-label col-form-label col-md-2">Quantity</label>
        <div class="col-md-6">
        <input class="form-control" type="number" value="1" id="quantity" name ="quantity" required />
        </div>
    </div>
    
    <div class="row mb-15px">
    <div class="col-md-2">
    </div>
    <div class="col-md-9">
    <button type="submit" class="btn btn-primary w-250px">Order Recharge Card</button>
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