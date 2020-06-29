
$(function() {
    let ajaxRequest;
    $('#loginForm').submit(function(event) {

        let username = $('#username');
        let password = $('#password');
        let loginError = $('#loginError');
        //empty username input
        if (username.val() === null || username.val() === '') {
            // .css has .error class
            loginError.addClass('error').text(' Username should not be empty !');
            //Give focus to an element
            username.focus();
            return false;
        }
        //set up min username length
        if(username.val().length < 5 ) {
            loginError.addClass('error').text(' Username should be not less than 5 characters !');
            username.focus();
            return false;
        }
        //empty password input
        if(password.val() === null || password.val() === '') {
            loginError.addClass('error').text(' Password can not be empty!');
            password.focus();
            return false;
        }
        //set password length range
        if(password.val().length < 7 || password.val().length > 15 ) {
            loginError.addClass('error').text(' Password must be between 7 to 15 alphanumeric characters!');
            password.focus();
            return false;
        }
        //prevent the link from the URL
        event.preventDefault();

        let loginForm = $(this).serialize();
        // If there is a pending request
        if(ajaxRequest) {
            ajaxRequest.abort();
        }

        ajaxRequest = $.ajax({
            url:'/customer/loginValidating',
            type:'POST',
            data: loginForm
        });

        ajaxRequest.done(function(response) {
            var mResponse = JSON.parse(response);
            if(!mResponse['statusCode']) {
                loginError.addClass('error').text(' '+mResponse['exception']);
            } else {
                window.location.replace("/");
            }
        });

        ajaxRequest.fail(function() {
            alert("request failed");
        });
    });
});