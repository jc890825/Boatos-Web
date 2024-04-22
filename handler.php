<?php

/**
 * Las líneas [MOSTRAR ERRORES] se usarán para encontrar cualquier tipo de errores,
 * en el inicio del script, durante el script. (Mantener comentado en entorno de Producción).
 *
 * OJO: Ahora mismo hay en el proyecto, funcionalidades obsoletas por lo que si se descomentan, al lanzar errores,
 * detendrán el envío del formulario.
 *
 *  [MOSTRAR ERRORES] -> Ya estaban en el proyecto
 *  ini_set('display_errors', 1);
 *  ini_set('display_startup_errors', 1);
 *  error_reporting(E_ALL);
 *
 * */

/* Tested working with PHP5.4 and above (including PHP 7 ) */
require_once './vendor/autoload.php';

use FormGuide\Handlx\FormHandler;

$pp = new FormHandler();

/* Validaciones del formulario del lado del servidor */
$validator = $pp->getValidator();
$validator->fields(['Name','Email', 'phone'])->areRequired()->maxLength(50);
$validator->field('Email')->isEmail();
$validator->field('Message')->maxLength(6000);

/* Lanza un error cuando se descomenta (Revisar). */
//$pp->requireReCaptcha();
//$pp->getReCaptcha()->initSecretKey('6LczpsApAAAAAFr_j0ufoguNKBhP6ksgF3fadaQs');

/* Esta forma en la que se envía el mail no está funcionando, habría que revisar la clase FormHandler() (Revisar) */
//$pp->sendEmailTo('oscar.valdes@grupoboatos.com');

/* Transfiere el valor '$pp->process($_POST)' a la función ajax para mostrar el mensaje de éxito o del error. /*
//echo $pp->process($_POST);


/** Nueva implementación de envío de correo y transferencia de valor a la función ajax del mensaje de éxito o error. */

$subject = 'Nuevo contacto de la web'; // Subject of your email
$to = 'oscar.valdes@grupoboatos.com';  //Recipient's E-mail
$emailTo = $_REQUEST['Email'];

/* Se incluyen los campos de tipo de propiedad y tamanno total de área. */
$property_type = $_REQUEST['Property_Type'];
$area_size = $_REQUEST['Area_Size'];
$name = $_REQUEST['Name'];
$email = $_REQUEST['Email'];
$phone = $_REQUEST['phone'];
$msg = $_REQUEST['message'];

$email_from = $name.'<'.$email.'>';

$headers = "MIME-Version: 1.1";
$headers .= "Content-type: text/html; charset=iso-8859-1";
$headers .= "From: ".$name.'<'.$email.'>'."\r\n"; // Sender's E-mail
$headers .= "Return-Path:"."From:" . $email;

$message .= 'Nuevo mensaje desde Grupo Boatos' . "\n";
$message .= 'Tipo de propiedad : ' . $property_type . "\n";
$message .= 'Tamaño total del área: ' . $area_size . ' Metros (m2)' . "\n";
$message .= 'Nombre: ' . $name . "\n";
$message .= 'Email: ' . $email . "\n";
$message .= 'Teléfono: ' . $phone . "\n";
$message .= 'Mensaje: ' . $msg;

if (@mail($to, $subject, $message, $email_from))
{
    // Transfiere el valor 'sent' a la función ajax para mostrar el mensaje de éxito.
    $sent = $pp->process($_POST);
    echo $sent;
}
else
{
    // Transfiere el valor 'failed' a la función ajax para mostrar el mensaje de error.
    $failed = $pp->process($_POST);
    echo $failed;
}

/* Fin de la nueva implementación */
