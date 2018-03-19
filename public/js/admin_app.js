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

// End global data
// ---------------------------------------------------------------------------
// Login/Registration/Password Related code
// ---------------------------------------------------------------------------
// Login success code

function loginSuccess(){
            displaySingleSection('none');
            setNavigationVisibility('on');    
}
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
// End Login/Registration/Password  related code
// ---------------------------------------------------------------------------
// Utility Methods
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
        displaySingleSection('');
        $('#addAnnouncementModal').modal('show')
    }); // End     
    $("#manageAnnouncements").click(function(e) { 
        ajaxGetAllAnnouncements();
        setTableButton('#announcementAdminToolbar', 'refresh', ajaxGetAllAnnouncements);
        setTableButton('#announcementAdminToolbar', 'delete', ajaxDeleteMultipleAnnouncements);
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
        displaySingleSection('logChartSection');
        ajaxGetActivityData();
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
                '<a class="" href="javascript:void(0)" onclick="updateRoleModal('
                +row.id+', \''
                +row.name+'\')" title="Edit Role" data-toggle="modal" data-target="#userRoleUpdateModal"> ',
                '<i class="fa fa-cogs" aria-hidden="true"></i>',
                '</a> &nbsp; ',
                '<a class="" href="javascript:void(0)" onclick="updateUserModal('
                +row.id+', \''
                +row.name+'\', \''
                +row.email+'\')" title="Edit" data-toggle="modal" data-target="#userUpdateModal"> ',
                '<i class="fa fa-pencil" aria-hidden="true"></i>',
                '</a> &nbsp; ',
                '<a class="" href="javascript:void(0)" onclick="ajaxDeleteUser(\'#userAdminTable\', '
                +row.id+')" title="Remove">',
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
       selector: '#addAnnouncementContent',
        menubar: false,
        toolbar: "bold alignleft aligncenter alignright alignjustify removeformat formatselect bullist numlist outdent, indent undo redo"
    });
    
    tinymce.init({
       selector: '#updateAnnouncementContent',
        menubar: false,
        toolbar: "bold alignleft aligncenter alignright alignjustify removeformat formatselect bullist numlist outdent, indent undo redo"
    });
}
// End setup tinyMCE
// ---------------------------------------------------------------------------
// Edit and delete actions for the announcement table

function announcementTableActions(value, row, index, field) {
    
    if (row.visible == "Y") 
        postVisibility = "fa fa-eye"
    else
        postVisibility = "fa fa-eye-slash"
    
    return [
                '<a class="" href="javascript:void(0)" onclick="updateAnnouncementVisibility(\''+ row.id +'\')" title="Change Visibility">',
                '<i class="' + postVisibility + '" aria-hidden="true"></i>',
                '</a> &nbsp; ',
                '<a class="" href="javascript:void(0)" onclick="updateAnnouncementModal('
                + row.id
                + ', '     + row.source
                + ', \''   + row.title
                + '\', \'' + encodeURIComponent(row.content)
                + '\')" title="Edit" data-toggle="modal" data-target="#updateAnnouncementModal"> ',
                '<i class="fa fa-pencil" aria-hidden="true"></i>',
                '</a> &nbsp; ',
                '<a class="" href="javascript:void(0)" onclick="ajaxDeleteAnnouncement( '+ row.id +' )" title="Remove">',
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
// Setup modal to submit data via ajax
function setupUpdateAnnouncementModalForm(){
    $("#updateAnnouncementForm").submit(function(e) {
        cancelDefaultBehaviour(e);
        ajaxUpdateAnnouncement($("#updateAnnouncementId").val(), $("#updateAnnouncementForm").serialize());
    });  
}
// End modal setup
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
            $('#addAnnouncementModal').modal('hide');
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
            displayTable('#announcementAdminTable', data, ['source', 'content', 'status', 'created_at', 'visible']);
            displaySingleSection('announcementAdminSection');            
        }, // End of success
        error: function(data) {
            toastr["error"](data.responseJSON["error"]);
        } // End error
    }); // End ajax    
}
// End get all the users from the server and display as a table
// ---------------------------------------------------------------------------
// Update user modal with data from the table row

function updateAnnouncementModal(id, sourceid, title, content){
     $("#updateAnnouncementId").val( id );
     $("#updateAnnouncementSourceId").val( sourceid );
     $("#updateAnnouncementTitle").val( title );
     tinyMCE.get('updateAnnouncementContent').setContent( decodeURIComponent(content) );    
}
// End update user modal
// ---------------------------------------------------------------------------
// Update user table row from user modal

function updateAnnouncementRowFromModal(){
    $('#announcementAdminTable').bootstrapTable('updateByUniqueId', {
        id: $("#updateAnnouncementId").val(),
        row: {
            source: $("#updateAnnouncementSourceId").val(),
            sourcename: $("#updateAnnouncementSourceId option:selected").text(),
            title: $("#updateAnnouncementTitle").val(),
            content: tinymce.activeEditor.getContent(),
            status: null
        }
    });
}
// End update user table row from user modal
// ---------------------------------------------------------------------------
// Update announcement using ajax

