@include('user.head')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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
        <h4 class="mb-4">CABLE Purchase</h4>

        <form action="{{ route('verifycable') }}" method="post">
            @csrf
            <!-- Cable Selection -->
            <div class="row mb-3 form-section">
                <label class="form-label col-md-2">Select Cable</label>
                <div class="col-md-6">
                    <select class="form-select" name="packagecable" id="cableSelect">
                        <option value="">Select Cable</option>
                        <option value="dstv">DSTv</option>
                        <option value="gotv">GOTv</option>
                        <option value="startimes">StarTimes</option>
                    </select>
                    <small class="text-muted">Kindly select a cable provider.</small>
                </div>
            </div>

            <!-- Package Selection -->
            <div class="row mb-3 form-section" id="package-section" style="display: none;">
                <label class="form-label col-md-2">Select Package</label>
                <div class="col-md-6">
                    <select class="form-select" name="selectedPackage" id="packageSelect">
                        <option value="">Select Package</option>
                    </select>
                    <small class="text-muted">Kindly select a cable package.</small>
                </div>
            </div>

            <!-- Smart Card Number -->
            <div class="row mb-3 form-section" id="smartcard-section" style="display: none;">
                <label class="form-label col-md-2">Smart Card Number</label>
                <div class="col-md-6">
                    <input class="form-control" type="text" id="smartNumber" name="smartNumber"
                        placeholder="Enter Smart Card Number" required />
                    <small class="text-muted">Enter your smart card number for verification.</small>
                </div>
            </div>

            <!-- Amount Display -->
            <div class="row mb-3 form-section" id="amount-section" style="display: none;">
                <label class="form-label col-md-2">Amount (₦)</label>
                <div class="col-md-6">
                    <input class="form-control" type="text" id="amountDisplay" name="amount"
                        placeholder="Package amount" readonly />
                    <input type="hidden" id="variationId" name="variation_id" />
                    <input type="hidden" id="serviceId" name="service_id" />
                    <input type="hidden" id="packagebouquetId" name="package_bouquet" />
                    <input type="hidden" id="actualAmount" name="actual_amount" />
                </div>
            </div>

            <!-- Service Fee -->
            <div class="row mb-3 form-section" id="service-fee-section" style="display: none;">
                <label class="form-label col-md-2">Service Fee (₦)</label>
                <div class="col-md-6">
                    <input class="form-control" type="text" value="50" name="serviceFee" readonly />
                </div>
            </div>

            <!-- Total Amount -->
            <div class="row mb-3 form-section" id="total-section" style="display: none;">
                <label class="form-label col-md-2">Total Amount (₦)</label>
                <div class="col-md-6">
                    <input class="form-control fw-bold" type="text" id="totalAmount" name="amount" readonly />
                </div>
            </div>

            <!-- Payment Method -->
            <div class="row mb-3 form-section" id="payment-section" style="display: none;">
                <label class="form-label col-md-2">Payment Method</label>
                <div class="col-md-6">
                    <select class="form-select" name="payment" id="payment">
                        <option value="wallet">Main Wallet</option>
                        <option value="card">Debit Card</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="row mb-3 form-section" id="submit-section" style="display: none;">
                <div class="col-md-2"></div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <span id="button-text">Purchase Cable Subscription</span>
                        <span id="loading-spinner" class="spinner-border spinner-border-sm ms-2" style="display: none;"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Cable variations data
    // const cableVariations = {
    //     'dstv': [{
    //             variation_id: "dstv-padi",
    //             package_bouquet: "DSTv Padi",
    //             price: 2500
    //         },
    //         {
    //             variation_id: "dstv-yanga",
    //             package_bouquet: "DSTv Yanga",
    //             price: 3500
    //         },
    //         {
    //             variation_id: "dstv-confam",
    //             package_bouquet: "DSTv Confam",
    //             price: 5500
    //         },
    //         {
    //             variation_id: "dstv79",
    //             package_bouquet: "DSTv Compact",
    //             price: 10500
    //         },
    //         {
    //             variation_id: "dstv7",
    //             package_bouquet: "DSTv Compact Plus",
    //             price: 16600
    //         },
    //         {
    //             variation_id: "dstv3",
    //             package_bouquet: "DSTv Premium",
    //             price: 26000
    //         }
    //     ],
    //     'gotv': [{
    //             variation_id: "gotv-smallie",
    //             package_bouquet: "GOTv Smallie",
    //             price: 1900
    //         },
    //         {
    //             variation_id: "gotv-jinja",
    //             package_bouquet: "GOTv Jinja",
    //             price: 3900
    //         },
    //         {
    //             variation_id: "gotv-jolli",
    //             package_bouquet: "GOTv Jolli",
    //             price: 5800
    //         },
    //         {
    //             variation_id: "gotv-max",
    //             package_bouquet: "GOTv Max",
    //             price: 8500
    //         },
    //         {
    //             variation_id: "gotv-supa",
    //             package_bouquet: "GOTv Supa",
    //             price: 11400
    //         },
    //         {
    //             variation_id: "gotv-supa-plus",
    //             package_bouquet: "GOTv Supa Plus",
    //             price: 16800
    //         }
    //     ],
    //     'startimes': [{
    //             variation_id: "startimes-nova",
    //             package_bouquet: "StarTimes Nova",
    //             price: 1200
    //         },
    //         {
    //             variation_id: "startimes-basic",
    //             package_bouquet: "StarTimes Basic",
    //             price: 2000
    //         },
    //         {
    //             variation_id: "startimes-smart",
    //             package_bouquet: "StarTimes Smart",
    //             price: 2800
    //         },
    //         {
    //             variation_id: "startimes-classic",
    //             package_bouquet: "StarTimes Classic",
    //             price: 3500
    //         },
    //         {
    //             variation_id: "startimes-super",
    //             package_bouquet: "StarTimes Super",
    //             price: 5000
    //         }
    //     ]
    // };
    const cableVariations = @json($cableVariations);

    // DOM elements
    const cableSelect = document.getElementById('cableSelect');
    const packageSelect = document.getElementById('packageSelect');
    const smartCardInput = document.getElementById('smartNumber');
    const amountDisplay = document.getElementById('amountDisplay');
    const totalAmountDisplay = document.getElementById('totalAmount');
    const variationIdInput = document.getElementById('variationId');
    const packageBouquetIdInput = document.getElementById('packagebouquetId')
    const serviceIdInput = document.getElementById('serviceId');
    const actualAmountInput = document.getElementById('actualAmount');
    const form = document.getElementById('cable-form');
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const loadingSpinner = document.getElementById('loading-spinner');

    // Service fee
    const serviceFee = 50;

    // Event listeners
    cableSelect.addEventListener('change', handleCableChange);
    packageSelect.addEventListener('change', handlePackageChange);
    smartCardInput.addEventListener('input', validateForm);
    form.addEventListener('submit', handleFormSubmit);

    function handleCableChange() {
        const selectedCable = cableSelect.value;

        // Reset form
        resetForm();

        if (selectedCable && cableVariations[selectedCable]) {
            // Show package section
            showElement('package-section');

            // Populate packages
            populatePackages(selectedCable);
        }
    }

    function populatePackages(cableType) {
        // Clear existing options
        packageSelect.innerHTML = '<option value="">Select Package</option>';

        // Add new options
        const variations = cableVariations[cableType];
        variations.forEach(variation => {
            const option = document.createElement('option');
            option.value = JSON.stringify(variation);
            option.textContent = `${variation.package_bouquet} - ₦${variation.price.toLocaleString()}`;
            packageSelect.appendChild(option);
        });
    }

    function handlePackageChange() {
        const selectedPackage = packageSelect.value;

        if (selectedPackage) {
            const packageData = JSON.parse(selectedPackage);

            // Update form fields
            amountDisplay.value = `₦${packageData.price.toLocaleString()}`;
            variationIdInput.value = packageData.variation_id;
            packageBouquetIdInput.value = packageData.package_bouquet;
            serviceIdInput.value = cableSelect.value;
            actualAmountInput.value = packageData.price;

            // Calculate and show total
            const total = Number(packageData.price) + Number(serviceFee);
            // totalAmountDisplay.value = `${total.toLocaleString()}`;
            totalAmountDisplay.value = total;


            // Show subsequent sections
            showElement('smartcard-section');
            showElement('amount-section');
            showElement('service-fee-section');
            showElement('total-section');
            showElement('payment-section');

            validateForm();
        } else {
            hideSubsequentSections();
        }
    }

    function validateForm() {
        const isValid = cableSelect.value &&
            packageSelect.value &&
            smartCardInput.value.trim().length >= 10;

        if (isValid) {
            showElement('submit-section');
            submitButton.disabled = false;
        } else {
            hideElement('submit-section');
            submitButton.disabled = true;
        }
    }

    function handleFormSubmit(e) {
        e.preventDefault();

        // Show loading state
        buttonText.textContent = 'Processing...';
        loadingSpinner.style.display = 'inline-block';
        submitButton.disabled = true;

        // Simulate form submission
        setTimeout(() => {
            alert('Cable subscription purchase initiated successfully!');

            // Reset loading state
            buttonText.textContent = 'Purchase Cable Subscription';
            loadingSpinner.style.display = 'none';
            submitButton.disabled = false;

            // Reset form
            form.reset();
            resetForm();
        }, 2000);
    }

    function resetForm() {
        hideSubsequentSections();
        packageSelect.innerHTML = '<option value="">Select Package</option>';

        // Clear all inputs
        smartCardInput.value = '';
        amountDisplay.value = '';
        totalAmountDisplay.value = '';
        variationIdInput.value = '';
        serviceIdInput.value = '';
        actualAmountInput.value = '';
        packageBouquetIdInput.value = '';

    }

    function hideSubsequentSections() {
        const sections = [
            'package-section',
            'smartcard-section',
            'amount-section',
            'service-fee-section',
            'total-section',
            'payment-section',
            'submit-section'
        ];

        sections.forEach(sectionId => hideElement(sectionId));
    }

    function showElement(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.style.display = 'block';
            // Add smooth animation
            element.style.opacity = '0';
            setTimeout(() => {
                element.style.transition = 'opacity 0.3s ease';
                element.style.opacity = '1';
            }, 10);
        }
    }

    function hideElement(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.style.display = 'none';
        }
    }

    // Initialize form
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Cable purchase form initialized successfully');
        resetForm();
    });
</script>
@include('user.footer')