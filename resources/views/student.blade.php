<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="theme-color" content="#212529">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Study Safe - Student</title>
    <link rel="manifest" href="manifest.json">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="css/mdb.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="css/student_style.css" rel="stylesheet">

</head>

<body class="hidden-sn mdb-skin">

@include('student.navigation')

    <!-- Start of project here-->
    <div class="container-fluid">

@include('student.loneworking')
@include('forms.login')
@include('forms.changepassword')
@include('forms.register')
@include('forms.requestsupport')

        <!--Announcement Card Template-->
        <div id="announcement-card" class="d-none">
            <section class="col-sm-6  announcement-col-pad">
                <div class="card">
                    <div class="card-body">
                        <!--Title-->
                        <span class="card-title f4-responsive">Card title</span>
                        <hr>
                        <!--Text-->
                        <p class="card-text">Some quick example text.</p>
                    </div>
                </div>
            </section>
        </div>
        <!-- End Announcement Card Template -->

        <!--announcement row start -->
        <div id="announcements" class="row">
        </div>
        <!--announcement row end -->

        <!--More button row start -->
        <div id="more" class="row">
            <section id="more-buttons" class="col-12 announcement-col-pad d-none">
                <button id="more-information" type="button" class="btn btn-mdb btn-lg btn-block" value="Announce-1#1">View More</button>
            </section>
        </div>
        <!--More button row end -->

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
    <script type="text/javascript" src="js/student_app.js" defer></script>
</body>

</html>