<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="theme-color" content="#212529">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Study Safe - Security</title>
    <link rel="manifest" href="manifest.json">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="css/mdb.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="css/security_style.css" rel="stylesheet">

</head>

<body class="hidden-sn navy-blue-skin">

@include('security.navigation')

    <!-- Start of project here-->
    <div class="container-fluid">
        
@include('forms.loginsecurity')
@include('forms.changepassword')            

        <!--Security Card Template-->
        <div id="security-card" class="d-none">
            <section class="col-12 announcement-col-pad">
                <div class="card">
                    <div class="card-header security-card-header white-text p-0">
                        <div class="container-fluid mx-0 px-0">
                            <div class="row align-items-center mx-0 px-0">
                                <div class="col-auto px-2 pt-1">
                                     <a href="#" class="security-card-toggle" data-toggle="collapse" data-target="#security-card-text" aria-controls="security-card-text" aria-expanded="false" aria-label="Toggle security message text">
                                         <i class="fa fa-bars white-text" style="font-size:1.4rem"></i></a> 
                                </div>
                                <div class="col px-1">
                                    <span class="name">Stephen Smith</span> 
                                </div>
                                <div class="col px-1 text-right">
                                    <span id="time-" class="departing">00:00:00</span> 
                                </div>                             
                                <div class="col-auto px-0">
                                    <!--<span id="btn-clear-activity_" class="btn-floating btn-sm  danger-color-dark mx-2 my-1"><i class="fa fa-check-circle security-header-icon white-text" aria-hidden="true"></i><i class="fa fa-close" aria-hidden="true"></i></span>-->
                                    <span id="btn-clear-activity_" class="mx-2 my-1"><i class="fa fa-times-circle-o security-header-icon white-text pt-1" aria-hidden="true"></i></span> 
                                </div>
                                <div class="col-auto px-0">
                                    <!--<span id="btn-accept-activity_" class="btn-floating btn-sm  success-color-dark mx-2 my-1"><i class="fa fa-check" aria-hidden="true"></i></span>-->
                                    <span id="btn-accept-activity_" class="mx-2 my-1"><i class="fa fa-check-circle-o security-header-icon white-text pt-1" aria-hidden="true"></i></span> 
                                </div>
                            </div>
                        </div>

                    </div>
                    <div id="security-card-text" class="collapse">
                        <div class="card-body px-1">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12 ">
                                        <span class="security-icon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                        <span class="card-text security-location">Some quick example.</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 ">
                                        <span class="security-icon"><i class="fa fa-phone" aria-hidden="true"></i></span>
                                        <span class="card-text security-telephone">Some quick example.</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 ">
                                        <span class="security-icon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                        <span class="card-text security-message">Some quick example.</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 ">
                                        <span class="security-icon"><i class="fa fa-warning" aria-hidden="true"></i></span><span class="card-text">Security Escort Required : </span>
                                        <span class="card-text security-escort">Some quick example.</span>
                                    </div>
                                </div>                                
                                <div class="row px-0 mx-0">
                                    <div class="col px-0 mx-0">
                                        <span id="btn-cancel-activity_" class="btn btn-sm danger-color-dark mt-3 mx-0" style="width: 100%">Remove</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- End Security Card Template -->
        <!-- Start post security message form -->
        <section id="postAnnouncementSection" class="form-simple d-none container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-6 form-col-pad">
                    <div class="card">
                        <!--Header-->
                        <div class="header pt-3 mdb-color">
                            <div class="row d-flex justify-content-start">
                                <h5 class="white-text mt-1 mb-3 pb-1 mx-5">Post Security Announcement</h5>
                            </div>
                        </div>
                        <!--Header-->
                        <div class="card-body mx-4 mt-4">
                            <!--Body-->
                            <form action="#" method="POST" id="postAnnouncementForm" novalidate>
                                <!--Announcement title -->
                                <div class="md-form">
                                    <input type="text" id="title" name="title" class="form-control">
                                    <label for="title" class="">Title</label>
                                </div>
                                <!--/Announcement title -->
                                <hr>
                                <!-- Announcement text -->
                                <div class="md-form mb-5">
                                    <label for="announcement">Enter announcement</label>
                                    <textarea type="text" id="announcement" name="content" maxlength="500" length="500" class="pt-0 pb-0 md-textarea form-control" rows="4"></textarea>
                                </div>
                                <!--/Announcement text -->
                                <!-- Form buttons -->
                                <div class="text-center mb-4">
                                    <button type="submit" class="btn btn-primary-custom btn-block z-depth-2">Post Announcement</button>
                                    <button id="cancelPostAnnouncement" type="button" class="btn btn-secondary-custom btn-block z-depth-2">Cancel</button>
                                </div>
                                <!--/Form buttons -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End post security message form -->
        <!--announcement row start -->
        <div id="announcements" class="row">
        </div>
        <!--announcement row end -->
        <!--End of screen gap start -->
        <div>
            <p>&nbsp;</p>
        </div>
        <!--End of screen gap end -->
    </div>
    <!-- Start your project here end-->

    <!-- SCRIPTS -->

    <!-- JQuery -->
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="js/mdb.min.js"></script>
    <!-- PWA Scripts -->
    <script type="text/javascript" src="js/common_app.js"></script>
    <script type="text/javascript" src="js/security_app.js" defer></script>
</body>

</html>