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
            displayForm('none');
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
// Logout code

function logout(e) {
    clearAPIToken();
    displayForm('loginSection');
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
// Only display one form at once

function displayForm(sectionId = 'none'){
    $('#navbarToggleExternalContent').collapse('hide');
    $( "section" ).each(function() {
      forms = $(this).find("form")
      id = this.id;
      if (forms.length > 0) {
          if(sectionId != 'none' && id == sectionId){
              setDisplay('#' + sectionId, 'on', 'd-block');              
          }
          else{
              setDisplay('#' + id, 'off', 'd-block');             
          }
      }
    });    
}
// ---------------------------------------------------------------------------
// Register form setup

function setupRegisterForm(){
    $("#registerForm").submit(function(e) {
        cancelDefaultBehaviour(e);
        ajaxRegister(e, "#registerForm");
    }); // End registerForm.submit
    $("#cancelButton").click(function(e) {
        displayForm('loginSection');
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
        displayForm('registerSection');
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
        displayForm('changePasswordSection');
    }); // End changePasswordButton.click
    $("#cancelPasswordChangeButton").click(function(e) {
        setDisplay('#changePasswordSection', 'off', 'd-block');
    }); // End endPasswordChangeButton.click    
}
// End change password form setup
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
// End Utility Methods
// ---------------------------------------------------------------------------
// Set up  the actions on the menus and forms
(function() {
    setToasterDefaults();
    // SideNav Options
    $('.button-collapse').sideNav({
        edge: 'left', // Choose the horizontal origin
        closeOnClick: true // Closes side-nav on <a> clicks
    });
    readAPIToken();
    if(loggedIn()) {
        setNavigationVisibility('on');
    } else {
        displayForm('loginSection');
        $('#lEmail').focus();
    }
    setupLoginForm();
    setupRegisterForm();
    setupChangePasswordForm();
    $("#logoutButton").click(function(e) {
        logout();
    }); // End logoutButton.click
    $("#logoutIcon").click(function(e) {
        logout();
    }); // End logoutButton.click
})();
// End setup actions on menus and forms
// ---------------------------------------------------------------------------