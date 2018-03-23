        <!--Start registration Form with header-->
        <section id="registerSection" class="form-simple d-none container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-6 form-col-pad">
                    <div class="card">
                        <!--Header-->
                        <div class="header pt-3 mdb-color">
                            <div class="row d-flex justify-content-start">
                                <h5 class="white-text mt-1 mb-3 pb-1 mx-5">Register</h5>
                            </div>
                        </div>
                        <!--Header-->
                        <div class="card-body mx-4 mt-4">
                            <!--Body-->
                            <form action="#" method="POST" id="registerForm" novalidate>
                                <div class="md-form">
                                    <input id="name" type="text" title="Please enter your name" required value="" name="name" class="form-control">
                                    <label for="name">Your name</label>
                                </div>
                                <div class="md-form">
                                    <input id="rEmail" type="text" title="Please enter your email" required value="" name="email" class="form-control">
                                    <label for="rEmail">Your college email</label>
                                </div>
                                <div class="md-form">
                                    <input type="password" id="rPassword" class="form-control" name="password">
                                    <label for="rpassword">Your password</label>
                                </div>
                                <div class="md-form">
                                    <input type="password" id="c_password" class="form-control" name="c_password">
                                    <label for="c_password">Confirm password</label>
                                </div>
                                <div class="text-center mb-4">
                                    <button type="submit" class="btn btn-primary-custom btn-block z-depth-2">Register</button>
                                    <button id="cancelButton" type="button" class="btn btn-secondary-custom btn-block z-depth-2">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End registration Form with header-->
