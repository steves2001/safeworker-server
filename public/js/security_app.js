// ---------------------------------------------------------------------------
// Security Study Safe Application 
// ---------------------------------------------------------------------------
// A Progressive Web Application linked to a laravel API, to log students 
// working alone in study areas and to provide safeguarding contacts to 
// learners
// ---------------------------------------------------------------------------
    var app = {
        daysOfWeek: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        studyEndTime: 0,
        studyTimer: 0,
        securityTimer: 0,
        securityData: null
    };

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
// Submit a security announcement to the server
function ajaxSubmitAnnouncement(e, announcementFormName) {
    var formData = $(announcementFormName).serialize();
    console.log(formData);
    $.ajax({
        url: api + 'announcement/submit/security',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: 'POST',
        data: formData,
        success: function(data) {
            setDisplay('#postAnnouncementSection', 'off', 'd-block');
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
// Login success code

function loginSuccess(){
    setDisplay('#loginSection', 'off', 'd-block');
    navigationButtons('on');
    retrieveSecurityActivities();
    enableSecurityTimer();    
}

// ---------------------------------------------------------------------------
// Logout code

function logout(e) {
    clearAPIToken();
    setDisplay('#changePasswordSection', 'off', 'd-block');
    setDisplay('#postAnnouncementSection', 'off', 'd-block');
    setDisplay('#loginSection', 'on', 'd-block');
    navigationButtons('off');
    $('#navbarToggleExternalContent').collapse('hide');
    $('#announcements').empty();
    //}
    disableSecurityTimer();
}
// End Logout code
// ---------------------------------------------------------------------------
// Utility Methods
// ---------------------------------------------------------------------------
// Show/Hide the sidebar and its navigation buttons

function navigationButtons(display = 'off'){
    setDisplay('#createAnnouncementButton', display, 'd-block');
    setDisplay('#changePasswordButton', display, 'd-block');
    setDisplay('#logoutButton', display, 'd-block');
    setDisplay('#navBurger', display, 'd-block');
}
// ---------------------------------------------------------------------------
// End Utility Methods
// ---------------------------------------------------------------------------
// Security related code
// ---------------------------------------------------------------------------
// Retrieve learners who have logged on site study

function retrieveSecurityActivities() {
    $.ajax({
        url: api + 'activity/list/active',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: 'GET',
        data: {
            filter: 1
        },
        success: function(data) {
            displayActiveLearners(data.success);
            app.securityData = data.success;
            //disableSecurityTimer();
            //enableSecurityTimer();
            console.log(data);
        }, // End of success
        error: function(data) {
            console.log("Debug retrieveSecurityActivities() failed");
            console.log(data);
        } // End error
    }); // End ajax            
}
// End retrieve active learners
// ---------------------------------------------------------------------------
// Create individual cards for each learner activity logged on the system

function displayActiveLearners(activityData) {
    $('#announcements').empty();
    var currentTime = new Date();
    $.each(activityData, function(index, value) {
        card = $('#security-card section:first-child').clone();
        card.attr('id', 'card' + value.id);

        var endDate = new Date(value.endtime);
        var remainingTime = endDate.getTime() - currentTime.getTime();
        
        if(remainingTime > 0) {
            card.find('#time-').text(createTimeString(remainingTime));
            card.find('.security-card-header').addClass(calcActivityColour(value.active, value.accepted, 1));
            
            if(value.accepted == 'Y') {
                card.find('#btn-accept-activity_').addClass('d-none');
            } 
            else{
                card.find('#btn-clear-activity_').addClass('d-none');
            }
        } else {
            card.find('#time-').text(value.endtime);
            card.find('.security-card-header').removeClass('activity-expired').removeClass('activity-active').removeClass('activity-accepted').addClass(calcActivityColour(value.active, value.accepted, remainingTime));
            card.find('#btn-accept-activity_').addClass('d-none');
        }

        card.find('#time-').attr('id', 'time-' + value.id);
        card.find('#btn-accept-activity_').attr('id', 'btn-accept-activity_' + value.id);
        card.find('#btn-cancel-activity_').attr('id', 'btn-cancel-activity_' + value.id);
        card.find('#btn-clear-activity_').attr('id', 'btn-clear-activity_' + value.id);
        card.find('.security-card-toggle').attr('data-target', '#security-card-text' + value.id)
        card.find('#security-card-text').attr('id', 'security-card-text' + value.id)
        card.find('.name').text(value.name);
        card.find('.security-location').text(value.location);
        card.find('.security-telephone').text(value.phone);
        card.find('.security-message').text(value.message);
        card.find('.security-escort').text(value.escortrequired);
        $('#announcements').append(card);
        $('#btn-accept-activity_' + value.id).click(function(e) {
            acceptSecurityActivity(e, this.id);
        });
        $('#btn-cancel-activity_' + value.id).click(function(e) {
            cancelSecurityActivity(e, this.id);
        });
        $('#btn-clear-activity_' + value.id).click(function(e) {
            cancelSecurityActivity(e, this.id);
        });
    });
}
// End create activity cards
// ---------------------------------------------------------------------------
// Update the security card status refreshes every second

function updateSecurityCards() {
    var currentTime = new Date();
    $.each(app.securityData, function(index, value) {
        var endDate = new Date(value.endtime);
        var remainingTime = endDate.getTime() - currentTime.getTime();
        if(remainingTime > 0) {
            updateSecurityTimer('#time-' + value.id, createTimeString(remainingTime));
        } else {
            $('#time-' + value.id).text(value.endtime);
            $('#card' + value.id + ' .security-card-header').removeClass('activity-expired').removeClass('activity-active').removeClass('activity-accepted').addClass(calcActivityColour(value.active, value.accepted, remainingTime));
        }
    });
}
// ---------------------------------------------------------------------------
// Security timer refreshes every second

function securityTimer() {
    var currentTime = new Date();
    // Every minute retrieve the current activty status of the learners
    if(Math.floor(currentTime.getTime() / 1000) % 60 == 0) 
        retrieveSecurityActivities();
    else
        updateSecurityCards();
}
// End  security timer
// ---------------------------------------------------------------------------
// Set the activity status colour based on expired/active/accepted by security state

function calcActivityColour(active, accepted, timer) {
    if(timer <= 0 || (active == 'N' && accepted == 'N')) return 'activity-expired';
    if(active == 'Y' && accepted == 'N') return 'activity-active';
    if(active == 'Y' && accepted == 'Y') return 'activity-accepted';
    return 'activity-expired';
}
// End calc activity colour
// ---------------------------------------------------------------------------
// Ajax call to accept an activity extracts record id from css/html id name

function acceptSecurityActivity(e, id) {
    var databaseId = id.substr(id.indexOf('_') + 1, id.length);
    $.ajax({
        url: api + 'activity/accept',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: 'PUT',
        data: {
            filter: databaseId
        },
        success: function(data) {
            //disableSecurityTimer();
            retrieveSecurityActivities();
            //displayActiveLearners(app.securityData);
            updateSecurityCards();
            //enableSecurityTimer();
            console.log(data);
        }, // End of success
        error: function(data) {
            console.log("Debug acceptSecurityActivity(e, id) failed");
            console.log(data);
        } // End error
    }); // End ajax 
    console.log("Security Timer Accepted " + databaseId);
}
// End security accept activity
// ---------------------------------------------------------------------------
// Cancel an active id on the system extracts record id from css/htmnl id name

function cancelSecurityActivity(e, id) {
    var databaseId = id.substr(id.indexOf('_') + 1, id.length);
    $.ajax({
        url: api + 'activity/clear',
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getAPIToken()
        },
        type: 'PUT',
        data: {
            filter: databaseId
        },
        success: function(data) {
            //disableSecurityTimer();
            retrieveSecurityActivities();           
            //displayActiveLearners(app.securityData);
            updateSecurityCards();
            //enableSecurityTimer();
            console.log(data);
        }, // End of success
        error: function(data) {
            console.log("Debug cancelSecurityActivity(e, id) failed");
            console.log(data);
        } // End error
    }); // End ajax    
    console.log("Security Activity Cancelled " + databaseId);
}
// End security cancel activity
// ---------------------------------------------------------------------------
// Enable Security Timer sets the security timer to 1 second

function enableSecurityTimer() {
    securityTimer();
    app.securityTimer = setInterval(function() {
        securityTimer()
    }, 1000);
    console.log("Security Timer Started");
}
// End enableSecurityTimer
// ---------------------------------------------------------------------------
// Update the security Timer

function updateSecurityTimer(id, timeString) {
    $(id).text(timeString);
}
// End update the security timer
// ---------------------------------------------------------------------------
// Disable the security timer

function disableSecurityTimer() {
    clearInterval(app.securityTimer);
    console.log("Security Timer Cancelled");
}
// End disable the security timer
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
        navigationButtons('on');
        retrieveSecurityActivities();
        updateSecurityCards();
        enableSecurityTimer();
    } else {
        setDisplay('#loginSection', 'on', 'd-block');
    }
    $("#loginForm").submit(function(e) {
        cancelDefaultBehaviour(e)
        ajaxLogin(e, "#loginForm");
    }); // End loginForm.submit
    $("#changePasswordForm").submit(function(e) {
        cancelDefaultBehaviour(e);
        ajaxChangePassword(e, "#changePasswordForm");
    }); // End changePasswordForm.submit    
    $("#changePasswordButton").click(function(e) {
        setDisplay('#postAnnouncementSection', 'off', 'd-block');
        setDisplay('#changePasswordSection', 'on', 'd-block');
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
    // Create a security announcement 
    $("#createAnnouncementButton").click(function(e) {
        setDisplay('#changePasswordSection', 'off', 'd-block');
        setDisplay('#postAnnouncementSection', 'on', 'd-block');
    }); // End changePasswordButton.click
    $("#postAnnouncementForm").submit(function(e) {
        cancelDefaultBehaviour(e)
        ajaxSubmitAnnouncement(e, "#postAnnouncementForm");
    }); // End postAnnouncementForm.submit
    $("#cancelPostAnnouncement").click(function(e) {
        setDisplay('#postAnnouncementSection', 'off', 'd-block');
    }); // End endPasswordChangeButton.click

    //  Check to see if there is still a lone working timer that is current
    $('#securityIndicator').click(function(e) {
        retrieveSecurityActivities();
    });
})();
// End setup actions on menus and forms
// ---------------------------------------------------------------------------