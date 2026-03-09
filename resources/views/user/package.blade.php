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
                <h4> Package Purchase</h4>
                <form action="{{ route('userpackage') }}" method="post">
                    @csrf
                    <div>
                        <div class="row mb-3">
                            <label class="form-label col-form-label col-md-2">Type Of Package</label>
                            <div class="col-sm-6">
                                <select class="form-select" name="migrate" id="migrate" required
                                    onChange="showMigrationInput()">
                                    <option value="NO">New Package</option>
                                    <option value="YES">Migrate Plan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-15px" id="packageSelection">
                        <label class="form-label col-form-label col-md-2">Our Packages</label>
                        <div class="col-sm-6">
                            <select class="form-select" name="package" id="regularPackage" required>
                                <option value="NONE">Select Package</option>
                                @foreach ($data as $datapackage)
                                    <option value="{{ $datapackage->packageName }}"
                                        data-amount="{{ $datapackage->packageAmount }}">
                                        {{ $datapackage->packageName }} (#{{ $datapackage->packageAmount }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-15px" id="migrationPackageSelection" style="display:none;">
                        <label class="form-label col-form-label col-md-2">Migration Packages</label>
                        <div class="col-sm-6">
                            <select class="form-select" name="packagemigrate" id="migrationPackage"
                                onChange="showMigrationValue()">
                                <option value="NONE">Select Migration Package</option>
                                @foreach ($data as $datapackage)
                                    <option value="{{ $datapackage->packageName }}"
                                        data-amount="{{ $datapackage->packageAmount }}">
                                        {{ $datapackage->packageName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-15px" id="migrationValueSection" style="display:none;">
                        <label class="form-label col-form-label col-md-2">Migration Value</label>
                        <div class="col-sm-6">
                            <input type="text" name="migrationValue" class="form-control" id="migrationValue"
                                readonly>
                        </div>
                    </div>
                    <input type="hidden" id="currentPackageAmount" value="{{ $packageAmount }}">

                    <div class="row mb-15px">
                        <div class="col-md-2"></div>
                        <div class="row mb-15px">
                            <label class="form-label col-form-label col-md-2">Select Method Payment</label>
                            <div class="col-md-6">
                                <select class="form-select" name="payment" id="payment"
                                    onChange="updatePaymentMethod()">
                                    <option value="wallet">Main Wallet</option>
                                    <option value="epin">One1Card</option>
                                    {{-- <option value="promo">Promo Wallet</option> --}}
                                </select>
                            </div>
                        </div>
                        <div id="walletpayment" style="display:none;"></div>
                        <div id="promopayment" style="display:none;">
                            <div class="mb-3">
                                <label class="col-sm-6 col-form-label">Promo Amount($)</label>
                                <div class="col-sm-6">
                                    <input type="text" name="promoamount" class="form-control" id="promoamount"
                                        placeholder="0" value="0">
                                </div>
                            </div>
                            <p style="color:red; font-size:14px;">Note: Maximum 50% can be used from promo wallet!</p>
                        </div>
                        <div id="epinpayment" style="display:none;">
                            <div class="mb-3">
                                <label class="col-sm-6 col-form-label">One1Card</label>
                                <div class="col-sm-6">
                                    <input type="text" name="epin" class="form-control" id="epin">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-10">
                        <button type="submit" class="btn btn-primary w-250px">Purchase Package</button>
                    </div>
                    <div class="col-md-10 mt-4">
                        <a href="{{ route('sponsorpackage') }}">
                            <button type="button" class="btn btn-danger w-250px">Partnership Package Details</button>
                        </a>
                    </div>
                </form>

                <script>
                    function showMigrationInput() {
                        var migrateSelect = document.getElementById('migrate').value;
                        var packageSelection = document.getElementById('packageSelection');
                        var migrationPackageSelection = document.getElementById('migrationPackageSelection');
                        var migrationValueSection = document.getElementById('migrationValueSection');


                        if (migrateSelect === 'YES') {
                            packageSelection.style.display = 'none';
                            migrationPackageSelection.style.display = 'block';
                            migrationValueSection.style.display = 'none';
                        } else {
                            packageSelection.style.display = 'block';
                            migrationPackageSelection.style.display = 'none';
                            migrationValueSection.style.display = 'none';
                        }
                    }

                    function showMigrationValue() {
                        var migrationPackageSelect = document.getElementById('migrationPackage');
                        var migrationValueSection = document.getElementById('migrationValueSection');
                        var migrationValueInput = document.getElementById('migrationValue');

                        var selectedOption = migrationPackageSelect.options[migrationPackageSelect.selectedIndex];
                        var packageAmount = parseInt(selectedOption.getAttribute('data-amount'), 10);
                        var currentPackageAmount = parseInt(document.getElementById('currentPackageAmount').value, 10);


                        if (migrationPackageSelect.value !== 'NONE') {
                            migrationValueInput.value = packageAmount - currentPackageAmount;
                            migrationValueSection.style.display = 'block';
                        } else {
                            migrationValueSection.style.display = 'none';
                        }
                    }

                    function updatePaymentMethod() {
                        var paymentMethod = document.getElementById('payment').value;
                        var walletPayment = document.getElementById('walletpayment');
                        var promoPayment = document.getElementById('promopayment');
                        var epinPayment = document.getElementById('epinpayment');

                        walletPayment.style.display = 'none';
                        promoPayment.style.display = 'none';
                        epinPayment.style.display = 'none';

                        if (paymentMethod === 'wallet') {
                            walletPayment.style.display = 'block';
                        } else if (paymentMethod === 'promo') {
                            promoPayment.style.display = 'block';
                        } else if (paymentMethod === 'epin') {
                            epinPayment.style.display = 'block';
                        }
                    }
                </script>


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
