<?php
require "../config/init_db.php";
// require "Email.php";
date_default_timezone_set('America/Bogota');
DB::$error_handler = false;
DB::$throw_exception_on_error = true;
session_start();


class Participantes
{

    public static function getAll($eventoid,$perfilid)
    {
        try {

            $data = [];
            DB::$encoding = 'utf8';
            $sql_query = "SELECT
                                due.usuarioid,
                                due.identificacion,
                                due.bonopuntos AS bono,
                                UPPER(due.nombres) AS nombres,
                                UPPER(due.apellidos) as apellidos,
                                CASE WHEN due.estadoid = 1 THEN 'ACTIVO' ELSE 'INACTIVO'
                                END AS estado,
                                CASE WHEN due.estadoid = 1 THEN 'badge-secondary' ELSE 'badge-danger'
                                END AS color,
                                UPPER(dp.nombre) AS perfil,
                                UPPER(de.nombres) empresa
                                FROM
                                    dsc_usuarios_eventos due
                                INNER JOIN dsc_perfiles_eventos dp ON
                                    dp.perfilid = due.perfilid
                                INNER JOIN dsc_empresas de ON
                                    de.empresaid = due.empresaid
                                INNER JOIN dsc_estados det ON
                                    det.estadoid = due.estadoid
                                    WHERE due.eventoid = $eventoid AND due.perfilid = $perfilid;";
            $res = DB::query($sql_query);
            $evento = DB::queryFirstRow("SELECT
                                                dpe.nombre AS perfil,
                                                de.nombre AS evento
                                            FROM
                                                dsc_perfiles_eventos dpe
                                            INNER JOIN dsc_eventos de ON
                                                de.eventoid = dpe.eventoid
                                            WHERE
                                                dpe.perfilid = $perfilid AND dpe.eventoid = $eventoid;");    


            $data["error"] = false;
            $data["message"] = "Todos los usuarios Ya están Registrados";
            $data["data"] = $res;
            $data["evento"] = $evento;
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
    public static function findById($id)
    {
        try {
            $data = [];
            DB::$encoding = 'utf8';
            $sql_query = "SELECT
                                    due.usuarioid,
                                    due.identificacion,
                                    due.bonopuntos AS bono,
                                    UPPER(due.nombres) AS nombres,
                                    UPPER(due.apellidos) AS apellidos,
                                    due.estadoid,
                                UPPER(dp.nombre) AS perfil,
                                UPPER(de.nombres) empresa
                                FROM
                                    dsc_usuarios_eventos due
                                INNER JOIN dsc_perfiles_eventos dp ON
                                    dp.perfilid = due.perfilid
                                INNER JOIN dsc_empresas de ON
                                    de.empresaid = due.empresaid
                                INNER JOIN dsc_estados det ON
                                    det.estadoid = due.estadoid
                                    where due.usuarioid = $id;";
            $res = DB::queryFirstRow($sql_query);


            $data["error"] = false;
            $data["message"] = "Todos los usuarios Ya están Registrados";
            $data["data"] = $res;
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

    public static function add($p, $eventoid, $perfilid)
    {
        try {

            /*  print_r($p);
            print_r($eventoid);
            print_r($perfilid);
            die(); */
            DB::$encoding = 'utf8';
            $data = [];
            $usuarioid = $_SESSION['DIAGNOSTICOSALESCONTESTAUTOTRAIN']['usuarioid'];
            $contrasena = password_hash($p['identificacion'], PASSWORD_DEFAULT);

            /* consulta si existe un perfil Asociado a Un evento */
            $existPerfil = DB::queryFirstRow("SELECT * FROM dsc_perfiles_eventos WHERE perfilid = '{$perfilid}' AND eventoid = '{$eventoid}'");
            if (empty($existPerfil)) {
                throw new MeekroDBException("el Evento no cuenta con un perfil Configurado");
            }
            /* consultar si la dsc_categorias_eventos tiene tiene un perifil con estado id = 1 que no agrege usuarios */
            $categoiaEtadoPerfil = DB::queryFirstRow("SELECT * FROM dsc_categorias_eventos WHERE perfilid = '{$perfilid}' AND eventoid = '{$eventoid}' AND estadoid = 1");
            if (!empty($categoiaEtadoPerfil)) {
                throw new MeekroDBException("No se pueden cargar participantes por que el evento esta en proceso");
            }

            /*  consultamos si existe el usuario X identificacion  */
            $existUser = DB::queryFirstRow("SELECT * FROM dsc_usuarios_eventos WHERE identificacion = '{$p['identificacion']}'");


            if (empty($existUser)) {
                // TODO: implement
                /* consulto si existe la empresa y si no se procede a crear */
                $empresaid =  Participantes::addEmpresa($p['empresa'], $usuarioid);
                /* consulto la cantidad de usuarios el ulimo id */
                /*  $totalUsuarios = DB::queryFirstRow("SELECT COUNT(usuarioid) as cantidad FROM dsc_usuarios_eventos;");
                $ultimoidusuario = $totalUsuarios['cantidad'] + 1; */
                $sql = "INSERT INTO dsc_usuarios_eventos(
                                                                eventoid,
                                                                empresaid,
                                                                creadorid,
                                                                editorid,
                                                                estadoid,
                                                                tipoidentificacionid,
                                                                perfilid,
                                                                nombres,
                                                                apellidos,
                                                                bonopuntos,
                                                                identificacion,
                                                                password
                                                    )
                                                    VALUES(
                                                                $eventoid,
                                                                 '{$empresaid['empresaid']}',
                                                                '{$usuarioid}',
                                                                '{$usuarioid}',
                                                                1,
                                                                1,
                                                                 $perfilid,
                                                                '{$p['nombres']}',
                                                                '{$p['apellidos']}',
                                                                '{$p['bono']}',
                                                                '{$p['identificacion']}',
                                                                '{$contrasena}'
                                                                                )";

                /*  print_r($sql);
                                                                                die(); */
                $insert =   DB::query($sql);
                if ($insert) {
                    $data["error"] = false;
                    $data["message"] = "Usuarios Cargados con Éxitos";
                    $data["data"] = $insert;
                } else {
                    $data["error"] = true;
                    $data["message"] = "Algo salio Mal";
                    $data["data"] = "";
                }
            }
            $data["error"] = false;
            $data["message"] = "Todos los usuarios Ya están Registrados";
            $data["data"] = "";
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


    public static function update($datos)
    {
        try {
            $data = [];
            extract($datos);
            $sql_query = "UPDATE
                                dsc_usuarios_eventos
                            SET
                                bonopuntos = '{$txt_bono}',
                                nombres = '{$txt_nombres}',
                                apellidos = '{$txt_apellidos}'
                            
                            WHERE usuarioid = $txt_id";
            $res = DB::query($sql_query);
            $data["error"] = false;
            $data["message"] = "Operación Exitosa";
            $data["data"] = $res;
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["message"] = $e->getMessage();
            $data["data"] = $e->getQuery();
        }

        return $data;
    }

    /* creacion de la empresa */
    public static function addEmpresa($nombre, $creadorid)
    {
        try {
            $data = [];
            $nombreAdd = mb_strtolower($nombre);
            $exisEmpresa = DB::queryFirstRow("SELECT * FROM dsc_empresas WHERE LOWER(nombres) = LOWER(%s)", $nombre);
            if (empty($exisEmpresa)) {
                $sql = "INSERT INTO dsc_empresas(
                                                      nombres,
                                                      creadorid,
                                                      editorid,
                                                      estadoid
                                                  )
                                                  VALUES(
                                                      '{$nombreAdd}',
                                                      '{$creadorid}',
                                                      '{$creadorid}',
                                                      1  
                                                  )";

                $idEmpresa = DB::query($sql);
                if ($idEmpresa) {
                    $data["empresaid"] =   DB::insertId();
                }
            } else {
                $data["empresaid"] =  $exisEmpresa['empresaid'];
            }
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["message"] = $e->getMessage();
            $data["data"] = $e->getQuery();
        }

        return $data;
    }

    public static function inactivar($id)
    {
        try {
            $data = [];
            DB::$encoding = 'utf8';
            $estado =  DB::queryFirstRow("SELECT estadoid FROM dsc_usuarios_eventos WHERE usuarioid = $id");
            /*  print_r($estado);
           die(); */
            if ($estado['estadoid'] == "1") {
                $sql_query = DB::query("UPDATE dsc_usuarios_eventos SET estadoid = 2 WHERE usuarioid = $id");
            } else {
                $sql_query = DB::query("UPDATE dsc_usuarios_eventos SET estadoid = 1 WHERE usuarioid = $id");
            }
            $data["error"] = false;
            $data["data"] = $sql_query;
            $data["icon"] = "success";
            $data["message"] = "Operacion Exitosa";
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["message"] = $e->getMessage();
            $data["getQuery"] = $e->getQuery();
            $data["data"] = [];
            $data["icon"] = "error";
        }
        return $data;
    }
}
