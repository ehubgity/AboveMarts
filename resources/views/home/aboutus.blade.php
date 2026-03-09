@include('home.head')
@include('home.header')
<!-- PAGE TITLE
        ================================================== -->
        <section class="top-position1 py-0">
            <div class="page-title-section bg-img cover-background left-overlay-dark" data-overlay-dark="7" data-background="img/bg/bg-03.jpg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <h1>About Us</h1>
                            <div class="breadcrumb">
                                <ul>
                                    <li><a href="{{ route('home') }}">Home</a></li>
                                    <li><a href="#!">Pages</a></li>
                                    <li><a href="#!">About Us</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <span class="page-title-shape1 d-none d-sm-block"></span>
            <span class="page-title-shape2 d-none d-sm-block"></span>
            <div class="d-inline-block p-2 border-secondary border border-width-2 position-absolute left-5 bottom-10 bottom-sm-25 ani-left-right z-index-1"></div>
            <div class="d-inline-block p-2 bg-secondary rounded-circle position-absolute right-40 top-25 ani-move z-index-1"></div>
        </section>

        <!-- ABOUTUS
        ================================================== -->
        <section
        class="bg-light pt-16 pt-md-18 pt-lg-22 about-style1 overflow-visible"
      >
        <div class="container">
          <div class="row align-items-xl-center">
            <div
              class="col-lg-6 mb-1-9 mb-sm-2-2 mb-lg-0 wow fadeIn"
              data-wow-delay="200ms"
            >
              <div class="position-relative">
                <div
                  class="text-center text-sm-end text-md-center text-lg-end pe-xxl-1-9 overflow-hidden position-relative"
                >
                  <img src="img/content/about-01.jpg" alt="..." />
                  <span class="about-shape1"></span>
                  <span class="about-shape2"></span>
                </div>
                <img
                  src="img/content/about-02.jpg"
                  class="border-radius-10 position-absolute top-15 d-none d-sm-block"
                  alt="..."
                />
                <div
                  class="bg-white text-center border-radius-10 p-1-9 d-inline-block position-absolute bottom-10 left-10"
                >
                  <h4 class="h1"><span class="countup">10</span>+</h4>
                  <span>Years of experience</span>
                </div>
              </div>
            </div>
            <div class="col-lg-6 wow fadeIn" data-wow-delay="400ms">
              <div class="ps-xl-6">
                <h2 class="h1 mb-4 font-weight-700">
                  The Business of the 21st Century 
                  <span class="font-weight-400">- AboveMarts</span>
                </h2>
                <p class="lead text-primary">
                  AboveMarts is a Multipurpose Digital Business & Investment Platform which has been proven by industry experts to be authentic, reliable and indefinitely sustainable. We provide a range of products and services that are innovative, impeccable and necessary for day to day living.</p>
                  <p>
                    Our mission is to be the leading Fintech Social Enterprise in Africa and for the World. Our solutions span across various categories including; Online Shopping and Selling, Printing of Recharge Cards, Airtime VTU, Cheap Data Bundles, BulkSMS Services, Bill Payments, POS Solution, Electricity & TV Subscriptions, Digital Finance, Capacity Building and much more.                  </p>
                  <p >
                    Our services are designed to ensure total convenience and customer satisfaction. AboveMarts is dedicated towards serving our Customers and empowering our Partners by ensuring their day to day Shopping and Payments are Convenient and Rewarding. A large portion of our income is distributed among our Partners, Stakeholders, Charity Support and Community Development.  
                  </p>
                  <p class="">
                    Join us today to start enjoying our unlimited services and benefits. We offer high quality and innovative Products and Services at Affordable Pricing with a devoted Customer Service Team. 
                  </p>
                <p class="mb-4">
                  Thank you and we hope you will enjoy your experience with us.                
                 </p>
                <div class="about-list mb-3 active">
                  <div class="d-flex align-items-center">
                    <i class="ti-check text-primary display-26"></i>
                    <div class="ms-3">
                      <h4 class="h6 mb-0">Reliable</h4>
                    </div>
                  </div>
                </div>
                <div class="about-list mb-3">
                  <div class="d-flex align-items-center">
                    <i class="ti-check text-primary display-26"></i>
                    <div class="ms-3">
                      <h4 class="h6 mb-0">
                        Superfast
                      </h4>
                    </div>
                  </div>
                </div>
                <div class="about-list">
                  <div class="d-flex align-items-center">
                    <i class="ti-check text-primary display-26"></i>
                    <div class="ms-3">
                      <h4 class="h6 mb-0">Tested and trusted</h4>
                    </div>
                  </div>
                </div>
                <div class="mt-1-9">
                  <div class="d-flex align-items-center">
                    {{-- <div
                      class="bg-img px-7 text-center py-3 cover-background border-radius-10 border-primary border border-width-3"
                      data-background="img/content/about-03.jpg"
                    > --}}
                      {{-- <div class="z-index-1 position-relative">
                        <a
                          class="popup-social-video"
                          href="https://www.youtube.com/watch?v=yd1JhZzoS6A"
                          ><i class="fas fa-play display-20 text-primary"></i
                        ></a>
                      </div> --}}
                    </div>
                    {{-- <div class="ms-2 ms-md-5">
                      <h4 class="mb-0 h5">Steve Everest</h4>
                      <span class="small">Senior Executive</span>
                    </div> --}}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <img
          src="img/content/line-01.png"
          class="position-absolute top-n15 right-5 ani-top-bottom"
          alt="..."
        />
      </section>
  @include("home.howitwork")
        <!-- EXTRA
        ================================================== -->
       
@include('home.whyus')
@include('home.counter')
@include('home.testimonies')
@include('home.footer')