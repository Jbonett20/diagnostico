<?php

require '../../vendor/autoload.php';
require_once __DIR__.'/../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."./../../");
$dotenv->load();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

 class Email
{
    public static function enviar($address, $asunto, $variables, $template, $archivo = [], $emailsCs = [])
    {

        try {
            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';
            $mail->IsSMTP();
            $mail->SMTPDebug  = $_ENV["SMTPDEBUG"];
            $mail->Host       = $_ENV["HOST"];
            $mail->Port       = $_ENV["PORT"];
            $mail->SMTPSecure = $_ENV["SMTPSECURE"];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV["USERNAME"];
            $mail->Password   = $_ENV["PASSWORD"];
            $mail->SetFrom($_ENV["SETFROM"], $_ENV["SETFROMNAME"]);
            $mail->AddAddress($address, 'El Destinatario');
            if (!empty($emailsCs)) {
                $ccList = implode(",", $emailsCs);
                $mail->addCC($ccList);
            }
            if (!empty($archivo)) {
                $mail->AddAttachment($archivo[0], $archivo[1]);
            }

            $html = file_get_contents($template);
            foreach ($variables as $item) {
                $html = str_replace($item["nombre"], $item["valor"], $html);
            }
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body    = $html;
            if (!$mail->Send()) {
                //echo "Error: " . $mail->ErrorInfo;
                throw new Exception($mail->ErrorInfo);
            } else {
                // echo "Enviado!";
                return "Enviado!";
            }
        } catch (Exception $e) {
            echo 'Error al enviar o recibir correos electrónicos: ' . $mail->ErrorInfo;
        }
    }
} 
