        <!-- Start request support form -->
        <section id="requestSupportSection" class="form-simple d-none container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-6 form-col-pad">
                    <div class="card">
                        <!--Header-->
                        <div class="header pt-3 mdb-color">
                            <div class="row d-flex justify-content-start">
                                <h5 class="white-text mt-1 mb-3 pb-1 mx-5">Contact Student Services</h5>
                            </div>
                        </div>
                        <!--Header-->
                        <div class="card-body mx-4 mt-4">
                            <!--Body-->
                            <form action="#" method="POST" id="requestSupportForm" novalidate>
                                <!--Dropdown destination selector -->
                                <div id="select-contact" class="dropdown">
                                    <label for="dropdownDestination">Reason for Contact</label>
                                    <!--Trigger-->
                                    <button class="btn btn-primary-custom waves-effect btn-block dropdown-toggle" type="button" id="dropdownDestination" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" value="Unknown">Unknown</button>
                                    <!--Menu-->
                                    <div class="dropdown-menu dropdown-ins" style="width:100%">
                                        <a class="dropdown-item" href="#">Safeguarding</a>
                                        <a class="dropdown-item" href="#">Financial</a>
                                        <a class="dropdown-item" href="#">Careers</a>
                                        <a class="dropdown-item" href="#">General</a>
                                        <a class="dropdown-item" href="#">Student Union</a>
                                    </div>
                                </div>
                                <!--/Dropdown destination -->
                                <hr>
                                <!-- Enquiry message -->
                                <div>
                                    <label for="contactMessage">Enter enquiry details</label>
                                    <textarea type="text" id="contactMessage" maxlength="500" length="500" class="pt-0 pb-0 md-textarea form-control fourline"></textarea>
                                </div>
                                <!--/Enquiry message -->
                                <hr>
                                <!-- Form buttons -->
                                <div class="text-center mb-4">
                                    <button type="submit" class="btn btn-primary-custom btn-block z-depth-2">Send Request</button>
                                    <button id="cancelSupportRequest" type="button" class="btn btn-secondary-custom btn-block z-depth-2">Cancel</button>
                                </div>
                                <!--/Form buttons -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End request support form -->