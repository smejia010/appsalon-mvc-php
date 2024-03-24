<?php
namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    public $email;
    public $nombre;
    public $token;
    public function __construct($email, $nombre, $token)
    {
        $this-> email = $email;
        $this-> nombre = $nombre;
        $this-> token = $token;
    }

    public function enviarConfirmacion(){
        //crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'appsalon.com');
        $mail->Subject = 'confirma tu cuenta';

        //SET HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola ". $this->email ."</strong> Has creado tu cuenta en Appsalon, solor
        debes confirmarla presionando en el siguiente enlace</p>";
        $contenido .= "<p>Presiona aquí: <a href='".  $_ENV['APP_URL']  ."/confirmar-cuenta?token=" 
        .$this->token." ". "'>Confirmar cuenta </a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta puedes ignorar el mensaje<p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;

        //ENVIAR EL MAIL
        $mail->send();
    }

    public function enviarInstrucciones() {
        //crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'appsalon.com');
        $mail->Subject = 'Reestablece tu contraseña';

        //SET HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola ". $this->nombre ."</strong> Has solicitado reestablecer tu contraseña,
        presiona en el siguiente enlace para completar</p>";
        $contenido .= "<p>Presiona aquí: <a href='".  $_ENV['APP_URL']  ."/recuperar?token=" 
        .$this->token." ". "'>Reestablecer contraseña</a></p>";
        $contenido .= "<p>Si tu no solicitaste este cambio puedes ignorar el mensaje<p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;

        //ENVIAR EL MAIL
        $mail->send();
    }
}