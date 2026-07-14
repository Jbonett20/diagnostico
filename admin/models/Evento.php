<?php
date_default_timezone_set('America/Bogota');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 


class Evento
{
    public static function Listar()
    {
        include_once("config/init_db.php");
        $data = [];
       
        try {
           @session_start();
           $creadorid = $_SESSION['DIAGNOSTICOSALESCONTESTAUTOTRAIN']['usuarioid'];
           $rolid = $_SESSION['DIAGNOSTICOSALESCONTESTAUTOTRAIN']['rolid'];
           $in = $rolid == 1 ? '1,2,3' : 1;
           $resultado= DB::query("SELECT * FROM dsc_eventos WHERE estadoid IN($in)");
        if($resultado){
           
            $data = $resultado;
        }
          
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
    public static function Crear($p, $imagen)
    { 
        include_once("../config/init_db.php");
        DB::$error_handler = false;
        DB::$throw_exception_on_error = true;
        $data = [];
        try {
            @session_start();
            $creadorid = $_SESSION['DIAGNOSTICOSALESCONTESTAUTOTRAIN']['usuarioid'];
            $nombre = strtoupper($p['nombre']);
            $color = $p['color'];
            $fechainicio = $p['fechainicio'];
            $fechafin = $p['fechafin'];
            $eventoData = array(
                'creadorid' => $creadorid,
                'estadoid' => 3,
                'nombre' => $nombre,
                'img' => $imagen,
                'color' => $color,
                'fechainicio' => $fechainicio,
                'fechafin' => $fechafin
            );
            $eventExist = DB::queryFirstRow('SELECT * FROM dsc_eventos WHERE LOWER(nombre) = LOWER(%s)', $nombre);
            if (!empty($eventExist)) {
                throw new MeekroDBException("El nombre del evento ya existe");
            } 
            $resultado = DB::insert('dsc_eventos', $eventoData);
            if(!$resultado){
                throw new MeekroDBException("Algo salio mal");
            }
            $lastInsertId = DB::insertId();
            $eventos = DB::query('SELECT * FROM dsc_eventos WHERE estadoid = 3');
            $data["error"] = false;
            $data["datos"] = $eventos; 
            $data["id"] = $lastInsertId; 
    
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
    public static function consultareventosperfiles($eventoid)
    {
        include_once("../config/init_db.php");
        $data = [];
        
        try {
            @session_start();
            DB::$encoding = 'utf8';
            $resultado = DB::query("SELECT DISTINCT e.*, COUNT(p.perfilid) AS perfiles 
                                    FROM dsc_eventos e 
                                    LEFT JOIN dsc_perfiles_eventos p ON e.eventoid = p.eventoid
                                    WHERE e.estadoid IN(1,2,3) AND e.eventoid = %i;", $eventoid);

            if($resultado){
                $data["error"] = false;
                $data["success"] = true;
                $data["datos"] = $resultado;
            }
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["user"] = [];
            $data["message"] = $e->getMessage();
            $data["getQuery"] = $e->getQuery();
        }
    
        DB::$error_handler = 'meekrodb_error_handler';
        DB::$throw_exception_on_error = false;
        DB::disconnect();
        
        return $data;
    }
    
    
    public static function Eliminar($eventoid, $estadoid)
    {
        include_once("../config/init_db.php");
        $data = [];
        try {
            @session_start();
            $creadorid = $_SESSION['DIAGNOSTICOSALESCONTESTAUTOTRAIN']['usuarioid'];

            if ($estadoid == 3) {
                DB::startTransaction();
                DB::query("UPDATE  dsc_eventos SET estadoid = 0 WHERE eventoid = %i", $eventoid);
                DB::commit();

                $data["error"] = false;
                $data["success"] = true;
                $data["message"] = "Operacion exitosa";
            }
        } catch (MeekroDBException $e) {
            if ($estadoid == 3) {
                DB::rollback();
            }

            $data["error"] = true;
            $data["message"] = $e->getMessage();
            $data["getQuery"] = $e->getQuery();
        }

        DB::disconnect();
        return $data;
    }

    public static function activarEvento($eventoid)
    {
        include_once("../config/init_db.php");
        DB::$error_handler = false;
        DB::$throw_exception_on_error = true;
        $data = [];
        try {
            @session_start();
            $rolid = $_SESSION['DIAGNOSTICOSALESCONTESTAUTOTRAIN']['rolid'];
            if($rolid != 1) throw new MeekroDBException("Rol no autorizado para esta acción.");

            $estadoEventos = DB::query("SELECT * FROM dsc_eventos WHERE estadoid = 0 AND eventoid = '{$eventoid}'");
            if($estadoEventos) throw new MeekroDBException("Por favor verifica el estado del evento.");

            $perfil = DB::query("SELECT * FROM dsc_perfiles_eventos WHERE eventoid = '{$eventoid}'");
            if(!$perfil) throw new MeekroDBException("El evento no se puede activar, no cuenta con perfil creado.");

            $etapas = DB::query("SELECT * FROM dsc_categorias_eventos WHERE eventoid = '{$eventoid}'");
            if(!$etapas) throw new MeekroDBException("El evento no se puede activar, no cuenta con etapa creadas.");

            $usuario = DB::query("SELECT * FROM dsc_usuarios_eventos WHERE eventoid = '{$eventoid}'");
            if(!$usuario) throw new MeekroDBException("El evento no se puede activar, no cuenta con participante.");

            DB::query("UPDATE dsc_eventos SET estadoid = '1' ,fechaedicion = NOW() WHERE eventoid = '{$eventoid}'");
            $etapasEventos = DB::query("SELECT pv.perfilid,
                                               pv.eventoid,
                                               pv.nombre,
                                               pv.bono,
                                               GROUP_CONCAT(CONCAT(cv.nombre, ':', cv.porcentaje , ':', cv.calificacion , ':',cv.categoriaid , ':', cv.estadoid ) SEPARATOR ',') AS etapas 
                                            FROM dsc_perfiles_eventos pv
                                            INNER JOIN dsc_categorias_eventos cv
                                            ON pv.perfilid = cv.perfilid
                                            WHERE pv.eventoid = '{$eventoid}' GROUP by pv.perfilid");

            $data["error"] = false;
            $data["datos"] = $etapasEventos;
            $data["message"] = "Operación exitosa";
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["message"] = $e->getMessage();
            $data["datos"] = $e->getQuery();
        }

        DB::disconnect();
        return $data;
    }

    public static function activarEtapaEventos($p)
    {
        include_once("../config/init_db.php");
        DB::$error_handler = false;
        DB::$throw_exception_on_error = true;
        $data = [];
        try {
            @session_start();
            $creadorid = $_SESSION['DIAGNOSTICOSALESCONTESTAUTOTRAIN']['usuarioid'];
            extract($p);
            
            $actividadesEtapa = DB::query("SELECT ca.*,p.* FROM
                                            dsc_categorias_actividades ca
                                            INNER JOIN dsc_preguntas p
                                            ON ca.actividadid = p.actividadid 
                                            WHERE ca.categoriaid = '{$etapaid}'");
            if(!$actividadesEtapa) throw new MeekroDBException("La etapa no se puede activar no cuenta con actividades.");
            
            $usuarios = DB::query("SELECT * FROM dsc_usuarios_eventos WHERE eventoid = '{$eventoid}' AND perfilid = '{$perfilid}'");
            if(!$usuarios) throw new MeekroDBException("La etapa no se puede activar, no cuenta con participante en el perfil.");

            $etapaEjecucion = DB::query("SELECT * FROM dsc_preguntas_usuarios_evento WHERE categoriaid ='{$etapaid}' AND valor > 1");
            if($etapaEjecucion) throw new MeekroDBException("La etapa se encuentra en ejecución por favor comunícate con el administrador.");
            
            $etapa = DB::queryFirstRow("SELECT estadoid FROM dsc_categorias_eventos WHERE eventoid = '{$eventoid}' AND categoriaid = '{$etapaid}'");
            if($etapa['estadoid'] == 0) {
                //activar la etapa y hacer un inser en la tabla dsc_preguntas_usuarios_evento.
                DB::query("UPDATE dsc_categorias_eventos SET estadoid = '1' WHERE categoriaid = '{$etapaid}'");
                // print_r($CATEGORIA);
                foreach ($usuarios as $key => $value) {
                    $usuarioid = $value['usuarioid'];
                    foreach ($actividadesEtapa as $key2 => $value2) {
                      $preguntaid = $value2['preguntaid'];
                    //   print_r($_SESSION['DIAGNOSTICOSALESCONTESTAUTOTRAIN']['usuarioid']);
                      DB::query("INSERT INTO dsc_preguntas_usuarios_evento(
                                                                            usuarioid,
                                                                            preguntaid,
                                                                            eventoid,
                                                                            categoriaid,
                                                                            fechacreacion,
                                                                            creadorid,
                                                                            valor
                                                                        )
                                                                        VALUES(
                                                                            '{$usuarioid}',
                                                                            '{$preguntaid}',
                                                                            '{$eventoid}',
                                                                            '{$etapaid}',
                                                                            NOW(),
                                                                            '{$creadorid}',
                                                                            0
                                                                        )");
                    }
                }
            } else if($etapa['estadoid'] == 1) {
                //inactivar la etapa y hacer un delete en la tabla dsc_preguntas_usuarios_evento.
                DB::query("UPDATE dsc_categorias_eventos SET estadoid = '0' WHERE categoriaid = '{$etapaid}'");
                foreach ($actividadesEtapa as $key2 => $value2) {
                    $preguntaid = $value2['preguntaid'];
                    DB::query("DELETE FROM dsc_preguntas_usuarios_evento WHERE preguntaid = '{$preguntaid}' AND eventoid = '{$eventoid}' AND categoriaid = '{$etapaid}'");
                }
            }

            $data["error"] = false;
            $data["datos"] = '';
            $data["message"] = "Operación exitosa";
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["message"] = $e->getMessage();
            $data["datos"] = $e->getQuery();
        }

        DB::disconnect();
        return $data;
    }

}
