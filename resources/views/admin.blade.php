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

@include('admin.navigation')


    <!-- Project start-->
    <div class="container-fluid">
        
@include('forms.register')
@include('forms.login')
@include('forms.changepassword')        
        
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
                            <label for="addAnnouncementContent" class="mt-3 mb-2 indigo-text">Announcement</label>
                            <textarea id="addAnnouncementContent" class="mce-editor" name="content"></textarea>
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
                            <textarea id="updateAnnouncementContent" class="mce-editor" name="content"></textarea>
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