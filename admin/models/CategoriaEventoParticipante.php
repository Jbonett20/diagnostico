<?php
require "../config/init_db.php";
date_default_timezone_set('America/Bogota');
DB::$error_handler = false;
DB::$throw_exception_on_error = true;
session_start();

class CategoriaEventoParticipante
{
    public static function getAllCategoriaEventoparticipante($eventoid)
    {
        try {
            $data = [];
            if (isset($_SESSION['DIAGNOSTICOSALESCONTESTAUTOTRAINALUMNO'])) {
                $usuariosid = $_SESSION['DIAGNOSTICOSALESCONTESTAUTOTRAINALUMNO']['usuarioid'];
                $query_sql_perfil = "SELECT
                                        dce.categoriaid,
                                        UPPER(dce.nombre) AS categoria,
                                        dce.porcentaje,
                                        de.nombre AS evento,
                                        dca.actividadid,
                                        UPPER(dca.nombre) as actividad,
                                        dca.calificacion,
                                        dce.estadoid
                                    FROM
                                        dsc_usuarios_eventos due
                                    INNER JOIN dsc_eventos de ON
                                        de.eventoid = due.eventoid
                                    INNER JOIN dsc_categorias_eventos dce ON
                                        dce.eventoid = de.eventoid
                                    INNER JOIN dsc_categorias_actividades dca ON 
                                    dca.categoriaid = dce.categoriaid and dce.perfilid = due.perfilid
                                    WHERE
                                        due.usuarioid = $usuariosid
                                        AND de.eventoid = $eventoid 
                                        AND dca.calificacion = 'responder'
                                    ORDER BY
                                        dce.categoriaid,
                                        dca.actividadid;";
                
                $resul = DB::query($query_sql_perfil);
                $categorias = [];
             
                $actividadesEstado = DB::query("SELECT actividadid, estado FROM dsc_actividades_usuarios WHERE usuarioid = %i", $usuariosid);
                $estadoActividades = [];
                foreach ($actividadesEstado as $estado) {
                    $estadoActividades[$estado['actividadid']] = $estado['estado'];
                }
    
                foreach ($resul as $row) {
                    $categoriaid = $row['categoriaid'];
                    $nombrecategoria = $row['categoria'];
                    $porcentaje = $row['porcentaje'];
                    $estado = $row['estadoid'];
    
                    if (!isset($categorias[$categoriaid])) {
                        $categorias[$categoriaid] = [
                            'categoriaid' => $categoriaid,
                            'nombrecategoria' => $nombrecategoria,
                            'porcentaje' => $porcentaje,
                            'estado' => $estado,
                            'actividades' => []
                        ];
                    }
    
                    $actividadid = $row['actividadid'];
                    $actividadNombre = $row['actividad'];
    
                    // Determinar el estado de la actividad
                    $estadoActividad = isset($estadoActividades[$actividadid]) ? $estadoActividades[$actividadid] : 0;
                    $estadoTexto = $estadoActividad == 2 ? 'realizada' : 'sin realizar';
    
                    $categorias[$categoriaid]['actividades'][] = [
                        'actividadid' => $actividadid,
                        'nombre' => $actividadNombre,
                        'estado' => $estadoTexto
                    ];
                }

                $data['evento'] = DB::queryFirstRow("SELECT UPPER(nombre) as evento, img FROM dsc_eventos WHERE eventoid = $eventoid");
                $data["error"] = false;
                $data["message"] = "cargando Datos";
                $data["data"] = array_values($categorias);
            } else {
                $data["error"] = true;
                $data["message"] = "Sin sesión";
                $data["data"] = [];
            }
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
?>
