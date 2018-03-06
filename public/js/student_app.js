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
// Student enquiry related code
// ---------------------------------------------------------------------------
function setupEnquiryForm(){
    $('#select-contact .dropdown-item').click(function(e) {
        $('#select-contact button').prop('value', $(this).text());
        $('#select-contact button').text($(this).text());
    });    
}
// // Start send enquiry
function ajaxEnquiry(e, enquiryFormName){
    
    // Build data for ajax call
    enquiryFormData = {
        contact: $('#dropdownDestination').attr('value').replace(/ +/g, "").toLowerCase(),
        enquiryMessage: $('#contactMessage').val()
    };
    
    console.log(enquiryFormData);
    
    $.ajax({
        url: api + 'contact',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: 'POST',
        data: enquiryFormData,
        success: function(data) {
            setDisplay('#requestSupportSection', 'off', 'd-block');
            toastr["success"](data.success);            
            console.log(data);
        }, // End of success
        error: function(data) {
            toastr["error"](data.responseJSON["error"]);
            console.log(data);
        } // End error
    });
    
}
// End send enquiry
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
// Study timer - updates onscreen timer or disables it if countdown completed

function studyTimer() {
    var currentTime = new Date();
    var remainingTime = app.studyEndTime - currentTime.getTime();
    if(remainingTime > 0) {
        updateStudyTimer(createTimeString(remainingTime));
        if(Math.floor(remainingTime / 1000) % 20 == 0) checkLoneWorking();
    } else {
        disableStudyTimer();
    }
}
// End study timer
// ---------------------------------------------------------------------------
// Set up and enable the study timer to refresh every second

function enableStudyTimer() {
    studyTimer();
    app.studyTimer = setInterval(function() {
        studyTimer()
    }, 1000);
    app.studyTimerRunning = true;
    console.log("Study Timer Started");
}
// End enable the study timer
// ---------------------------------------------------------------------------
// Update the study timer on screen

function updateStudyTimer(timeString) {
    $('#loneWorkingText').text("Cancel " + timeString);
}
// End update study timer
// ---------------------------------------------------------------------------
// Disables the study timer

function disableStudyTimer() {
    clearInterval(app.studyTimer);
    app.studyTimerRunning = false;
    showLoneWorkingFormElements();
    console.log("Study Timer Cancelled");
}
// End disable study timer
// ---------------------------------------------------------------------------
// Retrieves the status of a lone working request from the server, will change
// the colour of the icon on screen to green if security has accepted the
// notification.

function getSecurityStatus() {
    if(loggedIN()) $.ajax({
        url: api + 'activity/status',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: 'GET',
        data: {
            userToken: getAPIToken()
        },
        success: function(data) {
            if(data.hasOwnProperty('success') && data.success == null) {
                console.log("Cancel activity success");
                $('#navbarToggleExternalContent').collapse('hide');
                setDisplay('#cancelLoneWorking', 'off', 'd-block');
                setDisplay('#inputLoneWorking', 'on', 'd-block');
                disableStudyTimer();
            }
            if(data.hasOwnProperty('success') && data.success != null && data.success.hasOwnProperty('accepted') && data.success.accepted == 'Y') {
                $('#securityIndicator').removeClass('grey-text').addClass('light-green-text');
            } else {
                $('#securityIndicator').removeClass('light-green-text').addClass('grey-text');
            }
        }, // End of success
        error: function(data) {
            console.log("Security Failed Response Data");
            console.log(data);
        } // End error
    }); // End ajax
}
// End getSecurityStatus
// ---------------------------------------------------------------------------
// Retrieve announcements - retrieves announcements from server

