<?php

require_once './vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Validación de los campos del formulario
$requiredFields = ['Name', 'Email', 'phone'];
$maxLength = 50;
$emailField = 'Email';
$messageField = 'Message';
$messageMaxLength = 6000;

// Obteniendo los datos del formulario
$propertyType = $_REQUEST['Property_Type'] ?? '';
$name = $_REQUEST['Name'] ?? '';
$email = $_REQUEST['Email'] ?? '';
$phone = $_REQUEST['phone'] ?? '';
$msg = $_REQUEST['message'] ?? '';

// Verificando si todos los campos requeridos están presentes y no están vacíos
foreach ($requiredFields as $field) {
    if (empty($_REQUEST[$field])) {
        // Si algún campo requerido está vacío, muestra un mensaje de error y detén el proceso
        echo "Error: El campo '$field' es requerido.";
        exit;
    }
}

// Validando la longitud de los campos
foreach ($_REQUEST as $key => $value) {
    if (strlen($value) > $maxLength) {
        echo "Error: El campo '$key' excede la longitud máxima permitida de $maxLength caracteres.";
        exit;
    }
}

// Validando el campo de correo electrónico
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Error: El formato del correo electrónico no es válido.";
    exit;
}

// Validando la longitud del mensaje
if (strlen($msg) > $messageMaxLength) {
    echo "Error: El mensaje excede la longitud máxima permitida de $messageMaxLength caracteres.";
    exit;
}

$subject = 'Nuevo contacto de la web'; // Asunto del correo
$to = 'soporte@wpcache.es';  // Correo del destinatario

$emailFrom = "$name <$email>";

$message = 'Nuevo mensaje desde Grupo Boatos' . "<br>";
$message .= 'Tipo de propiedad : ' . $propertyType . "<br>";
$message .= 'Nombre: ' . $name . "<br>";
$message .= 'Email: ' . $email . "<br>";
$message .= 'Teléfono: ' . $phone . "<br>";
$message .= 'Mensaje: ' . $msg;

$mail = new PHPMailer(true);

try {
    // Configurar el servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.ionos.es';  // Cambia esto por tu servidor SMTP
    $mail->SMTPAuth = true;
    $mail->Username = 'soporte@wpcache.es'; // Cambia esto por tu nombre de usuario SMTP
    $mail->Password = 'Lala.1993'; // Cambia esto por tu contraseña SMTP
    $mail->SMTPSecure = 'tls'; // O 'ssl' si es necesario
    $mail->Port = 587; // Puerto SMTP, puede variar

    // Configurar el remitente y el destinatario
    $mail->setFrom($mail->Username, 'Grupo Boatos');
    $mail->addAddress($to);

    // Configurar el contenido del correo
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;

    // Enviar el correo
    $mail->send();

    echo "success";
} catch (Exception $e) {
    echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
}