function ajaxUpdateAnnouncement(announcementId, announcementData){
    rowData = $('#announcementAdminTable').bootstrapTable('getRowByUniqueId', announcementId);
    if(rowData.status) return;
    console.log(rowData);
    $('#announcementAdminTable').bootstrapTable('updateByUniqueId', { id: announcementId, row: { status: 'UPDATING' } });
    $.ajax({
        url: api + 'announcements/' + announcementId,
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: 'PATCH',
        data: announcementData,
        statusCode: {
            400: function(data) {
                toastr["error"]('Update operation failed, sent data missing either sourceid, title or content');
                $('#announcementAdminTable').bootstrapTable('updateByUniqueId', { id: data.responseJSON["id"], row: { status: null } });
            },
            404: function(data) {
                toastr["error"]('Update operation failed, user could not be found');
                $('#announcementAdminTable').bootstrapTable('updateByUniqueId', { id: data.responseJSON["id"], row: { status: 'ERROR' } });
            },
            409: function(data) {
                toastr["error"]('Update operation failed, there was a server conflict during update');
                $('#announcementAdminTable').bootstrapTable('updateByUniqueId', { id: data.responseJSON["id"], row: { status: null } });
            }            
        },
        success: function(user, status) {
            updateAnnouncementRowFromModal();
            $('#updateAnnouncementModal').modal('hide');
            toastr["success"]('Announcement was updated on the the system'); 
        }, // End of success
        error: function(data) {
            console.log(data);
        } // End error
    }); // End ajax    
}
// End update announcement
// ---------------------------------------------------------------------------
// Delete a announcement from the system

function ajaxDeleteAnnouncement(announcementId){
    rowData = $('#announcementAdminTable').bootstrapTable('getRowByUniqueId', announcementId);
    if(rowData.status) return;
    $('#announcementAdminTable').bootstrapTable('updateByUniqueId', { id: announcementId, row: { status: 'DELETING' } });
    $.ajax({
        url: api + 'announcements/' + announcementId,
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: 'DELETE',
        data: "",
        statusCode: {
            404: function(data) {
                toastr["error"]('Delete operation failed, announcement could not be found');
                $('#announcementAdminTable').bootstrapTable('updateByUniqueId', { id: data.responseJSON["id"], row: { status: 'ERROR' } });
            }            
        },
        success: function(announcement, status) {
            $('#announcementAdminTable').bootstrapTable('removeByUniqueId', announcement.id);
            toastr["success"]('Announcement was deleted from the system'); 
        }, // End of success
        error: function(data) {
            console.log(data.responseJSON["id"]);
        } // End error
    }); // End ajax    
}
// End delete announcement from system
// ---------------------------------------------------------------------------
// Delete multiple announcements from the system
function ajaxDeleteMultipleAnnouncements(){
    for (const announcement of $('#announcementAdminTable').bootstrapTable('getSelections')){
        ajaxDeleteAnnouncement(announcement.id);
    }
    $('#announcementAdminTable').bootstrapTable('uncheckAll');
}
// End delete multiple announcements from the system
// ---------------------------------------------------------------------------
function updateAnnouncementVisibility(announcementId){
    data = $('#announcementAdminTable').bootstrapTable('getRowByUniqueId', announcementId);
    
    if(data.status) return;
    $('#announcementAdminTable').bootstrapTable('updateByUniqueId', { id: announcementId, row: { status: 'UPDATING' } });
    
    if (data.visible == 'Y') {
        data.visible = 'N';
    }    
    else {
        data.visible = 'Y';
    }
    
    $.ajax({
        url: api + 'announcements/' + announcementId,
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: 'PATCH',
        data: data,
        visibility: data.visible,
        statusCode: {
            400: function(data) {
                toastr["error"]('Update operation failed, sent data missing either sourceid, title or content');
                $('#announcementAdminTable').bootstrapTable('updateByUniqueId', { id: data.responseJSON["id"], row: { status: null } });
            },
            404: function(data) {
                toastr["error"]('Update operation failed, user could not be found');
                $('#announcementAdminTable').bootstrapTable('updateByUniqueId', { id: data.responseJSON["id"], row: { status: 'ERROR' } });
            },
            409: function(data) {
                toastr["error"]('Update operation failed, there was a server conflict during update');
                $('#announcementAdminTable').bootstrapTable('updateByUniqueId', { id: data.responseJSON["id"], row: { status: null } });
            }            
        },
        success: function(announcement, status){
                $('#announcementAdminTable').bootstrapTable('updateByUniqueId', { id: announcement.id, row: { visible: this.visibility, status: null } });
                toastr["success"]('Announcement was updated on the the system'); 
                console.log(announcement);    
        }, // End of success
        error: function(data) {
            console.log(data);
        } // End error
    }); // End ajax    

   console.log(data);
    
}
// ---------------------------------------------------------------------------
// End announcement administration methods
// ---------------------------------------------------------------------------
// Start log administration methods
// ---------------------------------------------------------------------------
// Start display chart
function ajaxGetActivityData(startDate = '2017-09-01', endDate = '2018-09-01', groupBy = '%y-%v'){
    searchParameters = {
        start: startDate,
        end: endDate,
        grouping: groupBy
    }
    
    $.ajax({
        url: api + 'activities/chart/activity',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: 'POST',
        data: searchParameters,
        success: function(data) {
            displayLogsAsChart(data);
            
            console.log(data);
            
        }, // End of success
        error: function(data) {
            toastr["error"](data.responseJSON["error"]);
        } // End error
    }); // End ajax

}

