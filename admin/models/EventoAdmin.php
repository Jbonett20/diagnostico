<?php
require "../config/init_db.php";
// require "Email.php";
date_default_timezone_set('America/Bogota');
DB::$error_handler = false;
DB::$throw_exception_on_error = true;
session_start();


class EventoAdmin
{

    public static function getAll()
    {
        try {
            $data = [];
            $query_sql = "SELECT
                                de.eventoid,
                                de.fechainicio,
                                de.fechafin,
                                de.img,
                                de.nombre AS evento,
                                de.estadoid,
                                CONCAT(
                                    UPPER(du.nombres),
                                    ' ',
                                    UPPER(du.apellidos)
                                ) AS creador
                            FROM
                                dsc_eventos de
                            INNER JOIN dsc_usuarios du ON
                                du.usuarioid = de.creadorid
                            WHERE
                                de.estadoid = 1;";

            $response = DB::query($query_sql);

            $data["error"] = false;
            $data["message"] = "cargando Datos";
            $data["data"] = $response;
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["message"] = $e->getMessage();
            $data["data"] = $e->getQuery();
        }
        DB::$error_handler = 'meekrodb_error_handler';
        DB::$throw_exception_on_error = false;
        DB::disconnect();
        return $data;
    }

    public static function getEventoId($evento_id)
    {
        try {

            $data = [];
            $query_sql_perfil = " SELECT
                                    de.eventoid,
                                    de.nombre as evento,
                                    dpe.nombre AS perfil,
                                    dpe.perfilid as perfilp,
                                    dce.nombre AS categoria,
                                    du.*
                                FROM
                                    dsc_eventos de
                                INNER JOIN dsc_perfiles_eventos dpe ON
                                    dpe.eventoid = de.eventoid
                                INNER JOIN dsc_usuarios_eventos du ON
                                     du.perfilid = dpe.perfilid
                                INNER JOIN dsc_categorias_eventos dce ON
                                    dce.perfilid = dpe.perfilid
                                WHERE
                                    de.eventoid = $evento_id AND de.estadoid = 1";
            $response = DB::query($query_sql_perfil);

            /* print_r($response);
                       die(); */
            $perfiles = [];

            // Agrupar las categorías bajo cada perfil utilizando foreach
            foreach ($response as $row) {
                $perfil = $row['perfil'];
                $usuario = [
                    "usuarioid" => $row['usuarioid'],
                    "nombres" => $row['nombres'] . ' ' . $row['apellidos'],
                    // Agrega más campos de usuario si es necesario
                ];
                if (!isset($perfiles[$perfil])) {
                    $perfiles[$perfil] = [
                        "categorias" => [],
                        "usuarios" => []
                    ];
                }
                $perfiles[$perfil]["categorias"][] = $row['categoria'];
                $perfiles[$perfil]["usuarios"][] = $usuario;
            }

            $data["error"] = false;
            $data["message"] = "cargando Datos";
            $data["data"]['perfil'] = $perfiles;
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["message"] = $e->getMessage();
            $data["data"] = $e->getQuery();
        }
        DB::$error_handler = 'meekrodb_error_handler';
        DB::$throw_exception_on_error = false;
        DB::disconnect();
        return $data;
    }
}
