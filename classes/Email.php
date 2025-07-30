<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {

        // Enviar email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];

        $mail->setFrom($_ENV['EMAIL_USER'], 'UpTask');
        $mail->addAddress($this->email, $this->nombre);
        $mail->Subject = 'Confirma tu cuenta';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong> Hola " . $this->nombre  . " </strong> Has Creado tu cuenta en UpTask, solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .= "<p> presiona aqui: <a href='" . $_ENV['APP_URL'] . "/confirmar?token=" . $this->token . "'>Confirmar Cuenta</a> </p>";
        $contenido .= "Si tu no solicitaste esta cuenta, puedes ignorar el mensaje";
        $contenido .= "</html>";
        
        $mail->Body = $contenido;

        // Enviar el mail
        $mail->send();
    }

    public function enviarInstrucciones() {
        // Enviar email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom($_ENV['EMAIL_USER'], 'UpTask');
        $mail->addAddress($this->email, $this->nombre);
        $mail->Subject = 'Reestablece tu password';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong> Hola " . $this->nombre  . " </strong> Has solicitado reestablecer tu password, sigue el siguiente enlace para hacerlo. </p>";
        $contenido .= "<p> presiona aqui: <a href='" . $_ENV['APP_URL'] . "/reestablecer?token=" . $this->token . "'>Reestablece tu password</a> </p>";
        $contenido .= "Si tu no solicitaste esta cuenta, puedes ignorar el mensaje";
        $contenido .= "</html>";
        
        $mail->Body = $contenido;

        // Enviar el mail
        $mail->send();
    }
}