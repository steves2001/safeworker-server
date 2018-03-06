var api = 'https://toast-canvas-3000.codio.io/api/';
var token = '';

// ---------------------------------------------------------------------------
// Login/Password Related code
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
            loginSuccess();
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
            displayForm('loginSection');
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
//  ---------------------------------------------------------------------------
// Check user is logged in returns true if user is logged in

function loggedIn() {
    if(getAPIToken() == '') return false;
    else return true;
}
// ---------------------------------------------------------------------------
// End Login/Registration/Password  related code
// ---------------------------------------------------------------------------
// Study timer related code 
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
// Token related code
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
// ---------------------------------------------------------------------------
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

