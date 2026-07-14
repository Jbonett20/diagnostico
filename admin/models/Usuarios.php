<?php
require "../config/init_db.php";
// require "Email.php";
date_default_timezone_set('America/Bogota');
DB::$error_handler = false;
DB::$throw_exception_on_error = true;
@session_start();
class Usuario
{

    public static function All()
    {
        $data = [];
        try {
            $data["res"] = DB::query("SELECT u.*, r.nombre AS rol, e.nombres  AS empresa
                                            FROM dsc_usuarios AS  u
                                            INNER JOIN dsc_roles  AS r
                                            ON u.rolid = r.rolid
                                            INNER JOIN dsc_empresas e
                                            ON u.empresaid = e.empresaid");
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
        $data = [];
        try {
            extract($datos);
            $query = DB::query(" UPDATE
                                            dsc_usuarios
                                        SET
                                            nombres = '{$nombres}',
                                            apellidos = '{$apellidos}',
                                            email = '{$correo}',
                                            identificacion = '{$identificacion}',
                                            telefono = '{$telefono}',
                                            rolid = '{$rol}',
                                            empresaid = '{$empresaid}'
                                        WHERE usuarioid = '{$usuarioid}'");
            $data["error"] = false;
            $data["message"] = "";
            $data["getQuery"] = $query;
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

    public static function updateClave($datos): array
    {

        try {
            extract($datos);
            $contraM = password_hash($cc, PASSWORD_DEFAULT);
            $data = [];
            $data["data"] = DB::query("UPDATE
                                                dsc_usuarios
                                            SET
                                                password = '{$contraM}'
                                            WHERE
                                                usuarioid = $id");
            $data["error"] = false;
            $data["message"] = "Su contraseña se ha actualizado por su documento de identidad";
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
    public static function inactivar($datos): array
    {

        try {
            extract($datos);
            $estadoid = $estadoid == 1 ? 0 : 1;
            $data = [];
            $data["data"] = DB::query("UPDATE
                                                dsc_usuarios
                                            SET
                                              estadoid = '{$estadoid}'
                                            WHERE
                                                usuarioid = $id");
            $data["error"] = false;
            $data["message"] = "Operación exitosa";
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

    public static function findById($id)
    {
        $data = [];
        // $empresaid = $_SESSION["TYLFLYI"]["empresaid"];
        try {
            $data["res"] = DB::queryFirstRow("SELECT * FROM dsc_usuarios WHERE usuarioid = '{$id}'");
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
        try {
            $info = [];
            extract($datos);
            $data = [];
            $variable_email = [];
            // validamos si el email existe
            $usuario = DB::queryFirstRow("SELECT * FROM dsc_usuarios WHERE email = '{$correo}' OR  identificacion = '{$identificacion}'");
            if (!empty($usuario)) {
                throw new MeekroDBException("El usuario que intenta crear ya se encuentra registrado");
            }
            $contrasena = password_hash($identificacion, PASSWORD_DEFAULT);
            DB::query("INSERT INTO dsc_usuarios(
                                                    nombres,
                                                    apellidos,
                                                    email,
                                                    tipoidentificacionid,
                                                    identificacion,
                                                    password,
                                                    telefono,
                                                    estadoid,
                                                    fechacreacion,
                                                    rolid,
                                                    empresaid
                                                )
                                                VALUES(
                                                    '{$nombres}',
                                                    '{$apellidos}',
                                                    '{$correo}',
                                                    '1',
                                                    '{$identificacion}',
                                                    '{$contrasena}',
                                                    '{$telefono}',
                                                    '1',
                                                    NOW(),
                                                    '{$rol}',
                                                    '{$empresaid}'
                                                )");
            $idInsert = DB::insertId();
            $nombre_completo = ucfirst($nombres) . ' ' . ucfirst($apellidos);
            $variable_email[] = ['nombre' => '%nombre%', 'valor' => $nombre_completo];
            $variable_email[] = ['nombre' => '%email%', 'valor' => $correo];
            $variable_email[] = ['nombre' => '%clave%', 'valor' => $identificacion];
            // $info['usuarioid'] = $idInsert;
            // $info['tipo'] = "Creacion";
            // $info['tabla'] = "bro_terminos";
            // $info['descripcion'] = "Aceptación de términos de tipo de creación";
            // Trazabilidad::mdlCrear($info);
            // Email::enviar($correo, "Activar usuario ", $variable_email, "../template-email/Activar_cuenta.php", "", []);
            $data["error"] = false;
            $data["message"] = "Operación Exitosa";
            $data["getQuery"] = $idInsert;
            $data["registroid"] = $idInsert;
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
