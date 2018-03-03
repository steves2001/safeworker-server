        <!-- Start password change form with header-->
        <section id="changePasswordSection" class="form-simple d-none container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-6 form-col-pad">
                    <div class="card">
                        <!--Header-->
                        <div class="header pt-3 mdb-color">
                            <div class="row d-flex justify-content-start">
                                <h5 class="white-text mt-1 mb-3 pb-1 mx-5">Change Password</h5>
                            </div>
                        </div>
                        <!--Header-->
                        <div class="card-body mx-4 mt-4">
                            <!--Body-->
                            <form action="#" method="POST" id="changePasswordForm" novalidate>
                                <div class="md-form">
                                    <input type="password" id="oldPassword" class="form-control" name="oldPassword">
                                    <label for="oldPassword">Old password</label>
                                </div>
                                <div class="md-form">
                                    <input type="password" id="newPassword" class="form-control" name="newPassword">
                                    <label for="newPassword">New password</label>
                                </div>
                                <div class="text-center mb-4">
                                    <button type="submit" class="btn btn-mdb btn-block z-depth-2">Change Password</button>
                                    <button id="cancelPasswordChangeButton" type="button" class="btn btn-danger btn-block z-depth-2">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End password change form with header-->
