<?php

require 'PHPMailer/PHPMailerAutoload.php';

class clsMailHelper
{
    public static function EnviarMailRestauraClave($email, $nombre, $nueva)
    {
        $asunto = 'Restaurar clave de acceso';
        $mensaje = 'Estimando ' . $nombre . ' tu contraseña para acceder a Socios & Amigos TAT es: ' . $nueva . ' <br/><br/>  Disfruta de todos los beneficios que tenemos para ti';
        $mensaje = $mensaje . "<br/>";
        $mensaje = $mensaje . "<br/>";
        $mensaje = $mensaje . "<img src='https://sociosyamigos.com.co/images/socios_y_amigos.png' style='width: 150px;' alt='Socios & Amigos' />";
        $alt = 'This is the body in plain text for non-HTML mail clients';

        return clsMailHelper::EnviarMail($email, $nombre, $asunto, $mensaje, $alt, null, null);
    }

    public static function EnviarMailRedencion($email, $id_categoria)
    {
        if ($id_categoria == 1) {
            $asunto = 'Redención Bono Socios y Amigos';
            $mensaje = "";
            $mensaje = $mensaje . "<br/>";
            $mensaje = $mensaje . "<div class='center'>";
            $mensaje = $mensaje . "<img src='https://sociosyamigos.com.co/tat2022/images/img_correos/banner_exito1.png'/>";
            $mensaje = $mensaje . "<br/>";
            $mensaje = $mensaje . "</div>";
        } else if ($id_categoria == 9) {
            $asunto = 'Redención Bono Socios y Amigos';
            $mensaje = "";
            $mensaje = $mensaje . "<br/>";
            $mensaje = $mensaje . "<div class='center'>";
            $mensaje = $mensaje . "<img src='https://sociosyamigos.com.co/tat2022/images/img_correos/banner_recargas1.png'/>";
            $mensaje = $mensaje . "<br/>";
            $mensaje = $mensaje . "</div>";
        } else {
            $asunto = 'Redención Bono Socios y Amigos';
            $mensaje = "";
            $mensaje = $mensaje . "<br/>";
            $mensaje = $mensaje . "<div class='center'>";
            $mensaje = $mensaje . "<img src='https://sociosyamigos.com.co/tat2022/images/img_correos/banner_bonos1.png'/>";
            $mensaje = $mensaje . "<br/>";
            $mensaje = $mensaje . "</div>";
        }

        return clsMailHelper::EnviarMail($email, "Socios y Amigos", $asunto, $mensaje, "", "pmoreno@formasestrategicas.com.co", "Paola Moreno");
    }

    private static function EnviarMail($email, $nombre, $asunto, $mensaje, $alt, $cc, $ccNombre)
    {
        $mail = new PHPMailer;

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->CharSet = 'UTF-8';
        $mail->Host = 'servidor1.formasestrategicas.com.co';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'info@sociosyamigos.com.co';                 // SMTP username
        $mail->Password = 'Formas3strategicaS';                           // SMTP password
        $mail->SMTPSecure = true;                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;

        $mail->From = 'info@sociosyamigos.com.co';
        $mail->FromName = 'Info Socios & Amigos';
        $mail->addAddress($email, $nombre);     // Add a recipient

        if (isset($cc))
            $mail->AddCC($cc, $ccNombre);

        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $asunto;
        $mail->Body    = $mensaje;
        $mail->AltBody = $alt;

        if (!$mail->send()) {
            return 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return 'ok';
        }
    }
}
