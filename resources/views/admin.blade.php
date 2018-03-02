<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="theme-color" content="#212529">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Study Safe - Administration</title>
    <link rel="manifest" href="manifest.json">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="css/mdb.min.css" rel="stylesheet">
    <link href="css/bootstrap-table.css" rel="stylesheet">
    <!-- Text Editor -->
    
    <!-- Custom styles -->
    <link href="css/style.css" rel="stylesheet">

</head>

<body class="hidden-sn black-skin">

    <!--Double navigation-->
    <header>
        <!-- Sidebar navigation -->
        <div id="slide-out" class="side-nav">
            <ul class="custom-scrollbar list-unstyled">
                <li>
                    <!-- SideNav slide-out button -->
                    <div>
                        <a href="#"><i class="fa fa-bars" style="font-size:1.4rem"></i></a>
                    </div>
                </li>
                <!-- Side navigation links -->
                <li>
                    <ul id="sideMenu" class="collapsible collapsible-accordion">
                         <li><a class="collapsible-header waves-effect arrow-r"><i class="fa fa-chevron-right"></i>Announcement Management<i class="fa fa-angle-down rotate-icon"></i></a>
                            <div class="collapsible-body">
                                <ul>
                                    <li><a id="manageAnnouncements" href="#">Manage Announcements</a>
                                    </li>
                                    <li><a id="addAnnouncement" href="#">Add Announcement</a>
                                    </li>
                                </ul>
                            </div>
                        </li>                        
                        <li><a class="collapsible-header waves-effect arrow-r"><i class="fa fa-chevron-right"></i>User Management<i class="fa fa-angle-down rotate-icon"></i></a>
                            <div class="collapsible-body">
                                <ul>
                                    <li><a id="manageUsers" href="#">Manage Users</a>
                                    </li>                                    
                                    <li><a id="addUser" href="#">Add User</a>
                                    </li>

                                </ul>
                            </div>
                        </li>
                        <li><a class="collapsible-header waves-effect arrow-r"><i class="fa fa-chevron-right"></i>Log Management<i class="fa fa-angle-down rotate-icon"></i></a>
                            <div class="collapsible-body">
                                <ul>
                                    <li><a id="manageLogs" href="#">Manage Logs</a>
                                    </li>
                                    <li><a id="reviewLogs" href="#">Review Logs</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li id="changePasswordButton" class="d-none"><a href="#" class="waves-effect">Change Password</a>
                        </li>
                        <li id="logoutButton" class="d-none"><a href="#" class="waves-effect">Logout</a>
                        </li>
                    </ul>
                </li>
                <!--/. Side navigation links -->
            </ul>
            <div class="sidenav-bg mask-strong"></div>
        </div>
        <!--/. Sidebar navigation -->
        <!-- Navbar -->
        <nav class="invisible navbar navbar-default navbar-toggleable-md navbar-expand-lg scrolling-navbar double-nav" style="margin-bottom: 8px;">
            <ul class="nav navbar-nav nav-flex-icons ml-auto">
                <li class="nav-item">
                    <a class="nav-link"><i class="fa fa-envelope"></i></a>
                </li>
            </ul>
        </nav>
        <!-- Dummy nav bar -->
        <nav class="navbar fixed-top navbar-toggleable-md navbar-expand-lg scrolling-navbar double-nav">
            <!-- SideNav slide-out button -->
            <div class="float-left">
                <a id="navBurger" href="#" data-activates="slide-out" class="button-collapse d-none"><i class="fa fa-bars" style="font-size:1.4rem"></i></a>
            </div>
            <!-- Title -->
            <span id="debugLink" class="clearfix d-none d-sm-inline-block" style="margin-left: 7px;">Study Safe</span>
            <ul class="nav navbar-nav nav-flex-icons ml-auto">
                <li class="nav-item">
                    <a id="logoutIcon" class="nav-link invisible"><i id="securityIndicator" class="fa fa-power-off red-text"></i> <span class="clearfix d-none d-sm-inline-block">Logout</span></a>
                </li>
            </ul>
        </nav>
        <!-- /.Navbar -->
    </header>
    <!--/.Double navigation-->

    <!-- Project start-->
    <div class="container-fluid">
        <section id="addAnnouncementModal" class="modal fade top" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-notify modal-info" role="document">
                <div class="modal-content">
                    <!--Header-->
                    <div class="modal-header">
                            <p class="heading lead">Add Announcement</p>
                    </div>
                    <!--End Header-->
                    <!--Body-->
                    <div class="modal-body mx-0 mt-4">
                        <form action="#" method="POST" id="addAnnouncementForm" novalidate>
                            <div class="form-group mb-1 ">
                              <label for="announceSource" class="indigo-text">Select Category</label>
                              <select class="form-control d-block" id="announceSource">
                                @foreach ($sources as $source)
                                <option value="{{ $source->sourcename }}">{{ $source->sourcename }}</option>
                                @endforeach
                              </select>
                            </div>
                            <label for="announceTitle" class="mb-0 indigo-text">Announcement Title</label>
                            <input type="text" id="announceTitle" class="" name="title" style="width: 100%"><span></span>
                            <label for="editor" class="mt-3 mb-2 indigo-text">Announcement</label>
                            <textarea id="editor" class="mce-editor" name="announcement">Hello....</textarea>
                            <div class="text-center mt-3 mb-1">
                                <button type="submit" class="btn btn-primary-modal">Add</button>
                                <button type="button" class="btn btn-outline-secondary-modal" data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                    <!--End Body-->
                </div>
            </div>
        </section>
        
        <section id="updateAnnouncementModal" class="modal fade top" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-notify modal-info" role="document">
                <div class="modal-content">
                    <!--Header-->
                    <div class="modal-header">
                            <p class="heading lead">Update Announcement</p>
                    </div>
                    <!--End Header-->
                    <!--Body-->
                    <div class="modal-body mx-0 mt-4">
                        <form action="#" method="POST" id="updateAnnouncementForm" novalidate>
                            <div class="md-form d-none">
                                <input id="updateAnnouncementId" type="text" title="Please enter your id" required value="" name="id" class="form-control">
                            </div>
                            <div class="form-group mb-1 ">
                              <label for="updateAnnouncementSourceId" class="indigo-text">Select Category</label>
                              <select class="form-control d-block" id="updateAnnouncementSourceId" name="source">
                                @foreach ($sources as $source)
                                <option value="{{ $source->id }}">{{ $source->sourcename }}</option>
                                @endforeach
                              </select>
                            </div>
                            <label for="updateAnnouncementTitle" class="mb-0 indigo-text">Announcement Title</label>
                            <input type="text" id="updateAnnouncementTitle" class="" name="title" style="width: 100%"><span></span>
                            <label for="updateAnnouncementContent" class="mt-3 mb-2 indigo-text">Announcement</label>
                            <textarea id="updateAnnouncementContent" class="mce-editor" name="announcement"></textarea>
                            <div class="text-center mt-3 mb-1">
                                <button type="submit" class="btn btn-primary-modal">Update</button>
                                <button type="button" class="btn btn-outline-secondary-modal" data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                    <!--End Body-->
                </div>
            </div>
        </section>

        
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
                                    <button type="submit" class="btn btn-mdb btn-block z-depth-2">Register</button>
                                    <button id="cancelButton" type="button" class="btn btn-danger btn-block z-depth-2">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End registration Form with header-->
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
                                        <button id="recoverButton" type="button" class="btn btn-secondary btn-block z-depth-2" data-target=".multi-collapse" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="loginReveal1 recoverPassword loginReveal2">Recover Password</button>
                                        <button id="recoverCancelButton" type="button" class="btn btn-danger btn-block z-depth-2" data-target=".multi-collapse" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="loginReveal1 recoverPassword loginReveal2">Cancel</button>
                                    </div>
                                </div>
                                <div id="loginReveal2" class="collapse show multi-collapse" aria-expanded="true">
                                    <div class="text-center mb-4">
                                        <button type="submit" class="btn btn-mdb white-text btn-block z-depth-2">Log in</button>

                                        <button id="registerButton" type="button" class="btn btn-secondary btn-block z-depth-2">Register</button>
                                    </div>
                                </div>
                            </form>

                        </div>

                    </div>
                </div>
            </div>

        </section>
        <!-- End Login Form with header -->
        
        <!-- Start user admin modals -->
        <!--Start Add user modal -->
        <section id="userAddModal" class="modal fade top" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-notify modal-info" role="document">
                <div class="modal-content">
                <!--Header-->
                    <div class="modal-header">
                        <p class="heading lead">Add New User</p>
                    </div>
                <!--End Header-->
                <!--Body-->
                    <div class="modal-body mx-4 mt-2">
                        <form id="userAddForm" action="#" method="POST"  novalidate>
                            <div class="md-form">
                                <input id="userAddName" type="text" title="Please enter your name" required value="" name="name" class="form-control">
                                <label for="userAddName">User name</label>
                            </div>
                            <div class="md-form">
                                <input id="userAddEmail" type="text" title="Please enter your email" required value="" name="email" class="form-control">
                                <label for="userAddEmail">College email</label>
                            </div>
                            <div class="md-form">
                                <input id="userAddPassword" type="password" class="form-control" name="password">
                                <label for="userAddPassword">Password</label>
                            </div>
                            <div class="md-form">
                                <input id="userAddC_password" type="password" class="form-control" name="c_password">
                                <label for="userAddC_password">Confirm password</label>
                            </div>
                            <div class="text-center mt-2 mb-2">
                                <button type="submit" class="btn btn-primary-modal">Add User</button>
                                <button type="button" class="btn btn-outline-secondary-modal" data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                <!--End Body-->
                </div>
            </div>
        </section>
        <!-- End add user modal -->
        <section id="userRoleUpdateModal" class="modal fade top" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-notify modal-info" role="document">
                <div class="modal-content">
                <!--Header-->
                    <div class="modal-header">
                        <p class="heading lead">Update User Roles <span id="roleUserName"></span></p>
                    </div>
                <!--End Header-->
                <!--Body-->
                    <div class="modal-body mx-4 mt-2">
                        <form action="#" method="POST" id="userRoleUpdateForm" novalidate>
                            <div class="md-form d-none">
                                <i class="fa fa-user prefix grey-text"></i>
                                <input id="roleUserId" type="text" title="Please enter your id" required value="" name="id" class="form-control">
                                    
                            </div>                            
                            @foreach ($roles as $role)
                            <div class="ml-5 mb-1">
                                <input id="checkRole{{ $role->id }}" type="checkbox" class="filled-in" onclick="ajaxChangeRole({{ $role->id }}, this);">
                                <label for="checkRole{{ $role->id }}">{{ $role->role }}</label><span id="checkRoleError{{ $role->id }}" class="invisible errorMessage"> Error Changing Role</span>
                           </div>
                            @endforeach
                           <div class="text-center mt-2 mb-2">
                                <button type="button" class="btn btn-primary-modal" data-dismiss="modal">Close</button>
                           </div>
                        </form>
                    </div>
                <!--End Body-->
                </div>
            </div>
        </section>
        
        <section id="userUpdateModal" class="modal fade top" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-notify modal-info" role="document">
                    <div class="modal-content">
                        <!--Header-->
                        <div class="modal-header">
                                <p class="heading lead">Update User</p>
                        </div>
                        <!--End Header-->
                        <!--Body-->
                        <div class="modal-body mx-4 mt-4">
                            <form action="#" method="POST" id="userUpdateForm" novalidate>
                                <div class="md-form d-none">
                                    <i class="fa fa-user prefix grey-text"></i>
                                    <input id="updateId" type="text" title="Please enter your id" required value="" name="id" class="form-control">
                                    
                                </div>
                                <div class="md-form">
                                    <i class="fa fa-user prefix grey-text"></i>
                                    <input id="updateName" type="text" title="Please enter your name" required value="" name="name" class="form-control">
                                   
                                </div>
                                <div class="md-form">
                                    <i class="fa fa-envelope prefix grey-text"></i>
                                    <input id="updateEmail" type="text" title="Please enter your email" required value="" name="email" class="form-control">
                                    
                                </div>
                                <div class="text-center mb-4">
                                    <button type="submit" class="btn btn-primary-modal">Update</button>
                                    <button type="button" class="btn btn-outline-secondary-modal" data-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                        <!--End Body-->
                    </div>
            </div>
        </section>
        <!-- End user admin modals -->

        <!--Main row start -->
        <section id="mainPage" class="row">
        </section>
        <!--Main row end -->
        <section id="userAdminSection" class="userAdminCheckBox card d-none">
            <div id="userAdminToolbar" class="userAdminText header">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" name="export" class="btn btn-sm btn-purple btn-rounded"><i class="fa fa-star fa-sm pr-2" aria-hidden="true"></i>Export</button>
                    <button type="button" name="delete" class="btn btn-sm btn-red btn-rounded"><i class="fa fa-heart fa-sm pr-2" aria-hidden="true"></i>Delete</button>
                    <button type="button" name="refresh" class="btn btn-sm btn-purple btn-rounded"><i class="fa fa-user fa-sm pr-2" aria-hidden="true"></i>Refresh</button>
                </div>
            </div>
            <div class="card-body">
            <table id="userAdminTable"
                   data-toolbar="#userAdminToolbar"                   
                   data-icons-prefix = "fa"
                   data-icons = "icons"
                   data-icon-size = "sm"
                   data-buttons-class = "purple btn-rounded waves-effect waves-light"
                   data-row-style="userRowStyle" 
                   data-striped="true" 
                   data-classes="table-sm table-no-bordered" 
                   data-search="true"
                   data-show-columns="true"           
                   data-pagination="true" 
                   checkbox = "true"
                   data-unique-id="id"
                   data-buttons-align="right">
                <thead class="mdb-color lighten-4"><tr></tr></thead>
            </table>
            </div>                         
        </section>
        <section id="announcementAdminSection" class="userAdminCheckBox card d-none">
            <div id="announcementAdminToolbar" class="userAdminText header">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" name="export" class="btn btn-sm btn-purple btn-rounded"><i class="fa fa-star fa-sm pr-2" aria-hidden="true"></i>Export</button>
                    <button type="button" name="delete" class="btn btn-sm btn-red btn-rounded"><i class="fa fa-heart fa-sm pr-2" aria-hidden="true"></i>Delete</button>
                    <button type="button" name="refresh" class="btn btn-sm btn-purple btn-rounded"><i class="fa fa-user fa-sm pr-2" aria-hidden="true"></i>Refresh</button>
                </div>
            </div>
            <div class="card-body">
            <table id="announcementAdminTable"
                   data-toolbar="#announcementAdminToolbar"                   
                   data-icons-prefix = "fa"
                   data-icons = "icons"
                   data-icon-size = "sm"
                   data-buttons-class = "purple btn-rounded waves-effect waves-light"
                   data-row-style="userRowStyle" 
                   data-striped="true" 
                   data-classes="table-sm table-no-bordered" 
                   data-search="true"
                   data-show-columns="true"           
                   data-pagination="true" 
                   checkbox = "true"
                   data-unique-id="id"
                   data-buttons-align="right">
                <thead class="mdb-color lighten-4"><tr></tr></thead>
            </table>
            </div>                         
        </section>        
        <!--End of screen gap start -->
        <div>
            <p>&nbsp;</p>
        </div>
        <!--End of screen gap end -->
    </div>
    <!-- Project End-->

    <!-- SCRIPTS -->

    <!-- JQuery -->
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-table.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="js/mdb.min.js"></script>
    <!-- text editor -->
    <script src='js/tinymce/tinymce.min.js'></script>
    <!-- PWA Scripts -->
    <script type="text/javascript" src="js/core_app.js" async></script>
    <!-- Custom font awesome icons for bootstrap table -->
    <script>
    window.icons = {
        paginationSwitchDown: 'fa-chevron-down',
        paginationSwitchUp: 'fa-chevron-up',
        refresh: 'fa-refresh',
        toggle: 'fa-toggle-on',
        columns: 'fa-th-list',
        detailOpen: 'fa-plus',
        detailClose: 'fa-minus'
    };
    </script>
</body>

</html>