function displayLogsAsChart(ajaxData){

    var labels = [];
    var data = [];
    var headings = [];
    var series = [];
    var dataSets = [];
    var colours = ['0,0,220','220,0,220', '0,220,0','220,0,0','0,220,220','220,220,0','220,220,220'];
    for (let chartPoint of ajaxData){
        if(headings.indexOf(chartPoint.label) == -1){
            headings.push(chartPoint.label);
        }
        if(series.indexOf(chartPoint.Location) == -1){
            series.push(chartPoint.Location);
            labels[chartPoint.Location] = [];
            data[chartPoint.Location] = [];
        }
        labels[chartPoint.Location].push(chartPoint.label);
        data[chartPoint.Location].push(chartPoint.data);
    }
    var colourCounter = 0;
    for (let currentSeries of series){
        let rowData = [];
        for (let heading of headings){
            if(labels[currentSeries].indexOf(heading) == -1){
                rowData.push(0);
            }
            else {
                rowData.push(data[currentSeries][labels[currentSeries].indexOf(heading)]);
            }
        }
        
        colourIndex = colourCounter % colours.length;
        
        dataSets.push({
            label: currentSeries,
            fillColor: "rgba(" + colours[colourCounter % colours.length] + ",0.2)",
            strokeColor: "rgba(" + colours[colourCounter % colours.length] + ",1)",
            pointColor: "rgba(" + colours[colourCounter % colours.length] + ",1)",
            pointStrokeColor: "#fff",
            backgroundColor : "rgba(" + colours[colourCounter % colours.length] + ",0.2)",
            borderWidth : 2,
            borderColor : "rgba(" + colours[colourCounter % colours.length] + ",1)",
            pointBackgroundColor : "rgba(" + colours[colourCounter % colours.length] + ",1)",
            pointBorderColor : "#fff",
            pointBorderWidth : 1,
            pointRadius : 4,
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(" + colours[colourCounter % colours.length] + ",1)",
            pointHoverBackgroundColor : "#fff",
            pointHoverBorderColor :"rgba(" + colours[colourCounter % colours.length] + ",1)",
            data: rowData
        });
        colourCounter++;
    }

    var ctxL = document.getElementById("lineChart").getContext('2d');
    var myLineChart = new Chart(ctxL, {
        type: 'line',
        data: {
            labels: headings,
            datasets: dataSets
        },
        options: {
            responsive: true
        }    
    });    
    
}
// End display chart
function setupLogHistoryChart(){
    
$("#updateLogHistoryChartButton").click(function(e) {
    
    // Test for correct date format
    let datePattern = new RegExp("^[0-9]{2}-[0-9]{2}-[0-9]{4}", "i");
    
    if( datePattern.test($('#chartStarts').val()) &&  datePattern.test($('#chartEnds').val())) {
        
        // Check for same start end date
        if ($('#chartStarts').val() === $('#chartEnds').val()) {
            toastr["error"]('Start and end dates must be different');
            return;
        }
        
        let startDate = $('#chartStarts').val();
        let endDate   = $('#chartEnds').val();
        let groupBy   = $("input:radio[name ='weekMonth']:checked").val();
        
        // Swap date order dd-mm-yyyy to yyyy-mm-dd for query
        
        startDate = startDate.substring(6) + startDate.substring(2,6) + startDate.substring(0,2);
        endDate   = endDate.substring(6) + endDate.substring(2,6) + endDate.substring(0,2);
        
        // call the chart 
        ajaxGetActivityData(startDate, endDate, groupBy);
    }
    else
        toastr["error"]('Please specify both start and end dates');
});
    
$('#chartdatepicker').datepicker({
    format: "dd-mm-yyyy",
    startDate: "-5y",
    endDate: new Date(),
    todayHighlight: true
});

}
// ---------------------------------------------------------------------------
// End log administration methods
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
    setupUpdateAnnouncementModalForm();
    setupLogHistoryChart();
})();
// End setup actions on menus and forms
// ---------------------------------------------------------------------------
