<?php

//require 'PHPMailer/PHPMailerAutoload.php';

class clsMailHelper
{   
    public static function EnviarMailRestauraClave($email, $nombre, $nueva)
    {
        $asunto = 'Restaurar clave de acceso';
        $mensaje = 'Estimando '.$nombre.' tu contraseña para acceder a Socios & Amigos TAT es: '.$nueva.' <br/><br/>  Disfruta de todos los beneficios que tenemos para ti';
        $mensaje = $mensaje."<br/>";
        $mensaje = $mensaje."<br/>";
        $mensaje = $mensaje."<img src='https://sociosyamigos.com.co/images/socios_y_amigos.png' style='width: 150px;' alt='Socios & Amigos' />";
        $alt = 'This is the body in plain text for non-HTML mail clients';

        return clsMailHelper::EnviarMail($email, $nombre, $asunto, $mensaje, $alt, null, null);
    }
    
    public static function EnviarMailRedencion($email, $nombre, $redimidos)
    {
        $asunto = 'Redención Alkanzando Metas';
        
        $mensaje = 'Felicitaciones '.$nombre;
        $mensaje = $mensaje."<p>El siguiente número de seguimiento servirá para que realices el seguimiento en el caso que lo requieras</p>";
        $mensaje = $mensaje."<br/>";
        
        $direccion = $redimidos[0]["direccion_envio"];
        $fecha_estimada = $redimidos[0]["fecha_provista_entrega"];
        $mensaje = $mensaje."<table>";
            $mensaje = $mensaje."<tr>";
                $mensaje = $mensaje."<th>Premio</th>";
                $mensaje = $mensaje."<th>Folio</th>";
            $mensaje = $mensaje."</tr>";
        foreach ($redimidos as $premio) {
            $mensaje = $mensaje."<tr>";
                $mensaje = $mensaje."<td>".$premio["premio"]."</td>";
                $mensaje = $mensaje."<td>".$premio["folio"]."</td>";
            $mensaje = $mensaje."</tr>";
        }
        $mensaje = $mensaje."</table>";
        
        $mensaje = $mensaje."<br/>";
        $mensaje = $mensaje."<p>Para mayor información comunicate a la línea gratuita nacional 018000 423748.</p>";
        $mensaje = $mensaje."<p>Recuerda que la fecha estimada para que tu premio llege al domicilio ".$direccion." son <strong> 15 días habiles</strong>.</p>";
        $mensaje = $mensaje."<br/>";
        $mensaje = $mensaje."<br/>";
        $mensaje = $mensaje."<img src='http://alkanzandometas.com.co/images/logo.png' width='150px' alt='Alkanzando Metas' />";

        return clsMailHelper::EnviarMail($email, $nombre, $asunto, $mensaje, "", "amorales@formasestrategicas.com.co", "David Avila");
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
        
        if(isset($cc))
            $mail->AddCC($cc, $ccNombre);

        $mail->isHTML(true);                                  // Set email format to HTML
        
        $mail->Subject = $asunto;
        $mail->Body    = $mensaje;
        $mail->AltBody = $alt;
        
        if(!$mail->send()) {
            return 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return 'ok';
        }
    }
}

?>