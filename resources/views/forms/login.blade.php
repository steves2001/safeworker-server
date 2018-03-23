        <!-- Start Login Form with header-->
        <section id="loginSection" class="form-simple d-none container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-6 form-col-pad">

                    
                    <div class="card">

                        <!--Header-->
                        <div class="header pt-3 mdb-color">

                            <div class="row d-flex justify-content-start">
                                <h5 class="white-text mt-1 mb-3 pb-1 mx-5">Log in</h5>
                            </div>

                        </div>
                        <!--Header-->

                        <div class="card-body mx-4 mt-4">

                            <!--Body-->

                            <form action="#" method="POST" id="loginForm" novalidate>
                                <div class="md-form">
                                    <input id="lEmail" type="text" title="Please enter you email" required value="" name="email" class="form-control">
                                    <label for="lEmail">Your college email</label>
                                </div>
                                <div id="loginReveal1" class="collapse show multi-collapse" aria-expanded="true">
                                    <div class="md-form pb-3">
                                        <input type="password" id="lPassword" class="form-control" name="password">
                                        <label for="lPassword">Your password</label>
                                        <p class="font-small grey-text d-flex justify-content-end"><a id="forgotPassword" href=".multi-collapse" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="loginReveal1 recoverPassword loginReveal2" class="dark-grey-text font-bold ml-1">Click to Recover Password?</a>
                                        </p>
                                    </div>
                                </div>
                                <div id="recoverPassword" class="collapse multi-collapse" aria-expanded="false">
                                    <p>Enter your college email address to start the password recovery process, you will be emailed a new password.</p>
                                    <div class="text-center mb-4">
                                        <button id="recoverButton" type="button" class="btn btn-primary-custom btn-block z-depth-2" data-target=".multi-collapse" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="loginReveal1 recoverPassword loginReveal2">Recover Password</button>
                                        <button id="recoverCancelButton" type="button" class="btn btn-secondary-custom btn-block z-depth-2" data-target=".multi-collapse" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="loginReveal1 recoverPassword loginReveal2">Cancel</button>
                                    </div>
                                </div>
                                <div id="loginReveal2" class="collapse show multi-collapse" aria-expanded="true">
                                    <div class="text-center mb-4">
                                        <button type="submit" class="btn btn-primary-custom white-text btn-block z-depth-2">Log in</button>

                                        <button id="registerButton" type="button" class="btn btn-secondary-custom btn-block z-depth-2">Register</button>
                                    </div>
                                </div>
                            </form>

                        </div>

                    </div>
                </div>
            </div>

        </section>
        <!-- End Login Form with header -->