function retrieveAnnouncements(e, sourceId, nextPage = 1) {
    console.log('Calling announcement');
    $('#more-information').prop('value', sourceId + '#');
    $.ajax({
        url: api + 'announcements',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: 'GET',
        data: {
            source: sourceId.replace(/^(Announce-)/, ""),
            page: nextPage
        },
        statusCode: {
            204: function(data) {
                toastr["error"]("There is no information for this section");
                $('#announcements').empty();
                displayMoreInformationButton();
            }, // 204
        
            200: function(data) {
                console.log("Announcement Response Data");
                console.log(data);
                if(nextPage == 1) $('#announcements').empty();
                displayAnnouncements(data.success);
                displayMoreInformationButton(data.success.next_page_url);
            }, // 200
        },  // End of success
        error: function(data) {
            toastr["error"](data.responseJSON["error"]);          
            console.log("Announcement Failed Response Data");
            console.log(data.responseText);
        } // End error
    }); // End ajax
}
// End retrieveAnnouncements
// ---------------------------------------------------------------------------
// Display announcements builds a card for each announcement and adds it to 
// the screen.

function displayAnnouncements(announcementData) {
    var lastCardId = 0;
    var cardCount = 0;
    displayForm('none');
    $.each(announcementData.data, function(index, value) {
        card = $('#announcement-card section:first-child').clone();
        card.attr('id', 'card' + value.id);
        card.find('.card-title').text(value.title);
        card.find('.card-text').html(value.content);
        $('#announcements').append(card);
        lastCardId = value.id;
        cardCount++;
    });
    if(cardCount % 2 == 1) {
        $('#card' + lastCardId).removeClass('col-sm-6').addClass('col-12');
    }
}
// End display announcements
// ---------------------------------------------------------------------------
// Shows or hides the more information button if there is another page param
// in the url passed to it.

function displayMoreInformationButton(url = null) {
    if(url != null) {
        $('#more-buttons').removeClass('d-none');
        var nextPage = url.match(/page=(.*)/);
        if(nextPage) $('#more-information').prop('value', $('#more-information').prop('value') + nextPage[1]);
    } else {
        $('#more-buttons').addClass('d-none');
    }
}
// End display more information button
// ---------------------------------------------------------------------------
// Lone Working Related Code.  Performs the key actions required to set the 
// system to show lone working
// ---------------------------------------------------------------------------
// Enable lone working resets the timer and hides the data entry part of the
// lone working form

function enableLoneWorking() {
    disableStudyTimer();
    enableStudyTimer();
    $('#navbarToggleExternalContent').collapse('hide');
    hideLoneWorkingFormElements();
}
// End lone working
// ---------------------------------------------------------------------------
// Show lone working form
function showLoneWorkingFormElements(){
    setDisplay('#cancelLoneWorking', 'off', 'd-block');
    setDisplay('#inputLoneWorking', 'on', 'd-block');
    $('#loneWorkingText').text('Report Lone Working');
    $('#securityIndicator').removeClass('light-green-text').addClass('grey-text');    
}
// End show lone working form
// ---------------------------------------------------------------------------
// Hide lone working form
function hideLoneWorkingFormElements(){
    setDisplay('#cancelLoneWorking', 'on', 'd-block');
    setDisplay('#inputLoneWorking', 'off', 'd-block');    
}
// End hide lone working form
// Check lone working queries the server to check there is a valid 
// ---------------------------------------------------------------------------
// lone working record (it may have expired or security cancelled)

function checkLoneWorking() {
    if(loggedIn()) $.ajax({
        url: api + 'activity/status',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: 'GET',
        data: {
            userToken: getAPIToken()
        },
        success: function(data) {
            // Disable the timer if there is no data regarding lone working from the system.
            if(data.hasOwnProperty('success') && data.success == null) {
                console.log("Cancel activity success");
                $('#navbarToggleExternalContent').collapse('hide');
                setDisplay('#cancelLoneWorking', 'off', 'd-block');
                setDisplay('#inputLoneWorking', 'on', 'd-block');
                disableStudyTimer();
                $('#securityIndicator').removeClass('light-green-text').addClass('grey-text');
                return
            }
            var endDate = new Date(data.success.endtime);
            app.studyEndTime = endDate.getTime();
            if(localStorage) {
                localStorage.setItem('studyEndTime', app.studyEndTime);
            }
            if(!app.studyTimerRunning) enableLoneWorking();
            // Remove the green from the security indicator if security isn't aware.
            if(data.hasOwnProperty('success') && data.success != null && data.success.hasOwnProperty('accepted') && data.success.accepted == 'Y') {
                $('#securityIndicator').removeClass('grey-text').addClass('light-green-text');
            } else {
                $('#securityIndicator').removeClass('light-green-text').addClass('grey-text');
            }
        }, // End of success
        error: function(data) {
            console.log("checkLoneWorking() Failed Response Data");
            console.log(data);
        } // End error
    }); // End ajax    
}
// End check lone working
// ---------------------------------------------------------------------------
// Setup lone working form configures the events for each of the form elements

