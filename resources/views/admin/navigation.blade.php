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
