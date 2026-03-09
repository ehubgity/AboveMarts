@php
use Illuminate\Support\Facades\Crypt;
@endphp

@include("user.head")

<body>
    <style>
        .card {
            border: 1px solid #ccc;
            padding: 6px;
            margin-bottom: 20px;
        }

        .card h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 12px;
            margin-bottom: 5px;
        }

        @media print {

            html,
            body {
                height: 297mm;
                width: 210mm;
            }

        }
    </style>
    <div class="container-fluid">
        <div class="row">
            @foreach ( $cards as $card )
            @if($card->network == 'mtn')
            <div class="col-6">
                <div class="card">
                    <div class="row">
                        <div class="col-2">
                            <div class="row">
                                <div class="col-0"></div>
                                <div class="col-12">
                                    <img src="assets/img/mtnlogo.png" alt="" width="80" style="">
                                </div>

                            </div>
                        </div>
                        <div class="col-8">
                            <h6>{{ $data->businessName }} </h6>
                        </div>
                        <div class="col-2">
                            <div class="row">
                                <div class="col-0"></div>
                                <div class="col-12">
                                    <img src="assets/img/mtnlogo.png" alt="" width="80">
                                </div>

                            </div>
                        </div>
                        <div class="col-3"></div>
                        <div class="col-5">
                        </div>
                        <div class="col-4">
                            <h6> #{{ $card->amount }}</h6>
                        </div>
                        <div class="col-12">
                            <h6>Pin: {{ $card->pin }}</h6>
                        </div>

                        <div class="col-12">
                            <p>S/N: {{ $card->serialNumber }}</p>
                        </div>
                        <div class="col-12">
                            <p>Date: {{ $card->created_at }} </p>
                        </div>
                        <div class="col-12">
                            <p> Balance: *310# Recharge: *311*PIN#</p>
                        </div>
                        {{-- <div class="col-4">
                                <p> <strong>Code: *555*pin#</strong> </p>
                            </div> --}}
                    </div>

                </div>
            </div>
            @elseif($card->network == "airtel")
            <div class="col-6">
                <div class="card">
                    <div class="row">
                        <div class="col-2">
                            <div class="row">
                                <div class="col-0"></div>
                                <div class="col-12">
                                    <img src="assets/img/airtellogo.png" alt="" width="80" style="">
                                </div>

                            </div>
                        </div>
                        <div class="col-8">
                            <h6>{{ $data->businessName }} </h6>
                        </div>
                        <div class="col-2">
                            <div class="row">
                                <div class="col-0"></div>
                                <div class="col-12">
                                    <img src="assets/img/airtellogo.png" alt="" width="80">
                                </div>

                            </div>
                        </div>
                        <div class="col-3"></div>
                        <div class="col-5">
                        </div>
                        <div class="col-4">
                            <h6> #{{ $card->amount }}</h6>
                        </div>
                        <div class="col-12">
                            <h6>Pin: {{ $card->pin }}</h6>
                        </div>

                        <div class="col-12">
                            <p>S/N: {{ $card->serialNumber }} </p>
                        </div>
                        <div class="col-12">
                            <p>Date: {{ $card->created_at }} </p>
                        </div>
                        <div class="col-12">
                            <p> Balance: *310# Recharge: *311*PIN#</p>
                        </div>

                        {{-- <div class="col-4">
                            <p> <strong>Code: *555*pin#</strong> </p>
                        </div> --}}
                    </div>

                </div>
            </div>
            @elseif($card->network == "glo")
            <div class="col-4">
                <div class="card">
                    <div class="row">
                        <div class="col-2">
                            <div class="row">
                                <div class="col-0"></div>
                                <div class="col-12">
                                    <img src="assets/img/glo.jpg" alt="" width="80" style="">
                                </div>

                            </div>
                        </div>
                        <div class="col-8">
                            <h6>{{ $data->businessName }} </h6>
                        </div>
                        <div class="col-2">
                            <div class="row">
                                <div class="col-0"></div>
                                <div class="col-12">
                                    <img src="assets/img/glo.jpg" alt="" width="80">
                                </div>

                            </div>
                        </div>
                        <div class="col-3"></div>
                        <div class="col-5">
                        </div>
                        <div class="col-4">
                            <h6> #{{ $card->amount }}</h6>
                        </div>
                        <div class="col-12">
                            <h6>Pin: {{ ($card->pin) }}</h6>
                        </div>

                        <div class="col-12">
                            <p>S/N: {{ $card->serialNumber }} </p>
                        </div>
                        <div class="col-12">
                            <p>Date: {{ $card->created_at }} </p>
                        </div>

                        <div class="col-12">
                            <p> Balance: *310# Recharge: *311*PIN#</p>
                        </div>

                        {{-- <div class="col-4">
                            <p> <strong>Code: *555*pin#</strong> </p>
                        </div> --}}
                    </div>

                </div>
            </div>
            @elseif($card->network == "9mobile")
            <div class="col-6">
                <div class="card">
                    <div class="row">
                        <div class="col-2">
                            <div class="row">
                                <div class="col-0"></div>
                                <div class="col-12">
                                    <img src="assets/img/9mobile.png" alt="" width="80" style="">
                                </div>

                            </div>
                        </div>
                        <div class="col-8">
                            <h6>{{ $data->businessName }} </h6>
                        </div>
                        <div class="col-2">
                            <div class="row">
                                <div class="col-0"></div>
                                <div class="col-12">
                                    <img src="assets/img/9mobile.png" alt="" width="80">
                                </div>

                            </div>
                        </div>
                        <div class="col-3"></div>
                        <div class="col-5">
                        </div>
                        <div class="col-4">
                            <h6> #{{ $card->amount }}</h6>
                        </div>
                        <div class="col-12">
                            <h6>Pin: {{ $card->pin }}</h6>
                        </div>

                        <div class="col-12">
                            <p>S/N: {{ $card->serialNumber }} </p>
                        </div>
                        <div class="col-12">
                            <p>Date: {{ $card->created_at }} </p>
                        </div>

                        <div class="col-12">
                            <p> Balance: *310# Recharge: *311*PIN#</p>
                        </div>

                        {{-- <div class="col-4">
                            <p> <strong>Code: *555*pin#</strong> </p>
                        </div> --}}
                    </div>

                </div>
            </div>
            @else
            @endif
            @endforeach


        </div>
        <div class="row">

            <div class="col-8">
                {{-- <button type="submit" class="btn btn-primary" width="100">Print Recharge Card</button> --}}
                <button class="btn btn-primary m-2" onclick="printTable()">Print Recharge Card</button>

            </div>

        </div>

    </div>

    @include("user.footer")