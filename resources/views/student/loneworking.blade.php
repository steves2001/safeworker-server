        <!-- Start lone working input dialogue -->
        <section class="collapse" id="navbarToggleExternalContent">
            <div class="container-fluid">
                <div class="row justify-content-end">

                    <div class='col-sm-6'>
                        <div id="inputLoneWorking" class="d-block">

                            <label for="dropdownHours">Lone working duration</label>
                            <div class="btn-group btn-block">
                                <!--Dropdown primary Hour Selector-->
                                <div id="select-hours" class="dropdown" style="width:50%">
                                    <!--Trigger-->
                                    <button class="btn btn-elegant waves-effect dropdown-toggle" style="width:100%" type="button" id="dropdownHours" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" value="00">00 Hrs</button>

                                    <!--Menu-->
                                    <div class="dropdown-menu dropdown-ins" style="width:100%">
                                        <a class="dropdown-item" href="#">00</a>
                                        <a class="dropdown-item" href="#">01</a>
                                        <a class="dropdown-item" href="#">02</a>
                                        <a class="dropdown-item" href="#">03</a>
                                        <a class="dropdown-item" href="#">04</a>
                                        <a class="dropdown-item" href="#">05</a>
                                        <a class="dropdown-item" href="#">06</a>
                                        <a class="dropdown-item" href="#">07</a>
                                        <a class="dropdown-item" href="#">08</a>
                                    </div>
                                </div>
                                <!--/Dropdown primary-->

                                <!--Dropdown primary Minute Selector-->
                                <div id="select-minutes" class="dropdown" style="width:50%">
                                    <!--Trigger-->

                                    <button class="btn btn-elegant  waves-effect dropdown-toggle" style="width:100%" type="button" id="dropdownMinutes" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" value="00">00 Mins</button>

                                    <!--Menu-->
                                    <div class="dropdown-menu dropdown-ins" style="width:100%">
                                        <a class="dropdown-item" href="#">00</a>
                                        <a class="dropdown-item" href="#">15</a>
                                        <a class="dropdown-item" href="#">30</a>
                                        <a class="dropdown-item" href="#">45</a>
                                    </div>
                                </div>
                                <!--/Dropdown primary-->


                            </div>
                            <hr />
                            <!-- Switch -->
                            <div class="switch" data-toggle="collapse" data-target="#securityMessageToggle">
                                <label>Security escort
                                    <input id="securityEscort" type="checkbox"><span class="lever"></span>
                                </label>
                            </div>
                            <div id="securityMessageToggle" class="collapse">
                                <div>
                                    <label for="phoneNumber">Mobile Number</label>
                                    <input id="phoneNumber" type="text" maxlength="11" length="11">
                                </div>
                                <hr />
                                <div>
                                    
                                    <textarea type="text" id="securityMessage" maxlength="250" length="250" class="pt-0 pb-0 md-textarea form-control" rows="4"></textarea>
                                    <label for="securityMessage">Enter message for security</label>
                                </div>


                            </div>

                            <!--Dropdown location Selector -->
                            <div id="select-location" class="dropdown">
                                <label for="dropdownLocation">Study Location</label>
                                <!--Trigger-->
                                <button class="btn btn-elegant waves-effect btn-block dropdown-toggle" type="button" id="dropdownLocation" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" value="Unknown">Unknown</button>
                                <!--Menu-->
                                <div class="dropdown-menu dropdown-ins" style="width:100%">
                                    <a class="dropdown-item" href="#">HE Study Room</a>
                                    <a class="dropdown-item" href="#">College Library</a>
                                    <a class="dropdown-item" href="#">Deans Building</a>
                                </div>
                            </div>
                            <!--/Dropdown location -->
                            <hr />

                            <button id="startButton" type="button" class="btn btn-primary-custom btn-block mb-1">Start</button>
                        </div>
                        <div id="cancelLoneWorking" class="d-none">
                            <button id="stopButton" type="button" class="btn btn-secondary-custom btn-block mb-1">Confirm Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End lone working input dialogue -->
