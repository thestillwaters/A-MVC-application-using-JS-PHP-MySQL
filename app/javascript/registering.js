
$(function() {
    let ajaxRequest;
    let usernameRegex = new RegExp("^[a-zA-Z0-9]+$");
    let passwordRegex = new RegExp("^(?=.*[A-Z])[a-zA-Z0-9]+$");
    let emailRegex = new RegExp("^[a-zA-Z0-9]+@[a-zA-Z0-9]+\\.[a-zA-Z0-9]{2,}$");
    let registerForm = $('#registerForm');

    registerForm.submit(function(event) {

        let name,username,email,password,repeat,registerError,submit;

        registerError = $ ('#registerError');
        name = $('#name');
        username = $('#username');
        email = $('#email');
        password = $('#password');
        repeat = $('#repeat');
        //empty name input
        if(name.val() === null || name.val() === '') {
            registerError.addClass('error').text(' Name should not be empty !');
            name.focus();
            return false;
        }
        //empty username input
        if (username.val() === null || username.val() === '') {
            registerError.addClass('error').text(' Username should not be empty !');
            username.focus();
            return false;
        }
        //set up min password length
        if(username.val().length < 5 ) {
            registerError.addClass('error').text(' Username should be not less than 5 characters !');
            username.focus();
            return false;
        }
        //wrong username input, e.g #1111
        if(!usernameRegex.test(username.val())) {
            registerError.addClass('error').text(' Username should only contain alphanumeric characters!');
            username.focus();
            return false;
        }
        //empty email input
        if(email.val() === null || email.val() === '') {
            registerError.addClass('error').text(' Email  should not be empty!');
            email.focus();
            return false;
        }
        //not complete email input, e.g r@g
        if(!emailRegex.test(email.val())) {
            registerError.addClass('error').text(' Email is invalid!');
            email.focus();
            return false;
        }
        //empty password input
        if(password.val() === null || password.val() === '') {
            registerError.addClass('error').text(' Password should not be empty!');
            password.focus();
            return false;
        }
        //set password length range
        if(password.val().length < 7 || password.val().length > 15 ) {
            registerError.addClass('error').text(' Password must be between 7 to 15 alphanumeric characters!');
            password.focus();
            return false;
        }
        //set password format
        if(!passwordRegex.test(password.val())) {
            registerError.addClass('error').text(' Password should contain at least one upper case letter and only contain alphanumeric characters!');
            password.focus();
            return false;
        }
        //empty confirmed password input
        if(repeat.val() === null || repeat.val() === '') {
            registerError.addClass('error').text(' Repeated Password should not be empty!');
            repeat.focus();
            return false;
        }
        //set confirmed password length range
        if(repeat.val().length < 7 || repeat.val().length > 15 ) {
            registerError.addClass('error').text(' Repeated Password must be between 7 to 15 alphanumeric characters!');
            repeat.focus();
            return false;
        }
        //set confirmed password format
        if(!passwordRegex.test(repeat.val())) {
            registerError.addClass('error').text(' Repeated Password should contain at least one upper case letter and only contain alphanumeric characters!');
            repeat.focus();
            return false;
        }
        //set password and confirmed password match
        if(password.val() !== repeat.val()) {
            registerError.addClass('error').text(' Password and Repeated Password are not same!');
            repeat.focus();
            return false;
        }

        event.preventDefault();

        var registerFormSerialize = $(this).serialize();
         // If there is a pending request
        if(ajaxRequest) {
            ajaxRequest.abort();
        }

        ajaxRequest = $.ajax({
            url:'/customer/registerValidating',
            type:'POST',
            data: registerFormSerialize
        });

        ajaxRequest.done(function(response) {
            const mResponse = JSON.parse(response);
            if(!mResponse['statusCode']) {
                registerError.addClass('error').text('* '+mResponse['exception']);
            } else {
                registerForm.off("submit");   // Have to turn off, otherwise the submit below will be caught by the handler again
                registerForm.submit();
            }
        });

        ajaxRequest.fail(function() {
            alert("request failed");
        });
    });

});