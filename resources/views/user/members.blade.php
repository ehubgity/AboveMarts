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
<p class="mb-2">{{ auth()->user()->package }} Package</p>
</div>
</div>


</div>
</div>


<div class="profile-content">

<div class="tab-content p-0">

<div class="tab-pane fade show active" id="profile-about">

<section>
    <div class="container z-index-2 position-relative">
        <div class="section-heading mb-8 wow fadeIn" data-wow-delay="100ms">
            {{-- <span class="subtitle">AboveMarts Partnership Plans And Benefits</span> --}}
            <h2 class="w-100">My Team <span class="font-weight-400">Members</span></h2>
        </div>
        <div class="row mb-15px mt-4 mb-4">
            <div class="col-md-1">
            </div>
            <label class="form-label col-form-label col-md-2">Affliate Link</label>
            <div class="col-md-7">
                 <div class='alert alert-success' style='border:2px dotted #155724;'>
        <h4>Invite & Earn</h4>
        Refer your Family & Friends to Earn Bonus & Points. <br>Receive from <b>#10k</b> to <b>#50M</b> FREE Cash Grants!
       <!--Refer a friend and receive #1,000 Bonus for every 5 Activations! Upgrade & Earn up to 50% Cashbacks & Commissions.-->
        
        <div class="container">
        <input type="text" id="referralCode" class='form form-control form-control-sm' value="{{ Route('register', ['ref' => auth()->user()->mySponsorId]) }}" readonly>
        <button id='referalButton' class='btn btn-success' onclick="copyReferralCode()"><i class='fa fa-copy'></i></button>
    </div>
    </div>
                <!--<input type="text" class="form-control"  placeholder="{{ Route('register',['ref' => auth()->user()->mySponsorId]) }}" value="{{ Route('register',['ref' => auth()->user()->mySponsorId]) }}" disabled>    -->
            </div>
        </div>

        <div class="row">

            <div class="col-xl-12">
            
            <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                        
            <div class="panel-body">

            @if(auth()->user()->uplineOne != 'Admin')
            <div class="table-responsive">
            <table class="table mb-0">
            <thead>
            <tr>
            <th>Mentor</th>
            </tr>
            </thead>
            <tbody>
            <tr>
            @if(auth()->user()->uplineOne != 'Admin')
            <td>{{ auth()->user()->uplineOne }}</td>
            @else
            <td></td>
            @endif
           
            </tbody>
            </table>
            </div>
            @endif
            </div>
            
            
    </div>
    <div class="d-sm-inline-block d-none p-2 bg-primary rounded-circle position-absolute right-5 bottom-25 ani-left-right"></div>
    <div class="d-sm-inline-block d-none p-2 border-secondary border border-width-2 position-absolute right-10 top-25 ani-move"></div>
    <div class="d-inline-block px-5 py-6 border position-absolute left-5 top-5 border-radius-10 ani-move"></div>
</section>
@if($downlines->count() > 0 )
    {{-- <section style="margin-top:-18%;">
        <div class="container z-index-2 position-relative">
            <div class="section-heading mb-8 wow fadeIn" data-wow-delay="100ms">
                <span class="subtitle">AboveMarts Partnership Plans And Benefits</span>
                <h2 class="w-100">My Clients<span class="font-weight-400"> Records</span></h2>
            </div>
    </section>     --}}
        <div class="alert alert-secondary alert-dismissible rounded-0 mb-0 fade show">
    
        {{-- Transaction Activity --}}
        <div class="row">
            <div class="col-lg-6">
                <h2 class="w-100">My Clients<span class="font-weight-400"> Records</span></h2>
            </div>
            <div class="col-lg-6">
                <form class="d-flex" method="GET" action="{{ route('member') }}">
                    <input class="form-control me-2 w-100 mb-4" name="query" type="search" placeholder="Search For Team Member" aria-label="Search">
                    <button class="btn btn-outline-primary" type="submit">Search</button>
                </form>
            </div>
        </div>
       
          
        </div>

        @if(isset($query))
        <h4>Search results for "{{ $query }}"</h4>

        <div class="panel-body"  >
        <table id="data-table-responsive"class="table table-striped table-bordered align-middle">
        <thead>
        <tr>
        <th class="text-nowrap">Username</th>
        <th class="text-nowrap">Fullname</th>
        <th class="text-nowrap">Email</th>
        <th class="text-nowrap">Phone Number</th>
        <th class="text-nowrap">Rank</th>
        <th class="text-nowrap">Package</th>
        {{-- <th class="text-nowrap">Status</th>
        <th class="text-nowrap">Date</th> --}}

        </tr>
        </thead>
        @foreach ( $datas as $data )

        <tbody>
        <tr class="odd gradeX">
        <td>{{ $data->downline }}</td>
        <td>{{ $data->fullname }}</td>
        <td>{{ $data->email }}</td>
        <td>{{ $data->phoneNumber }}</td>
        <td>{{ $data->rank }}</td>
        <td>{{ $data->package }}</td>
        {{-- <td>{{ $downline->status }}</td>
        <td>{{ $downline->created_at }}</td> --}}
        </tr>
        </tr>
        </tbody>
        @endforeach
        </table>
        {{ $downlines->links() }}

        </div>

        @else
        <div class="panel-body"  >
            <table id="data-table-responsive"class="table table-striped table-bordered align-middle">
            <thead>
            <tr>
            <th class="text-nowrap">Username</th>
            <th class="text-nowrap">Fullname</th>
            <th class="text-nowrap">Email</th>
            <th class="text-nowrap">Phone Number</th>
            <th class="text-nowrap">Rank</th>
            <th class="text-nowrap">Package</th>
            {{-- <th class="text-nowrap">Status</th>
            <th class="text-nowrap">Date</th> --}}
    
            </tr>
            </thead>
            @foreach ( $downlines as $downliner )
    
            <tbody>
            <tr class="odd gradeX">
            <td>{{ $downliner->downline }}</td>
            <td>{{ $downliner->fullname }}</td>
            <td>{{ $downliner->email }}</td>
            <td>{{ $downliner->phoneNumber }}</td>
            <td>{{ $downliner->rank }}</td>
            <td>{{ $downliner->package }}</td>
            {{-- <td>{{ $downline->status }}</td>
            <td>{{ $downline->created_at }}</td> --}}
            </tr>
            </tr>
            </tbody>
            @endforeach
            </table>
            {{ $downlines->links() }}

            </div>

            @endif
        </div>
        </div>
        <div class="d-sm-inline-block d-none p-2 bg-primary rounded-circle position-absolute right-5 bottom-25 ani-left-right"></div>
        <div class="d-sm-inline-block d-none p-2 border-secondary border border-width-2 position-absolute right-10 top-25 ani-move"></div>
        <div class="d-inline-block px-5 py-6 border position-absolute left-5 top-5 border-radius-10 ani-move"></div>
@endif
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

</div>

 <script>
        function copyReferralCode() {
            // Get the input field
            var referralCodeField = document.getElementById("referralCode");

            // Select the text in the input field
            referralCodeField.select();
            referralCodeField.setSelectionRange(0, 99999); /*For mobile devices*/

            // Copy the selected text to the clipboard
            document.execCommand("copy");

            // Deselect the text
            referralCodeField.setSelectionRange(0, 0);

            // Optionally, provide some visual feedback to the user (e.g., changing button text)
            // For simplicity, this example changes the button text briefly
            var copyButton = document.getElementById("referalButton");
            copyButton.textContent = "Copied!";
            setTimeout(function() {
                copyButton.textContent = "Copy";
            }, 1500);
        }
    </script>

@include('user.footer')