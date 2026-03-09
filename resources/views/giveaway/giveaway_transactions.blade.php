@extends('dashboard.master1')
@section('header')
@endsection
@section('content')


<div class="container-fluid">




    <div class="row mt-4">

        <div class="card mb-xl-10" style="border-left:5px solid #001f3f;background-color: hsl(210, 90%, 95%)">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                
                    <h2 class="fw-bolder">{{ $giveaway->name }} Details <span style='color:red'>(NGN{{
                            $giveaway->estimated_amount }})</span>
                    </h2>
                    <div>
                        <a href='/giveaway_participant/{{ $giveaway->slug }}' class='btn btn-secondary'>Back</a>
                    </div>
             

            </div>
        </div>
        <div class="card mb-xl-10">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title flex">
                    <h2 class="fw-bolder">{{ $giveaway->name }} Transactions
                    </h2>
                   
                </div>

            </div>
            <div class="card-body">
                <div style='overflow-x:auto;max-width: 100%'>
                    <table style='width:100%' class="datatable table table-responsive mb-0 fixed-solution">
                        <thead>
                            <tr>

                                <th scope="col">Name</th>
                                <th scope="col">Phone </th>
                               
                                <th>Actions</th>

                            </tr>
                        </thead>

                        @foreach($transactions as $key => $tranx)
                        @if($tranx->status == 1)
                        <tr class='alert alert-success'>

                            <td>{{ $tranx->name }}(Credited)</td>
                            <td> 
                                @if($tranx->type == 'cash')
                                {{ $tranx->account_no }}, {{ $tranx->bank_name }}, {{ $tranx->account_name }}
                                @else 
                                {{ $tranx->phone }}
                                @endif 
                            </td>
                           
                           
                            <td><a class='btn btn-success' href='https://wa.me/234{{ $tranx->phone }}'>Message</a>
                            </td>

                        </tr>
                        @else
                        <tr>

                            <td>{{ $tranx->name }}</td>
                            <td> @if($tranx->type == 'cash')
                                {{ $tranx->account_no }}, {{ $tranx->bank_name }}, {{ $tranx->account_name }}
                                @else 
                                {{ $tranx->phone }}
                                @endif 
                             </td>
                           
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
@section('script')
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
@endsection
@endsection