 @include("home.head")
 @include("home.header")

 <section class="top-position1 py-0">
    <div class="page-title-section bg-img cover-background left-overlay-dark" data-overlay-dark="7" data-background="img/bg/bg-03.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1> FAQs</h1>
                    <div class="breadcrumb">
                        <ul>
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="#!">FAQs</a></li>
                            <li><a href="#!"> FAQs</a></li>
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

 <!-- CLIENT QUESTIONS
        ================================================== -->
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mb-2-5 mb-lg-0">
                        <h2 class="h1 font-weight-700 mb-4">Frequently Asked <span class="font-weight-400">Questions</span></h2>
                        <div class="accordion" id="accordion">
                            <div class="card">
                                <h2 class="card-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">01. Is this another Internet HYIP, MLM, Ponzi or Pyramid Scheme?</button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordion">
                                    <div class="card-body">
                                        We are a Fintech Social Enterprise legally registered and offering genuine Products and Services just like Amazon, Jumia, Multichoice, MTN, Opay, Zenith Bank, Coursera etc. We share a large chunk of our profits with our Partners, Stakeholders, Charity and Community.
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <h2 class="card-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">02. I am interested, how do I register?</button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordion">
                                    <div class="card-body">
                                        Revert back to the person that shared this platform with you for their custom invitation link. However, if you came through Random Internet Surfing, then click <a href="{{ route('register') }}"> HERE </a> to register.
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <h2 class="card-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">03.  Is it compulsory to buy a Partnership Package?</button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordion">
                                    <div class="card-body">
                                        No, you can just register as a Free Member and use the platform. However, only Paid Partners get to enjoy incentives such as Commissions, Dividends, Pension, Cash Grants, Gift Items etc
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <h2 class="card-header" id="headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseThree">04.  Can a Free Member upgrade to Paid Partner anytime?</button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordion">
                                    <div class="card-body">
                                        Absolutely, everyone first join as a Free Member then upgrade to any package of their choice by clicking on Partner Packages or Buy Package. 
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <h2 class="card-header" id="headingFive">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="true" aria-controls="collapseThree">05.  What are the Partnership Packages available?</button>
                                </h2>
                                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordion">
                                    <div class="card-body">
                                        There are 4 packages available with unique benefits, kindly click <a href="{{ route('packages') }}"> HERE </a> to learn more
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <h2 class="card-header" id="headingSix">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="true" aria-controls="collapseThree">06.  What if I have a special Question or Suggestion?</button>
                                </h2>
                                <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordion">
                                    <div class="card-body">
                                        Feel free to contact us using any of the channels provided <a href="{{ route('packages') }}"> HERE </a> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="ps-lg-2-5 ps-xl-7">
                            <div class="bg-img cover-background p-1-9 py-sm-2-9 px-sm-2-5 py-md-8 px-md-6 border-radius-10" data-overlay-dark="8" data-background="img/bg/bg-04.jpg">
                                <div class="position-relative z-index-9">
                                    <h2 class="mb-4 text-white">Enjoy more of Abovemart FAQsI</h2>
                                    <ul class="list-style1 mb-4 white">
                                        <li><i class="fas fa-check-circle text-white me-3 font-weight-600"></i>Recharge Card Purchase</li>
                                        <li><i class="fas fa-check-circle text-white me-3 font-weight-600"></i>Internet Data Purchase</li>
                                        <li><i class="fas fa-check-circle text-white me-3 font-weight-600"></i>DSTV/GOTV Subscription</li>
                                        <li><i class="fas fa-check-circle text-white me-3 font-weight-600"></i>Electricity Bills</li>
                                    </ul>
                                    <a href="{{ route('register') }}" class="butn md white">Get Started</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
@include("home.footer")
