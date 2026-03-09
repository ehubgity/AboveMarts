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
                <form action="{{ route('userpackage') }}" method="post">
                    @csrf

                    <section style="margin-top:-5%;">
                        <div class="container z-index-2 position-relative">
                            <div class="section-heading mb-8 wow fadeIn" data-wow-delay="100ms">
                                {{-- <span class="subtitle">AboveMarts Partnership Plans And Benefits</span> --}}
                                <h2 class="w-100">Career<span class="font-weight-400"> Plan</span></h2>

                            </div>
                    </section>

                    <div class="alert alert-secondary alert-dismissible rounded-0 mb-0 fade show">


                    </div>

                    <div class="panel-body">
                        <table id="data-table-responsive" class="table table-striped table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">Status</th>
                                    <th class="text-nowrap">Total Points</th>
                                    <th class="text-nowrap">Mentor Points</th>
                                    <th class="text-nowrap">Cash Grant</th>
                                    <th class="text-nowrap">Total Grant</th>

                                </tr>
                            </thead>

                            <tbody>

                                <tr class="odd gradeX">

                                    <td>Feeder One</td>
                                    <td>1</td>
                                    <td>0.10</td>
                                    <td>10,000</td>
                                    <td>10,000</td>
                                </tr>

                                <tr class="odd gradeX">

                                    <td>Feeder Two</td>
                                    <td>4</td>
                                    <td>0.30</td>
                                    <td>25,000</td>
                                    <td>35,000</td>
                                </tr>
                                <tr class="odd gradeX">

                                    <td>Feeder Three</td>
                                    <td>10</td>
                                    <td>0.50</td>
                                    <td>50,000</td>
                                    <td>85,000</td>
                                </tr>
                                <tr class="odd gradeX">

                                    <td>Team Leader </td>
                                    <td>20</td>
                                    <td>1</td>
                                    <td>100,000</td>
                                    <td>185,000</td>
                                </tr>


                                <tr class="odd gradeX">
                                    <td>Group Leader</td>
                                    <td>60</td>
                                    <td>3</td>
                                    <td>250,000</td>
                                    <td>435,000</td>
                                </tr>

                                <tr class="odd gradeX">
                                    <td>Nation Builder</td>
                                    <td>120</td>
                                    <td>5</td>
                                    <td>500,000</td>
                                    <td>935,000</td>
                                </tr>

                                <tr class="odd gradeX">
                                    <td>Team Manager</td>
                                    <td>250</td>
                                    <td>10</td>
                                    <td>1,000,000</td>
                                    <td>1,935,000</td>
                                </tr>

                                <tr class="odd gradeX">
                                    <td>Senior Manager</td>
                                    <td>500</td>
                                    <td>30</td>
                                    <td>2,000,000</td>
                                    <td>3,935,000</td>
                                </tr>

                                <tr class="odd gradeX">
                                    <td>Exective Manager</td>
                                    <td>1,000</td>
                                    <td>50</td>
                                    <td>3,000,000</td>
                                    <td>6,935,000</td>
                                </tr>

                                <tr class="odd gradeX">
                                    <td>Executive Director</td>
                                    <td>2,000</td>
                                    <td>100</td>
                                    <td>7,000,000</td>
                                    <td>13,935,000</td>
                                </tr>

                                <tr class="odd gradeX">
                                    <td>Regional Director </td>
                                    <td>4,000</td>
                                    <td>300</td>
                                    <td>12,000,000</td>
                                    <td>25,935,000</td>
                                </tr>

                                <tr class="odd gradeX">
                                    <td>Brand Ambassador </td>
                                    <td>7,500</td>
                                    <td>500</td>
                                    <td>25,000,000</td>
                                    <td>50,935,000</td>
                                </tr>

                                </tr>
                                </tr>
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
        <div
            class="d-sm-inline-block d-none p-2 bg-primary rounded-circle position-absolute right-5 bottom-25 ani-left-right">
        </div>
        <div
            class="d-sm-inline-block d-none p-2 border-secondary border border-width-2 position-absolute right-10 top-25 ani-move">
        </div>
        <div class="d-inline-block px-5 py-6 border position-absolute left-5 top-5 border-radius-10 ani-move"></div>

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
