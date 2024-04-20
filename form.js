
$(function()
{
    function after_form_submitted(data) 
    {
        // Convierte a json la data recibida desde handler.php
        var responseData = JSON.parse(data);
        
        if(responseData.result === 'success')
        {
            $('form#contact_form').hide();
            $('#success_message').show();
            $('#error_message').hide();
        }
        else
        {
            $('#error_message').append('<ul></ul>');

            jQuery.each(responseData.errors,function(key,val)
            {
                $('#error_message ul').append('<li>'+key+': '+val+'</li>');
            });
            $('#success_message').hide();
            $('#error_message').show();

            //reverse the response on the button
            $('input[type="button"]', $form).each(function()
            {
                $btn = $(this);
                label = $btn.prop('orig_label');
                if(label)
                {
                    $btn.prop('type','submit' ); 
                    $btn.text(label);
                    $btn.prop('orig_label','');
                }
            });
            
        }//else
    }

	$('#contact_form').submit(function(e)
    {
        e.preventDefault();

        $form = $(this);
        // show some response on the button (Solo aquí había que cambiar button[type="submit"]' por input[type="submit"]')
        $('input[type="submit"]', $form).each(function()
        {
            $btn = $(this);
            $btn.prop('type','button' ); 
            $btn.prop('orig_label',$btn.text());
            $btn.text('Sending ...');
        });
        

        $.ajax({
            type: "POST",
            url: 'handler.php',
            data: $form.serialize(),
            // dataType: 'json', // Con esta línea no se estaba pasando los datos del formulario por ajax, ya que esperaba un json como respuesta.
            success: after_form_submitted,
        });  
              
        
    });	
});