function setupLoneWorkingForm() {
$('#select-hours .dropdown-item').click(function(e) {
    $('#select-hours button').prop('value', $(this).text());
    $('#select-hours button').text($(this).text() + ' Hrs');
});
$('#select-minutes .dropdown-item').click(function(e) {
    $('#select-minutes button').prop('value', $(this).text());
    $('#select-minutes button').text($(this).text() + ' Mins');
});
$('#select-location .dropdown-item').click(function(e) {
    $('#select-location button').prop('value', $(this).text());
    $('#select-location button').text($(this).text());
});
$('#timerButton').click(function(e) {
    $("html, body").animate({
        scrollTop: 0
    }, "slow");
});
$('#stopButton').click(function(e) {
    ajaxCancelLoneWorking();
});
$('#startButton').click(function(e) {
    
    studyObject = validateLoneWorkingForm();
    
    if(studyObject != null) 
        ajaxRegisterLoneWorking(studyObject);
    
    return;
});
}
// End setup lone working form
// ---------------------------------------------------------------------------
// Validate lone working form

function validateLoneWorkingForm() {
    var validData = true;
    var studyLocation = $('#select-location button').attr('value');
    var studyHours = $('#select-hours button').attr('value');
    var studyMins = $('#select-minutes button').attr('value');
    var studyTotalTime = parseInt(studyHours) * 60 + parseInt(studyMins);
    var studyPhone = $('#phoneNumber').val();
    var studyMessage = $('#securityMessage').val();
    var studyEscort = '';
    // Validation
    if($('#securityMessageToggle').hasClass('show')) {
        studyEscort = 'Y'
        if(studyPhone.length < 11) {
            toastr["warning"]("Security need a contact number");
            validData = false;
        }
    } else {
        studyEscort = 'N'
    }
    if(studyLocation == 'Unknown') {
        toastr["warning"]("You must select a location");
        validData = false;
    }
    if(studyTotalTime == 0) {
        toastr["warning"]("How long will you be studying?");
        validData = false;
    }
    // Validation failed
    if(!validData) {
        return null;
    }
    // Validation success build data for ajax
    loneWorkingFormData = {
        userToken: getAPIToken(),
        location: studyLocation,
        duration: studyTotalTime,
        phone: studyPhone,
        message: studyMessage,
        escort: studyEscort
    };
    
    return loneWorkingFormData;
}
// End validate lone working form
// ---------------------------------------------------------------------------
// Register lone working request on system

function ajaxRegisterLoneWorking(studyObject) {
    $.ajax({
        url: api + 'activity/log',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: 'POST',
        data: studyObject,
        success: function(data) {
            console.log("Log activity success");
            toastr["info"]("Lone working logged on system");
            console.log(data);
            var endDate = new Date(data.success.endtime);
            app.studyEndTime = endDate.getTime();
            if(localStorage) {
                localStorage.setItem('studyEndTime', app.studyEndTime);
            }
            enableLoneWorking();
        }, // End of success
        error: function(data) {
            toastr["error"]("Unable to store on system");
            console.log("Log activity failed");
            console.log(data.responseText);
        } // End error
    }); // End ajax    
}
// End register lone working request
// ---------------------------------------------------------------------------
// Student cancel lone working request

