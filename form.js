$(function () {
    $('#contact_form').submit(function (e) {
        e.preventDefault();

        var name = $('#name').val();
        var email = $('#email').val();
        var phone = $('#phone').val();
        var message = $('#message').val();

        // Remove error classes on input focus
        $('#area-size, #name, #email, #phone, #message').click(function () {
            $(this).removeClass("error_input");
        });

        // Form field validation
        var error = false;

        if (name.length == 0) {
            error = true;
            $('#name').addClass("error_input");
        }
        if (email.length == 0 || email.indexOf('@') == -1) {
            error = true;
            $('#email').addClass("error_input");
        }
        if (phone.length == 0) {
            error = true;
            $('#phone').addClass("error_input");
        }
        if (message.length == 0) {
            error = true;
            $('#message').addClass("error_input");
        }

        if (!error) {
            $.ajax({
                type: "POST",
                url: 'handler-test.php',
                data: $(this).serialize(),
                success: function (data) {
                    //var responseData = JSON.parse(data);
                    console.log(data);
                    if (data.result === 'success') {
                        $('form#contact_form').hide();
                        $('#success_message').show();
                        $('#error_message').hide();
                    } else {
                        $('#error_message').append('<ul></ul>');
                        /*jQuery.each(responseData.errors,function(key,val) {
                            console.error(val)
                        });*/
                        $('form#contact_form').hide();
                        $('#success_message').hide();
                        $('#error_message').show();
                    }
                },
                error: function () {
                    console.error('Error en la solicitud AJAX');
                }
            });
        } else {
            console.error('Uno de los campos no se ha enviado de manera correcta');
        }
    });
});
