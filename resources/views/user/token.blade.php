@include('user.head')
@include('user.header')
<style>
   
    .container {
        margin-top: 100px;
        text-align: center;

    }
    .success-message {
        color: green;
        font-size: 24px;
    }
</style>
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
<h4> Electricity Token</small></h4>

    <div class="row mb-15px" id="amount">
        <div class="container">
            <h1>Your Successful Token Is:</h1>
            <p class="success-message">{{ $tokendata->token }}</p>
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

</div>

@include('user.footer')