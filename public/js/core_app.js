// ---------------------------------------------------------------------------
// Student Study Safe Application 
// ---------------------------------------------------------------------------
// A Progressive Web Application linked to a laravel API, to log students 
// working alone in study areas and to provide safeguarding contacts to 
// learners
// ---------------------------------------------------------------------------
// Code required to install the service worker
(function() {
    'use strict';
    if('serviceWorker' in navigator) {
        navigator.serviceWorker.register('./service-worker.js').then(function() {
            console.log('Service Worker Registered');
        });
    }
})();
// End Service worker code
// ---------------------------------------------------------------------------
// Global Data
// ---------------------------------------------------------------------------
    var app = {
        sectionList: [],
        daysOfWeek: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        studyEndTime: 0,
        studyTimer: 0,
        studyTimerRunning: false,
        securityData: null
    };

    var api = 'https://toast-canvas-3000.codio.io/api/';
    var token = '';
// End global data
// ---------------------------------------------------------------------------
// Login/Registration/Password Related code
// ---------------------------------------------------------------------------
// Send ajax call with and email address to trigger an email to reset password.

function forgotPassword(recoveryEmail) {
    $.ajax({
        url: api + 'password/reset',
        type: 'POST',
        data: {
            email: recoveryEmail
        },
        success: function(data) {
            toastr["error"]("Recovery Email Sent");
            console.log(data);
        }, // End of success
        error: function(data) {
            toastr["error"]("Incorrect Email Specified");
            console.log("Incorrect Email Specified");
        } // End error
    });
} // End ajax
// End forgot password
// ---------------------------------------------------------------------------
// Login User

function ajaxLogin(e, loginFormName) {
    var formData = $(loginFormName).serialize();
    console.log(formData);
    $.ajax({
        url: api + 'login',
        type: 'POST',
        data: formData,
        success: function(data) {
            console.log("Login Response Data");
            console.log(data);
            writeAPIToken(data.success.token);
            displaySingleSection('none');
            //setDisplay('#loginSection', 'off', 'd-block');
            setNavigationVisibility('on');
        }, // End of success
        error: function(data) {
            toastr["error"](data.responseJSON["error"]);
            console.log("Login Failed Response Data");
            console.log(data.responseText);
        } // End error
    });
}
// End Login User
// ---------------------------------------------------------------------------
// Register User

function ajaxRegister(e, registerFormName) {
    var formData = $(registerFormName).serialize();
    console.log(formData);
    $.ajax({
        url: api + 'register',
        type: 'POST',
        data: formData,
        success: function(data) {
            //setDisplay('#registerSection', 'off', 'd-block');
            displaySingleSection('loginSection');
            toastr["success"](data.success);
        }, // End of success
        error: function(data) {
            console.log("Register Failed Response Data");
            console.log(data.responseText);
            var obj = jQuery.parseJSON(data.responseText);
            if(obj.error.name) {
                toastr["warning"](obj.error.name);
            }
            if(obj.error.email) {
                toastr["warning"](obj.error.email);
            }
            if(obj.error.password) {
                toastr["warning"](obj.error.password);
            }
            if(obj.error.c_password) {
                toastr["warning"]("Passwords do not match");
            }
            toastr["error"]("Registration Failed");
        } // End error
    }); // End ajax
}
// End register user
// ---------------------------------------------------------------------------
// Logout code

function logout(e) {
    clearAPIToken();
    displaySingleSection('loginSection');
    setNavigationVisibility('off');
    $('#mainPage').empty();
}
// End Logout code
// ---------------------------------------------------------------------------
// Change Password

function ajaxChangePassword(e, passwordFormName) {
    var formData = $(passwordFormName).serialize();
    console.log(formData);
    $.ajax({
        url: api + 'password/change',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: 'PUT',
        data: formData,
        success: function(data) {
            setDisplay('#changePasswordSection', 'off', 'd-block');
            toastr["success"](data.success);
            console.log(data);
        }, // End of success
        error: function(data) {
            toastr["error"](data.responseJSON["error"]);
        } // End error
    }); // End ajax
}
// End Change Password
// ---------------------------------------------------------------------------
// End Login/Registration/Password  related code
// ---------------------------------------------------------------------------
// Utility Methods
// ---------------------------------------------------------------------------
// Cancel default behaviour stop clicks and form submissions occuring

