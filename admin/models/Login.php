<?php
require "../config/init_db.php";
// require "Email.php";
date_default_timezone_set('America/Bogota');
DB::$error_handler = false;
DB::$throw_exception_on_error = true;

use ParagonIE\ConstantTime\Base32;
use OTPHP\TOTP;

class Login
{

    public static function ingresar($datos)
    {
        $data = [];
        extract($datos);
        try {
            // $data["otp"] = true;
            $data["user"] = DB::queryFirstRow("SELECT * FROM dsc_usuarios WHERE email = '$email' ");
            if (empty($data["user"])) {
                throw new MeekroDBException("Usuario o contraseña incorrecta");
            }
            if (!password_verify($password, $data["user"]["password"])) {
                throw new MeekroDBException("Usuario o contraseña incorrecta");
            }

           /*  if (isset($otp) && empty($otp)) {
                $secreto = self::generarSecreto();
                $otpGenerado = self::generarOTP($secreto);
                DB::query("INSERT INTO tyl_otp(codigo, usado, usuarioid) VALUES ('$otpGenerado', 0, '{$data["user"]["usuarioid"]}')");
                // enviar email
                $variable_email[] = ['nombre' => '%nombre%', 'valor' => $data["user"]["nombres"] . " " . $data["user"]["apellidos"]];
                $variable_email[] = ['nombre' => '%otp%', 'valor' => $otpGenerado];
                Email::enviar($data["user"]["correo"], "AUTENTICACIÓN ", $variable_email, "../template-email/otp.php", "", []);
                $data["otp"] = true;
            } */

            /* if (isset($otp) && !empty($otp)) {
                $consultaOtp = DB::queryFirstRow("SELECT * FROM tyl_otp WHERE codigo = '$otp' AND usado = 0 AND usuarioid = '{$data["user"]["usuarioid"]}'");
                if (empty($consultaOtp)) {
                    throw new MeekroDBException("Código OTP invalido");
                }
                DB::query("UPDATE tyl_otp SET usado = 1 WHERE usuarioid = '{$data["user"]["usuarioid"]}' AND codigo= '$otp' AND usado = 0");
                $data["otp"] = false;
                //Generar sesión
                @session_start();
            } */
            @session_start();
            $_SESSION["DIAGNOSTICOSALESCONTESTAUTOTRAIN"] = $data["user"];
            $data["error"] = false;
            $data["message"] = "";
            $data["getQuery"] = "";
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            // $data["otp"] = true;
            $data["user"] = [];
            $data["message"] = $e->getMessage();
            $data["getQuery"] = $e->getQuery();
        }
        DB::$error_handler = 'meekrodb_error_handler';
        DB::$throw_exception_on_error = false;
        DB::disconnect();
        return $data;
    }
    public static function loginParticipante($datos)
    {
        $data = [];
        extract($datos);
        
        try {
            // $data["otp"] = true;
            $data["user"] = DB::queryFirstRow("SELECT * FROM dsc_usuarios_eventos WHERE identificacion = '$txt_usuario'");
            if (empty($data["user"])) {
                throw new MeekroDBException("Usuario o contraseña incorrecta");
            }
            if (!password_verify($txt_clave, $data["user"]["password"])) {
                throw new MeekroDBException("Usuario o contraseña incorrecta");
            }

            

            @session_start();
            $_SESSION["DIAGNOSTICOSALESCONTESTAUTOTRAINALUMNO"] = $data["user"];
            $data["error"] = false;
            $data["message"] = "";
            $data["getQuery"] = "";
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            // $data["otp"] = true;
            $data["user"] = [];
            $data["message"] = $e->getMessage();
            $data["getQuery"] = $e->getQuery();
        }
        DB::$error_handler = 'meekrodb_error_handler';
        DB::$throw_exception_on_error = false;
        DB::disconnect();
        return $data;
    }










    public static function generarSecreto()
    {
        return Base32::encodeUpper(random_bytes(16));
    }

    public static function generarOTP($secreto)
    {
        $otp = TOTP::create($secreto);
        return $otp->now();
    }
}
