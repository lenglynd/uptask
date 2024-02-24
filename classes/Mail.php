<?php 
namespace Mail;
use PHPMailer\PHPMailer\PHPMailer;
class Mail{
    public $email;
    public $nombre;
    public $token;
    public function __construct($email,$nombre,$token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }
    
    public function enviarMail() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'f32ca5468e4690';
        $mail->Password = '64b4a7e1caae63';
        $mail->SMTPSecure = 'tls';
        
        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress($this->email,'AppSalon.com');
        $mail->Subject = 'Confirma tu Cuenta';
        
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $contenido ="<html>";
        $contenido .="<p>Hola <strong> ".$this->nombre."</strong> Has creado un cuenta en UpTask, sólo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .="<p>Presiona aqui <a href='http://localhost:3000/confirmar?token=".$this->token."'>Confirmar Cuenta</a></p>";
        $contenido .="<p>Si tu no solicitaste esta cuenta, puedes ignorar este mensaje</p>";
        $contenido .="</html>";
        
        $mail->Body = $contenido;
        //enviar el email
        
        $mail->send();
        
    }
    
    public function enviarInstrucciones() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
        $mail->SMTPSecure = 'tls';
        
        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress($this->email,'UpTask.com');
        $mail->Subject = 'Confirma tu Cuenta';
        
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $contenido ="<html>";
        $contenido .="<p>Hola <strong> ".$this->nombre."</strong> Has solicitado restablecer tu cuenta en UpTask, sólo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .="<p>Presiona aqui <a href='" . $_ENV['APP_URL'] . "/reestablecer?token=".$this->token."'>Restablecar Cuenta</a></p>";
        $contenido .="<p>Si tu no solicitaste restablecer esta cuenta, puedes ignorar este mensaje</p>";
        $contenido .="</html>";
        
        $mail->Body = $contenido;
        //enviar el email
        
        $mail->send();
        
    }
}
?>