function cancelDefaultBehaviour(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
}
// End cancel defaults
// ---------------------------------------------------------------------------
// Turns the visibility of MDBootstrap styled items on or off

function setDisplay(item, status, style) {
    if(status == 'on') {
        $(item).removeClass('d-none').addClass(style);
    }
    if(status == 'off') {
        $(item).removeClass(style).addClass('d-none');
    }
}
// end of set display
// Section methods
function displaySingleSection(sectionId = ''){
    for (var section of app.sectionList){
        if(sectionId == section){
            setDisplay('#' + section, 'on', 'd-block');
        }
        else{
            setDisplay('#' + section, 'off', 'd-block');            
        }
    }
}
// End section methods
// ---------------------------------------------------------------------------
// Check user is logged in returns true if user is logged in

function loggedIn() {
    if(getAPIToken() == '') return false;
    else return true;
}
// ---------------------------------------------------------------------------
// Stores the token in local storage

function writeAPIToken(tokenString) {
    if(localStorage) {
        localStorage.setItem('token', tokenString);
    }
    setAPIToken(tokenString);
}
// ---------------------------------------------------------------------------
// Retrieves the token from local storage

function readAPIToken() {
    if(localStorage && localStorage.getItem('token')) {
        setAPIToken(localStorage.getItem('token'));
        return true;
    } else {
        setAPIToken('');
        return false;
    }
}
// ---------------------------------------------------------------------------
// Returns the token

function getAPIToken() {
    return token;
}
// ---------------------------------------------------------------------------

function setAPIToken(tokenString) {
    token = tokenString;
}
// ---------------------------------------------------------------------------
// Removes the token from local storage and memory

function clearAPIToken() {
    if(localStorage && localStorage.getItem('token')) {
        localStorage.removeItem('token');
    }
    setAPIToken('');
}
// ---------------------------------------------------------------------------
// Setup the default settings for the MDBootstrap toastr messages

