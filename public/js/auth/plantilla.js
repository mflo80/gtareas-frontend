$(document).ready(() => {
    $('#nombre').keypress(function() {
        $('#error').hide();
        document.getElementById("nombre").focus();
    });
    $('#apellido').keypress(function() {
        $('#error').hide();
    });
    $('#email').keypress(function() {
        $('#error').hide();
        document.getElementById("email").focus();
    });
    $('#password').keypress(function() {
        $('#error').hide();
    });
    $('#password_confirmation').keypress(function() {
        $('#error').hide();
    });
});
