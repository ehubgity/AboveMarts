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
<h4> Deposit</small></h4>
<div class="row mt-4 mb-4">
    <div class="col-12 col-md-3 mb-2">
        <button id="manual" class="btn btn-success w-200px">Manual Funding</button>
    </div>
    <div class="col-12 col-md-3 ">
        <button id="auto" class="btn btn-warning w-200px">Automatic Funding</button>
    </div>
</div>

<form action="{{ route('pay') }}" method="post" id="formAuto" style="display: none;">
@csrf
<div class="row mb-15px" >
    <div class="col-12 mt-4 mb-4">
        <h5>
            NOTE: Gateway Charge is 1.5% of the amount. However, amount above #2500 incurs additional #100 charge  .
        </h5>
    </div>
<label class="form-label col-form-label col-md-2">Email address</label>
<div class="col-md-6">
<input class="form-control" type="email" name ="email" placeholder="Enter Email Address" required />
<small class="fs-12px text-gray-500-darker">We'll never share your email with anyone else.</small>
</div>
</div>
<div class="row mb-15px">
<label class="form-label col-form-label col-md-2">Amount</label>
<div class="col-md-6">
<input class="form-control" type="number" oninput="myFunction()" id ="amountinitial" name ="amountinitial" placeholder="Enter Amount" required />
</div>
</div>
<div class="row mb-15px">
    <label class="form-label col-form-label col-md-2">Gateway Charge </label>
    <div class="col-md-6">
    <input class="form-control" type="number" name ="fee" id="fee" value="" readonly required />
    </div>
</div>

<div class="row mb-15px">
    <label class="form-label col-form-label col-md-2">Total Amount</label>
    <div class="col-md-6">
    <input class="form-control" type="number" id="amount" name ="amount" placeholder="Enter Amount" required readonly />
    </div>
</div>

<div class="row mb-15px">
<div class="col-md-2">
</div>
<div class="col-md-9">
<button type="submit" class="btn btn-primary w-150px">Fund Wallet</button>
</div>
</div>
</form>

<form action="{{ route('manualpay') }}" method="post" id="formManual" style="display: none;">
    <div class="row mb-15px" >
    <div class="row">
        <div class="col-12 mt-4 mb-4">
            <h4>
                NOTE: KINDLY NOTIFY ADMIN AFTER PAYMENT BY FILLING THE FORM BELOW.
            </h4>
        </div>
        <div class="col-12 mb-4">
            <h6>Bank Name: Zenith Bank PLC</h6>
            <h6>Account Number: 1228669880</h6>
            <h6>Account Name: Above E-Business Hub</h6>
        </div>
        
    </div>

    </div>
    @csrf
    <div class="row mb-15px" >
    <label class="form-label col-form-label col-md-2">Depositor Name</label>
    <div class="col-md-6">
    <input class="form-control" type="text" name ="accountName" placeholder="Enter Depositor Name" required />
    </div>
    </div>
    <div class="row mb-15px" >
        {{-- <label class="form-label col-form-label col-md-2">Account Number</label> --}}
        <div class="col-md-6">
        <input class="form-control" type="text" value="none" name ="accountNumber" hidden ="Enter Account Number" required />
        </div>
    </div>
    <div class="row mb-15px" >
        <label class="form-label col-form-label col-md-2">Payment Method</label>
        <div class="col-md-6">
            <select name ="bankName" id="bankName" class="form-control"  onchange="handleChange()">
                <option value="None">Select Payment Method</option>
                <option value="Zenith Bank">Zenith Bank</option>
                <option value="Paystack">Paystack</option>
            </select>
        {{-- <input class="form-control" type="text" name ="bankName" placeholder="Enter Bank Name" required /> --}}
        </div>
    </div>
    <div class="row mb-15px">
    <label class="form-label col-form-label col-md-2">Amount</label>
    <div class="col-md-6">
    <input class="form-control" type="number"  id ="amountinit" placeholder="Enter Amount" oninput="myFunctionManual()" required />
    </div>
    </div>

    <div class="row mb-15px" >
        <label class="form-label col-form-label col-md-2">Payment Charges</label>
        <div class="col-md-6">
        <input class="form-control" type="text" id="feeManual" value="50" readonly name ="feeManual" required />
        </div>
    </div>

    <div class="row mb-15px">
        <label class="form-label col-form-label col-md-2">Total Amount</label>
        <div class="col-md-6">
        <input class="form-control" type="number" id="amountManual" readonly name ="amountManual" placeholder="Enter Amount" required />
        </div>
     </div>

     <div class="row mb-15px">
        <label class="form-label col-form-label col-md-2">Date of Payment</label>
        <div class="col-md-6">
        <input type="date" lass="form-control" id="dateInput" name="date" required>
        </div>
     </div>

    <div class="row mb-15px">
    <div class="col-md-2">
    </div>
    <div class="col-md-9">
    <button type="submit" class="btn btn-danger  w-150px">Notify Admin</button>
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
<script>
    function myFunction() {
        
        var amt = document.getElementById("amountinitial").value;
        if(amt >= 2475){
            var charges = amt * 1.5/100;
        var inputfee = document.getElementById("fee");
        var totalamount = document.getElementById("amount");

        fee.value = charges + 100;
        totalamount.value =  parseInt(amt) + charges + 100;
        }else{
            var charges = amt * 1/100;
        var inputfee = document.getElementById("fee");
        var totalamount = document.getElementById("amount");

        fee.value = charges;
        totalamount.value =  parseInt(amt) + charges ;
        }
       

    }

        document.getElementById("auto").addEventListener("click", function() {
            var div = document.getElementById("formAuto");
            var divTwo = document.getElementById("formManual");

            if (div.style.display === "none") {
                div.style.display = "block";
                divTwo.style.display = "none";

            } else {
                div.style.display = "block";
            }
        });

        document.getElementById("manual").addEventListener("click", function() {
            var div = document.getElementById("formAuto");
            var divTwo = document.getElementById("formManual");

            if (divTwo.style.display === "none") {
                divTwo.style.display = "block";
                div.style.display = "none";

            } else {
                divTwo.style.display = "block";
            }
        });

        function myFunctionManual() {
        var amt = document.getElementById("amountinit").value;
        var selectElement = document.getElementById("bankName");
    
        var inputfee = document.getElementById("feeManual").value;
        var charges = inputfee;

        var totalamount = document.getElementById("amountManual");

        feeManual.value = inputfee;
        totalamount.value =  parseInt(amt) - charges;

    }

    function handleChange() {
        var selectElement = document.getElementById("bankName");
        var amt = document.getElementById("amountinit").value;
        var totalamount = document.getElementById("amountManual");


        var selectedValue = selectElement.value;
        if(selectedValue == "Paystack") {
            feeManual.value = parseInt(0);
            totalamount.value =  parseInt(amt) - 0;
        }else{
            feeManual.value = parseInt(50);
            totalamount.value =  parseInt(amt) - 50;
        }
    }
</script>
@include('user.footer')