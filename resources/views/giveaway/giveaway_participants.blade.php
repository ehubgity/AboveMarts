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




    <div class="row mt-4">

        <div class="card mb-xl-10" style="border-left:5px solid #001f3f;background-color: hsl(210, 90%, 95%)">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h2 class="fw-bolder">{{ $giveaway->name }} Details <span style='color:red'>(NGN{{
                            $giveaway->estimated_amount }})</span>
                    </h2>
                </div>

            </div>
            @if($giveaway->type == 'raffle_data' || $giveaway->type == 'raffle_airtime' || $giveaway->type ==
            'raffle_cash' )
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">{{ count($giveaway->all_numbers ?? []) }} / {{ $giveaway->part_no }}</h4>
                        <a class="text-muted fw-medium">Total Participants</a>
                        <h4 class="mb-0">{{ count($giveaway->lucky_numbers) - count($giveaway->lucky_numbers_confirm) }}
                            / {{
                            count($giveaway->lucky_numbers) }}</h4>
                        <a class="text-muted fw-medium">Total Claimed Giveaway</a>
                        <h4 class="mb-0">
                            @foreach($giveaway->lucky_numbers as $luk)
                            {{ $luk }},
                            @endforeach
                        </h4>
                        <a class="text-muted fw-medium">Lucky Numbers</a>
                        <h4 class="mb-0"> {{ $giveaway->data_price }} {{ $giveaway->airtime_price }}</h4>
                        <a class="text-muted fw-medium">Giveaway Prize</a>


                    </div>


                </div>
            </div>



            @else
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">{{ $giveaway->no_of_winners - $giveaway->max_winners }}/{{
                            $giveaway->no_of_winners }}</h4>
                        <a class="text-muted fw-medium">Total Winners</a>

                        <h4 class="mb-0">
                            {{ count($giveaway->all_questions->all()) }}
                        </h4>
                        <a class="text-muted fw-medium">Total Questions</a>
                        <h4 class="mb-0">
                            {{ $giveaway->time }} Mins
                        </h4>
                        <a class="text-muted fw-medium">Assessment Duration</a>
                        <h4 class="mb-0"> {{ $giveaway->data_price }} {{ $giveaway->airtime_price }}</h4>
                        <a class="text-muted fw-medium">Giveaway Prize</a>


                    </div>


                </div>
            </div>

            @endif
        </div>
        <div class="card mb-xl-10">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
              
                    <h2 class="fw-bolder">{{ $giveaway->name }} Participants
                    </h2>
                    <div>
                       
                        <a href='/my-giveaway' class='btn btn-secondary'>Back</a>
                    </div>
               

            </div>
            <div class="card-body">
                <div style='overflow-x:auto;max-width: 100%'>
                    <table style='width:100%' class="datatable table table-responsive mb-0 fixed-solution">
                        <thead>
                            <tr>

                                <th scope="col">Name</th>
                                <th scope="col">Phone </th>
                                @if($giveaway->type == 'raffle_data' || $giveaway->type == 'raffle_airtime' ||
                                $giveaway->type == 'raffle_cash' )

                                <th scope="col">Lucky Number</th>
                                @endif
                                <th>Actions</th>

                            </tr>
                        </thead>

                        @foreach($participants as $key => $tranx)
                        @if($tranx->is_win == 1)
                        <tr class='alert alert-success'>

                            <td>{{ $tranx->name }}(Winner)</td>
                            <td>{{ $tranx->phone }}</td>
                            @if($giveaway->type == 'raffle_data' || $giveaway->type == 'raffle_airtime' ||
                            $giveaway->type == 'raffle_cash' )

                            <td>{{ $tranx->lucky_number }}</td>
                            @endif
                            <td><a class='btn btn-success' href='https://wa.me/234{{ $tranx->phone }}'>Message</a>
                            </td>

                        </tr>
                        @else
                        <tr>

                            <td>{{ $tranx->name }}</td>
                            <td>{{ $tranx->phone }}</td>
                            @if($giveaway->type == 'raffle_data' || $giveaway->type == 'raffle_airtime' ||
                            $giveaway->type == 'raffle_cash' )

                            <td>{{ $tranx->lucky_number }}</td>
                            @endif
                            <td><a class='btn btn-success' href='https://wa.me/234{{ $tranx->phone }}'>Message</a>
                            </td>

                        </tr>
                        @endif


                        @endforeach



                    </table>
                </div>
            </div>


        </div>

    </div>
</div>
<!-- end row -->



<!-- end row -->
</div>
<script>
    $(document).ready(function() {
       
        var oTable = $('.datatable').DataTable({
            ordering: false,
            searching: true
            });   

      


            $('#searchTable').on('keyup', function() {
              oTable.search(this.value).draw();
            });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
                })
                @if (session('success'))
        Toast.fire({
                        icon: 'success',
                        title: '{{ session("success") }}'
                        }) 
           
        @endif
      

    })
</script>
    @include('user.footer')