function ajaxCancelLoneWorking() {
    $.ajax({
        url: api + 'activity/cancel',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: 'PUT',
        data: {
            userToken: getAPIToken()
        },
        success: function(data) {
            console.log("Cancel activity success");
            $('#navbarToggleExternalContent').collapse('hide');
            setDisplay('#cancelLoneWorking', 'off', 'd-block');
            setDisplay('#inputLoneWorking', 'on', 'd-block');
            disableStudyTimer();
            toastr["error"]("Lone working log cancelled");
        }, // End of success
        error: function(data) {
            toastr["error"]("Error cancelling, contact security");
            console.log("Cancel activity failed");
            console.log(data.responseText);
        } // End error
    }); // End ajax     
}
// End student cancel lone working request
// ---------------------------------------------------------------------------
// End Lone Working Related Code
// ---------------------------------------------------------------------------
// ---------------------------------------------------------------------------
// Login/Registration/Password Related code
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
            setDisplay('#loginSection', 'off', 'd-block');
            setDisplay('#changePasswordButton', 'on', 'd-block');
            setDisplay('#logoutButton', 'on', 'd-block');
            setDisplay('#navBurger', 'on', 'd-block');
            setDisplay('#timerButton', 'on', 'd-block');
            checkLoneWorking();
            retrieveAnnouncements(e, 'Announce-3');
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
    setDisplay('#changePasswordButton', 'off', 'd-block');
    setDisplay('#logoutButton', 'off', 'd-block');
    setDisplay('#navBurger', 'off', 'd-block');
    setDisplay('#timerButton', 'off', 'd-block');
    $('#announcements').empty();
    displayMoreInformationButton();
    disableStudyTimer();
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
        setDisplay('#changePasswordButton', 'on', 'd-block');
        setDisplay('#logoutButton', 'on', 'd-block');
        setDisplay('#navBurger', 'on', 'd-block');
        setDisplay('#timerButton', 'on', 'd-block');
    } else {
        displayForm('loginSection');
        $('#lEmail').focus();
    }
    $("#loginForm").submit(function(e) {
        cancelDefaultBehaviour(e)
        ajaxLogin(e, "#loginForm");
    }); // End loginForm.submit
    $("#registerForm").submit(function(e) {
        cancelDefaultBehaviour(e);
        ajaxRegister(e, "#registerForm");
    }); // End registerForm.submit
    $("#changePasswordForm").submit(function(e) {
        cancelDefaultBehaviour(e);
        ajaxChangePassword(e, "#changePasswordForm");
    }); // End changePasswordForm.submit        
    $("#requestSupportForm").submit(function(e) {
        cancelDefaultBehaviour(e)
        ajaxEnquiry(e, "#requestSupportForm");
    }); // End requestSupportForm.submit
    $("#changePasswordButton").click(function(e) {
        displayForm('changePasswordSection');
    }); // End changePasswordButton.click
    $("#cancelPasswordChangeButton").click(function(e) {
        setDisplay('#changePasswordSection', 'off', 'd-block');
    }); // End endPasswordChangeButton.click
    $("#recoverButton").click(function(e) {
        forgotPassword($('#lEmail').val());
    }); // End recoverButton.click
    $("#logoutButton").click(function(e) {
        logout();
    }); // End logoutButton.click
    $("#registerButton").click(function(e) {
        displayForm('registerSection');
        //setDisplay('#loginSection', 'off', 'd-block');
        //setDisplay('#registerSection', 'on', 'd-block');
    }); // End registerButon.click
    $("#cancelButton").click(function(e) {
        displayForm('loginSection');
        //setDisplay('#loginSection', 'on', 'd-block');
        //setDisplay('#registerSection', 'off', 'd-block');
    }); // End cancelButon.click  
    $("#requestSupportButton").click(function(e) {
        displayForm('requestSupportSection');
    }); // End requestSupportButton.click
    $("#cancelSupportRequest").click(function(e) {
        setDisplay('#requestSupportSection', 'off', 'd-block');
    }); // End cancelSupportRequest.click
    //  Check to see if there is still a lone working timer that is current
    checkLoneWorking();
    // Set up AJAX call to retrieve the announcement data from the server
    $('[id^=Announce-]').click(function(e) {
        retrieveAnnouncements(e, this.id);
    });
    $('#more-information').click(function(e) {
        var value = $(this).prop("value")
        var id = value.substr(0, value.indexOf('#'));
        var page = value.substr(value.indexOf('#') + 1, value.length);
        retrieveAnnouncements(e, id, page);
    });
    // End announcement
    setupLoneWorkingForm();
    setupEnquiryForm();
})();
// End setup actions on menus and forms
// ---------------------------------------------------------------------------