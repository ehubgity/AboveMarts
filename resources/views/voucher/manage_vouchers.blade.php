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

       
        <div class="card mb-xl-10">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
              
                   <h2 class="fw-bolder">Manage Tokens</h2>
                  <form method="post" action="{{route('updateRate')}}" class="d-inline"> @csrf
                        <div class="input-group">
                            <input type="text" value='{{$voucher_rate ?? ""}}' class="form-control" name="rate" placeholder="Enter rate" />
                            <button type="submit" class="btn btn-dark">Update Dollar Rate</button>
                        </div>
                    </form>
                   <a class='btn btn-success' href='/create-voucher'>Create Token</a>
                   

            </div>
            <div class="card-body">
                <div style='overflow-x:auto;max-width: 100%'>
                    <table style='width:100%' class="datatable table table-responsive mb-0 fixed-solution">
                        <thead>
                            <tr>

                                <th scope="col">S/N</th>
                                <th scope="col">Token </th>
                                 <th scope="col">Price </th>
                                  <th scope="col">Status </th>
                                   <th scope="col">Purchased By </th>
                                <th scope="col">Created By</th>
                                <th>Actions</th>

                            </tr>
                        </thead>

                        @foreach($vouchers as $key => $voucher)
                       
                        <tr>

                            <td>{{ ++$key }}</td>
                            <td>{{ $voucher->voucher}}</td>
                           <td>${{ number_format($voucher->price)}}</td>
                            <td>@if($voucher->status == 1) Purchased @else Not Yet Purchased @endif</td>
                             <td>{{ $voucher->user->username ?? ""}}</td>
                              <td>{{ $voucher->creator->username ?? ""}}</td>
                          
                            <td><a href='/delete_voucher/{{$voucher->id}}' onclick="return confirm('Are you sure you want to delete this voucher?');" class='btn btn-danger'>Delete</a>
                            </td>

                        </tr>
                       


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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        
           @if (session('message'))
        Swal.fire({
                        icon: 'success',
                        title: '{{ session("message") }}'
                        }) 
           
        @endif
      

    })
</script>
    @include('user.footer')