function setToasterDefaults() {
    toastr.options = {
        "closeButton": true, // true/false
        "debug": false, // true/false
        "newestOnTop": false, // true/false
        "progressBar": false, // true/false
        "positionClass": "toast-bottom-right", // toast-top-right / toast-top-left / toast-bottom-right / toast-bottom-left
        "preventDuplicates": false, // true/false
        "onclick": null,
        "showDuration": "300", // in milliseconds
        "hideDuration": "1000", // in milliseconds
        "timeOut": "5000", // in milliseconds
        "extendedTimeOut": "1000", // in milliseconds
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
}
// End setup default toastr
// ---------------------------------------------------------------------------
// Create a string formatted as HH:MM:SS based on a millisecond value

function createTimeString(milliseconds) {
    seconds = Math.floor(milliseconds / 1000);
    hours = Math.floor(seconds / 3600);
    minutes = Math.floor((seconds - hours * 3600) / 60);
    seconds = Math.floor(seconds - (hours * 3600 + minutes * 60));
    hourPad = "0";
    minPad = "0";
    secPad = "0";
    if(hours >= 10) hourPad = "";
    if(minutes >= 10) minPad = "";
    if(seconds >= 10) secPad = "";
    return hourPad + hours + ":" + minPad + minutes + ":" + secPad + seconds;
}
// end create time string
// ---------------------------------------------------------------------------
// Register form setup

function setupRegisterForm(){
    $("#registerForm").submit(function(e) {
        cancelDefaultBehaviour(e);
        ajaxRegister(e, "#registerForm");
    }); // End registerForm.submit
    $("#cancelButton").click(function(e) {
        displaySingleSection('loginSection');
    }); // End cancelButon.click      
}
// End register form setup
//  ---------------------------------------------------------------------------
// Login form setup

function setupLoginForm(){
    $("#loginForm").submit(function(e) {
        cancelDefaultBehaviour(e)
        ajaxLogin(e, "#loginForm");
    }); // End loginForm.submit
    $("#recoverButton").click(function(e) {
        forgotPassword($('#lEmail').val());
    }); // End recoverButton.click    
    $("#registerButton").click(function(e) {
        displaySingleSection('registerSection');
    }); // End registerButon.click
}
// End login form setup
//  ---------------------------------------------------------------------------
// Change password form setup
function setupChangePasswordForm(){
    $("#changePasswordForm").submit(function(e) {
        cancelDefaultBehaviour(e);
        ajaxChangePassword(e, "#changePasswordForm");
    }); // End changePasswordForm.submit        
    $("#changePasswordButton").click(function(e) {
        displaySingleSection('changePasswordSection');
    }); // End changePasswordButton.click
    $("#cancelPasswordChangeButton").click(function(e) {
        setDisplay('#changePasswordSection', 'off', 'd-block');
    }); // End endPasswordChangeButton.click    
}
// End change password form setup
// ---------------------------------------------------------------------------
// Set navigation visibility

function setupNavigationMenu(){
       
    $("#addAnnouncement").click(function(e) { 
        $('#addAnnouncementModal').modal('show')
    }); // End     
    $("#manageAnnouncements").click(function(e) { 
        ajaxGetAllAnnouncements();
        //setTableButton('#announcementAdminToolbar', 'refresh', ajaxGetAllAnnouncments);
        //setTableButton('#userAdminToolbar', 'delete', ajaxDeleteMultipleUsers);
    }); // End     
    $("#manageUsers").click(function(e) {
        ajaxGetAllUsers();
        setTableButton('#userAdminToolbar', 'refresh', ajaxGetAllUsers);
        setTableButton('#userAdminToolbar', 'delete', ajaxDeleteMultipleUsers);
        
    }); // End   
    $("#addUser").click(function(e) {
        displaySingleSection('');
        $('#userAddModal').modal('show');
    }); // End     
    $("#manageLogs").click(function(e) {        
    }); // End   
    $("#reviewLogs").click(function(e) {        
    }); // End   
}
// End setup Navigation
// ---------------------------------------------------------------------------
// Set navigation visibility

function setNavigationVisibility(visibility = 'off'){
        setDisplay('#changePasswordButton', visibility, 'd-block');
        setDisplay('#logoutButton', visibility, 'd-block');
        setDisplay('#navBurger', visibility, 'd-block');
        if(visibility == 'off') {
            $('#logoutIcon').removeClass('visible').addClass('invisible');
        } else {
            $('#logoutIcon').removeClass('invisible').addClass('visible');
        }
}
// End set navigation visibility
// ---------------------------------------------------------------------------
// Display an ajax bootstrap table

function displayTable(tableId, userData, hideColumns = []){
    $(tableId).bootstrapTable('destroy');    
    $(tableId).bootstrapTable(userData);
    for (const column of hideColumns)
        $(tableId).bootstrapTable('hideColumn', column);
}

function setTableButton(tableId, name, functionCallback){
    $(tableId).off("click", " button[name*='"+name+"']");
    $(tableId).on( "click", " button[name*='"+name+"']", function(e){functionCallback();});
}
// End Display an ajax bootstrap table
// ---------------------------------------------------------------------------
function userRowStyle(row, index) {
   
    switch(row.status){
        case 'DELETING':
            return { classes: 'table-warning' };
            break;
        case 'UPDATING':
            return { classes: 'table-warning' };
            break;
        case 'ERROR':
            return { classes: 'table-danger' };
            break;
        default:
            return {};
            break;
    }    
}
// End Utility Methods
// ---------------------------------------------------------------------------
// Start User Administration Methods
// ---------------------------------------------------------------------------
// Edit and delete actions for the user table

function userTableActions(value, row, index, field) {

    return [
                '<a class="" href="javascript:void(0)" onclick="updateRoleModal('+row.id+', \''+row.name+'\')" title="Edit Role" data-toggle="modal" data-target="#userRoleUpdateModal"> ',
                '<i class="fa fa-cogs" aria-hidden="true"></i>',
                '</a> &nbsp; ',
                '<a class="" href="javascript:void(0)" onclick="updateUserModal('+row.id+', \''+row.name+'\', \''+row.email+'\')" title="Edit" data-toggle="modal" data-target="#userUpdateModal"> ',
                '<i class="fa fa-pencil" aria-hidden="true"></i>',
                '</a> &nbsp; ',
                '<a class="" href="javascript:void(0)" onclick="ajaxDeleteUser(\'#userAdminTable\', '+row.id+')" title="Remove">',
                '<i class="fa fa-trash" aria-hidden="true"></i>',
                '</a>'
            ].join('');
}
// End edit and delete actions for the user table
// ---------------------------------------------------------------------------
// Update user role modal with data from the table row

function updateRoleModal(userId, name){
     $("#roleUserId").val( userId );
     $("#roleUserName").text( name );
     $('#userRoleUpdateModal :checkbox').prop('checked', false);
     $('#userRoleUpdateModal .errorMessage').removeClass('visible').addClass('invisible')
     $.ajax({
        url: api + 'userroles/users/' + userId,
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: 'GET',
        data: "",
        success: function(roles) {
            console.log(roles);
            for (const role of roles){
                $('#checkRole'+role.roleid).prop('checked', true);
                $('#checkRole'+role.roleid).data('roleid', role.id);
            }
        }, // End of success
        error: function(data) {
            console.log(data);
        } // End error
    }); // End ajax    
}     

// ---------------------------------------------------------------------------
// Add user modal setup

function setupAddUserModalForm(){
    $("#userAddForm").submit(function(e) {
        cancelDefaultBehaviour(e);
        ajaxAddUser(e, "#userAddForm");
    }); // End submit

}
// End add user modal setup
// ---------------------------------------------------------------------------
// Add User

function ajaxAddUser(e, FormName) {
    var formData = $(FormName).serialize();
    console.log(formData);
    $.ajax({
        url: api + 'users',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: 'POST',
        data: formData,
        success: function(data) {
            $('#userAddModal').modal('hide');
            toastr["success"](data.success);
            
        }, // End of success
        error: function(data) {
            console.log("Register Failed Response Data");
            console.log(data.responseText);
            var obj = jQuery.parseJSON(data.responseText);
            if(obj.error.name) {
                toastr["warning"](obj.error.name);
            }
            if(obj.error.email) {
                toastr["warning"](obj.error.email);
            }
            if(obj.error.password) {
                toastr["warning"](obj.error.password);
            }
            if(obj.error.c_password) {
                toastr["warning"]("Passwords do not match");
            }
            toastr["error"]("User Registration Failed");
        } // End error
    }); // End ajax
}
// End add user
// ---------------------------------------------------------------------------
function ajaxChangeRole(userRole, object){
    userId = "";
    ajaxData = "";
    ajaxCRUD = "";
    
    if($(object).is(':checked')){
        ajaxCRUD = "POST";
        ajaxData= {userid: $("#roleUserId").val(), roleid: userRole};
        
    } else {
        ajaxCRUD = "DELETE";
        userId = "/" + $(object).data('roleid');
    }
    
    $.ajax({
        url: api + 'userroles' + userId,
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: ajaxCRUD,
        data: ajaxData,
        statusCode: {
            404: function(data) {
                  toastr["error"]('Operation failed, user role could not be found');
                  updateCheckBoxInfo(data, userRole, object, false);
            }            
        },        
        success: function(data) { 
            updateCheckBoxInfo(data, userRole, object, true);
            console.log(data);
        }, // End of success
        error: function(data) { updateCheckBoxInfo(data, userRole, object, false); } // End error
    }); // End ajax    
    
    // Do the ajax then set checkbox status
}
function updateCheckBoxInfo(data, userRole, object, success){
    if(success){
        $('#checkRoleError'+userRole).removeClass('visible').addClass('invisible');
        $('#checkRole'+userRole).data('roleid', data.id);
    }
    else {
        $('#checkRoleError'+userRole).removeClass('invisible').addClass('visible'); 
        $('#checkRole'+userRole).data('roleid', "");
        $(object).prop('checked', !$(object).is(':checked'));
    }
}
// End update user role modal
// ---------------------------------------------------------------------------
// Setup modal to submit data via ajax
function setupUserModalForm(){
    $("#userUpdateForm").submit(function(e) {
        cancelDefaultBehaviour(e);
        ajaxUpdateUser($("#updateId").val(), $("#userUpdateForm").serialize());
    }); // End changePasswordForm.submit     
}
// End modal setup
// ---------------------------------------------------------------------------
// Update user modal with data from the table row

function updateUserModal(id, name, email){
     $("#updateId").val( id );
     $("#updateName").val( name );
     $("#updateEmail").val( email );
}
// End update user modal
// ---------------------------------------------------------------------------
// Update user table row from user modal

function updateUserRowFromModal(){
    $('#userAdminTable').bootstrapTable('updateByUniqueId', {
                id: $("#updateId").val(),
                row: {
                    name: $("#updateName").val(),
                    email: $("#updateEmail").val(),
                    status: null
                }
            });

    
}
// End update user table row from user modal
// ---------------------------------------------------------------------------
// Update user using ajax

function ajaxUpdateUser(userId, userData){
    rowData = $('#userAdminTable').bootstrapTable('getRowByUniqueId', userId);
    if(rowData.status) return;
    console.log(userData);
    $('#userAdminTable').bootstrapTable('updateByUniqueId', { id: userId, row: { status: 'UPDATING' } });
    $.ajax({
        url: api + 'users/' + userId,
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: 'PATCH',
        data: userData,
        statusCode: {
            400: function(data) {
                toastr["error"]('Update operation failed, sent data missing either email or name');
                $('#userAdminTable').bootstrapTable('updateByUniqueId', { id: data.responseJSON["id"], row: { status: null } });
            },
            404: function(data) {
                toastr["error"]('Update operation failed, user could not be found');
                $('#userAdminTable').bootstrapTable('updateByUniqueId', { id: data.responseJSON["id"], row: { status: 'ERROR' } });
            },
            409: function(data) {
                toastr["error"]('Update operation failed, there was a server conflict during update');
                $('#userAdminTable').bootstrapTable('updateByUniqueId', { id: data.responseJSON["id"], row: { status: null } });
            }            
        },
        success: function(user, status) {
            updateUserRowFromModal();
            $('#userUpdateModal').modal('hide');
            toastr["success"]('User was updated on the the system'); 
        }, // End of success
        error: function(data) {
            console.log(data);
        } // End error
    }); // End ajax    
}
// End update user
// ---------------------------------------------------------------------------
// Delete a user from the system

function ajaxDeleteUser(tableId, userId){
    rowData = $('#userAdminTable').bootstrapTable('getRowByUniqueId', userId);
    if(rowData.status) return;
    $('#userAdminTable').bootstrapTable('updateByUniqueId', { id: userId, row: { status: 'DELETING' } });
    $.ajax({
        url: api + 'users/' + userId,
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: 'DELETE',
        data: "",
        statusCode: {
            403: function(data) {
                toastr["error"]('Delete operation failed, you cannot delete yourself');
                $('#userAdminTable').bootstrapTable('updateByUniqueId', { id: data.responseJSON["id"], row: { status: null } });      
            },
            404: function(data) {
                  toastr["error"]('Delete operation failed, user could not be found');
                $('#userAdminTable').bootstrapTable('updateByUniqueId', { id: data.responseJSON["id"], row: { status: 'ERROR' } });
            }            
        },
        success: function(user, status) {
            $('#userAdminTable').bootstrapTable('removeByUniqueId', user.id);
            toastr["success"]('User was deleted from the system'); 
        }, // End of success
        error: function(data) {
            console.log(data.responseJSON["id"]);
        } // End error
    }); // End ajax    
}
// End delete user from system
// ---------------------------------------------------------------------------
// Delete multiple users from the system
function ajaxDeleteMultipleUsers(){
    for (const user of $('#userAdminTable').bootstrapTable('getSelections')){
        ajaxDeleteUser('#userAdminTable', user.id);
    }
    $('#userAdminTable').bootstrapTable('uncheckAll');
}
// End delete multiple users from the system
// ---------------------------------------------------------------------------
// Get all the users from the server and display as a table

function ajaxGetAllUsers(){
    $.ajax({
        url: api + 'users',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: 'GET',
        data: "",
        success: function(data) {
            displayTable('#userAdminTable', data, ['status', 'created_at']);
            displaySingleSection('userAdminSection');            
        }, // End of success
        error: function(data) {
            toastr["error"](data.responseJSON["error"]);
        } // End error
    }); // End ajax    
}
// End get all the users from the server and display as a table
// ---------------------------------------------------------------------------
// End User Administration Methods
// ---------------------------------------------------------------------------
// Start announcement administration methods
// ---------------------------------------------------------------------------
// Set up  the tiny MCE editor to suit the needs of the app
function setupTinyMCE(){
    // Prevent Bootstrap dialog from blocking focusin
    $(document).on('focusin', function(e) {
      if ($(e.target).closest(".mce-window").length) {
        e.stopImmediatePropagation();
      }
    });
    tinymce.init({
      selector: 'textarea#editor',
        menubar: false,
        toolbar: "bold alignleft aligncenter alignright alignjustify removeformat formatselect bullist numlist outdent, indent undo redo"
    });
}
// End setup tinyMCE
// ---------------------------------------------------------------------------
// Edit and delete actions for the announcement table

function announcementTableActions(value, row, index, field) {

    return [
                '<a class="" href="javascript:void(0)" onclick="updateAnnouncementModal('+row.id+', '+row.source+', \''+row.title+'\', \''+row.content+'\')" title="Edit" data-toggle="modal" data-target="#announcementUpdateModal"> ',
                '<i class="fa fa-pencil" aria-hidden="true"></i>',
                '</a> &nbsp; ',
                '<a class="" href="javascript:void(0)" onclick="ajaxDeleteAnnouncement(\'#announcementAdminTable\', '+row.id+')" title="Remove">',
                '<i class="fa fa-trash" aria-hidden="true"></i>',
                '</a>'
            ].join('');
}
// End edit and delete actions for the user table
// ---------------------------------------------------------------------------
// Set up  the new announcement modal
function setupAddAnnouncementModal(){
    $("#addAnnouncementForm").submit(function(e) {
        cancelDefaultBehaviour(e);
        ajaxAddAnnouncement(e, "#addAnnouncementForm");
    }); // End submit    
}
// End set up the new announcement modal
// ---------------------------------------------------------------------------
// Set up 
//function setupAddAnnouncementModal(){
    
//}
// End set up 
// ---------------------------------------------------------------------------
// Submit a security announcement to the server
function ajaxAddAnnouncement(e, announcementFormName) {
    var formData = $(announcementFormName).serialize();
    console.log(formData);
    $.ajax({
        url: api + 'announcement/submit/' + $('#announceSource').val().toLowerCase(),
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: 'POST',
        data: formData,
        success: function(data) {
            toastr["success"](data.success);
            console.log(data);
        }, // End of success
        error: function(data) {
            toastr["error"](data.responseJSON["error"]);
        } // End error
    }); // End ajax
}
// End submit a security announcement to the server
// ---------------------------------------------------------------------------
// Get all the users from the server and display as a table

function ajaxGetAllAnnouncements(){
    $.ajax({
        url: api + 'announcements',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: 'GET',
        data: "",
        success: function(data) {
            displayTable('#announcementAdminTable', data, ['status', 'created_at']);
            displaySingleSection('announcementAdminSection');            
        }, // End of success
        error: function(data) {
            toastr["error"](data.responseJSON["error"]);
        } // End error
    }); // End ajax    
}
// End get all the users from the server and display as a table
// ---------------------------------------------------------------------------// End announcement administration methods
// ---------------------------------------------------------------------------
// Set up  the actions on the menus and forms
(function() {
    setToasterDefaults();
    $("section[id$='Section']").each(function(){app.sectionList.push($(this).attr('id'));});
    // SideNav Options
    $('.button-collapse').sideNav({
        edge: 'left', // Choose the horizontal origin
        closeOnClick: true // Closes side-nav on <a> clicks
    });
    readAPIToken();
    if(loggedIn()) {
        setNavigationVisibility('on');
    } else {
        displaySingleSection('loginSection');
        $('#lEmail').focus();
    }
    setupLoginForm();
    setupRegisterForm();
    setupChangePasswordForm();
    setupNavigationMenu();
    $("#logoutButton").click(function(e) {
        logout();
    }); // End logoutButton.click
    $("#logoutIcon").click(function(e) {
        logout();
    }); // End logoutButton.click
    setupUserModalForm();
    setupAddUserModalForm();
    setupTinyMCE();
    setupAddAnnouncementModal();
})();
// End setup actions on menus and forms
// ---------------------------------------------------------------------------
