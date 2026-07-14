<?php
require "../config/init_db.php";
date_default_timezone_set('America/Bogota');
DB::$error_handler = false;
DB::$throw_exception_on_error = true;
@session_start();
class Empresa
{

    public static function All()
    {
        $data = [];
        // $empresaid = $_SESSION["TYLFLYI"]["empresaid"];
        try {
            $data["res"] = DB::query("SELECT
                                        empresaid,
                                        nombre,
                                        fechacreacion,
                                        creadorid,
                                        direccion,
                                        email,
                                        estadoid,
                                        CASE WHEN estadoid = 1 THEN 'Activo' ELSE 'Inactivo'
                                    END estado,
                                    CASE WHEN estadoid = 1 THEN 'bg-success' ELSE 'bg-danger'
                                    END clase,
                                    CASE WHEN telefono IS NULL THEN '-' ELSE telefono
                                    END telefono
                                    FROM
                                        dsc_empresas");
            $data["error"] = false;
            $data["message"] = "";
            $data["getQuery"] = "";
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["res"] = [];
            $data["message"] = $e->getMessage();
            $data["getQuery"] = $e->getQuery();
        }
        DB::$error_handler = 'meekrodb_error_handler';
        DB::$throw_exception_on_error = false;
        DB::disconnect();
        return $data;
    }

    public static function AllSelect()
    {
        $data = [];
        // $empresaid = $_SESSION["TYLFLYI"]["empresaid"];
        try {
            $data["res"] = DB::query("SELECT
                                        empresaid,
                                        nombres
                                    FROM
                                        dsc_empresas where estadoid = 1");
            $data["error"] = false;
            $data["message"] = "";
            $data["getQuery"] = "";
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["res"] = [];
            $data["message"] = $e->getMessage();
            $data["getQuery"] = $e->getQuery();
        }
        DB::$error_handler = 'meekrodb_error_handler';
        DB::$throw_exception_on_error = false;
        DB::disconnect();
        return $data;
    }

    public static function edit($datos)
    {

        // return $datos;
        $data = [];
        try {
            extract($datos);
            // $empresaid = $_SESSION["TYLFLYI"]["empresaid"];
            $email_editar = trim($email_editar);
            $validarEmail = DB::query("SELECT *  FROM  dsc_empresas WHERE email = '$email_editar' AND empresaid != $empresaid");
            if (!empty($validarEmail)) {
                throw new MeekroDBException("El email ya esta registrado.");
            }
            $query = "UPDATE dsc_empresas 
                        SET 
                            nombre      = '$nombre_editar',
                            email       = '$email_editar',
                            telefono    = '$telefono_editar',
                            direccion   = '$direccion_editar',
                            estadoid   = '$estado'
                      WHERE empresaid   = $empresaid";
            DB::query($query);
            $data["error"] = false;
            $data["message"] = "";
            $data["getQuery"] = "";
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["message"] = $e->getMessage();
            $data["getQuery"] = $e->getQuery();
        }
        DB::$error_handler = 'meekrodb_error_handler';
        DB::$throw_exception_on_error = false;
        DB::disconnect();
        return $data;
    }

    public static function findById($registroid)
    {
        $data = [];
        // $empresaid = $_SESSION["TYLFLYI"]["empresaid"];
        try {
            $data["res"] = DB::queryFirstRow("SELECT * FROM dsc_empresas WHERE empresaid = $registroid");
            if (empty($data["res"])) {
                throw new MeekroDBException("Registro no encontrado.");
            }
            $data["error"] = false;
            $data["message"] = "";
            $data["getQuery"] = "";
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["res"] = [];
            $data["message"] = $e->getMessage();
            $data["getQuery"] = $e->getQuery();
        }
        DB::$error_handler = 'meekrodb_error_handler';
        DB::$throw_exception_on_error = false;
        DB::disconnect();
        return $data;
    }

    public static function save($datos)
    {
        $data = [];
        try {
            extract($datos);
            $usuarioid = $_SESSION["TYLFLYI"]["usuarioid"];
            // $empresaid = $_SESSION["TYLFLYI"]["empresaid"];
            $email = trim($email);
            $nombre = trim($nombre);
            $validarEmail = DB::query("SELECT *  FROM  dsc_empresas WHERE email = '$email' OR nombre = '$nombre'");
            if (!empty($validarEmail)) {
                throw new MeekroDBException("El email o nombre de la empresa ya esta registrado.");
            }
            DB::query("INSERT INTO dsc_empresas(nombre, email, telefono, direccion, creadorid) VALUES ('$nombre', '$email', '$telefono', '$direccion',  $usuarioid)");
            $data["error"] = false;
            $data["message"] = "";
            $data["getQuery"] = "";
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["message"] = $e->getMessage();
            $data["getQuery"] = $e->getQuery();
        }
        DB::$error_handler = 'meekrodb_error_handler';
        DB::$throw_exception_on_error = false;
        DB::disconnect();
        return $data;
    }
}
