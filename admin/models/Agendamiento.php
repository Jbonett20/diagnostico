<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "../config/init_db.php";
DB::$error_handler = false;
DB::$throw_exception_on_error = true;
session_start();
class Agendamiento{
    
    public static function getPerfilByid($ide){
        try {
            $data = [];
            $sql_query = "SELECT
                                de.eventoid,
                                UPPER(de.nombre) AS evento,
                                dpe.perfilid,
                                UPPER(dpe.nombre) AS perfil,
                                dce.categoriaid,
                                UPPER(dce.nombre) as etapa
                                FROM
                                    dsc_eventos de
                                INNER JOIN dsc_perfiles_eventos dpe ON
                                    dpe.eventoid = de.eventoid
                                INNER JOIN dsc_categorias_eventos dce ON
                                    dce.perfilid = dpe.perfilid
                                WHERE
                                    de.eventoid = $ide";
                
                $response =DB::query($sql_query);
              
                foreach ($response as $item) {
                    $perfil = $item['perfil'];
                    if (!isset($result[$perfil])) {
                        $result[$perfil] = array(
                            'perfilid' => $item['perfilid'],
                            'perfil' => $perfil,
                            'categorias' => array()
                        );
                    }
                    $result[$perfil]['categorias'][] = array(
                        'categoriaid' => $item['categoriaid'],
                        'etapa' => $item['etapa']
                    );
                }
                $result = array_values($result);
                // print_r($result);
                /* print_r($response[0]['evento']);
                die();
                 */
                $data["error"] = false;
                $data["success"] = true;
                $data["datos"] = $result;
                $data["nombreevento"] = $response[0]['evento'];
            
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["user"] = [];
            $data["message"] = $e->getMessage();
            $data["getQuery"] = $e->getQuery();
        }
        return $data;
    } 
    public static function getGeneralEventos($ide){
        try {
                $data = [];
                $evento = DB::queryFirstRow("SELECT nombre, img,color FROM dsc_eventos WHERE estadoid = 1 AND eventoid = $ide");
                $datos = DB::query("SELECT 
                                            pu.usuarioid,
                                            UPPER(CONCAT(u.nombres,' ',u.apellidos)) as participante,
                                             UPPER(de.nombres) as empresa,
                                            pe.eventoid,
                                            CASE WHEN pe.bono = 1 THEN u.bonopuntos ELSE 'NO APLICA' END AS puntoextras,
                                            pe.perfilid,
                                            UPPER(pe.nombre) as perfil,
                                            ce.categoriaid,
                                            UPPER(CONCAT(ce.nombre,'(',ce.porcentaje,'%)')) as etapa,
                                            SUM(pu.valor) AS valor
                                        FROM 
                                            dsc_preguntas_usuarios_evento pu
                                            JOIN dsc_preguntas p ON pu.preguntaid = p.preguntaid
                                            JOIN dsc_categorias_actividades a ON p.actividadid = a.actividadid
                                            JOIN dsc_categorias_eventos ce ON a.categoriaid = ce.categoriaid
                                            JOIN dsc_perfiles_eventos pe ON ce.eventoid = pe.eventoid AND ce.perfilid = pe.perfilid
                                            JOIN dsc_usuarios_eventos u ON pu.usuarioid = u.usuarioid
                                             join dsc_empresas de ON de.empresaid = u.empresaid
                                        WHERE pe.eventoid = $ide
                                        GROUP BY 
                                            pu.usuarioid, 
                                            pe.eventoid, 
                                            pe.perfilid, 
                                            ce.categoriaid
                                        ORDER BY valor DESC");

                                      /*   print_r($datos);
                                        die(); */
                $nuevo_array = [];
                // Recorremos los datos
                foreach ($datos as $value) {
                    // Verificamos si el perfil ya existe en $nuevo_array
                    $perfil_existente = false;
                    $key_perfil_existente = null;
                    foreach ($nuevo_array as $key => $item) {
                        if ($item['perfil'] === $value['perfil']) {
                            $perfil_existente = true;
                            $key_perfil_existente = $key;
                            break;
                        }
                    }

                    // Si el perfil existe, continuamos la validación para el usuario
                    if ($perfil_existente) {
                        // Verificamos si el usuario ya existe en el array participante del perfil
                        $usuarioid_existente = false;
                        $key_usuarioid_existente = null;
                        foreach ($nuevo_array[$key_perfil_existente]['participante'] as $key => $usuario) {
                            if ($usuario['usuarioid'] === $value['usuarioid']) {
                                $usuarioid_existente = true;
                                $key_usuarioid_existente = $key;
                                break;
                            }
                        }

                        // Si el usuario existe, simplemente agregamos las etapas
                        if ($usuarioid_existente) {
                            $nuevo_array[$key_perfil_existente]['participante'][$key_usuarioid_existente]['etapas'][$value['categoriaid']] = [
                                'etapaid' => $value['categoriaid'],
                                'valor' => $value['valor']
                            ];
                        } else {
                            // Si el usuario no existe, lo agregamos al array participante del perfil
                            $nuevo_array[$key_perfil_existente]['participante'][] = [
                                'usuarioid' => $value['usuarioid'],
                                'participante' => $value['participante'],
                                'empresa' => $value['empresa'],
                                'puntoextras' => $value['puntoextras'],
                                'etapas' => [
                                    $value['categoriaid'] => [
                                        'etapaid' => $value['categoriaid'],
                                        'valor' => $value['valor']
                                        ]
                                ]
                            ];
                        }
                    } else {
                        // Si el perfil no existe, lo agregamos al array junto con el participante y las etapas y la cabecera
                        $heder = DB::query("SELECT categoriaid,CONCAT(nombre, ' (',porcentaje,' %)') AS etapa FROM dsc_categorias_eventos WHERE eventoid = {$value['eventoid']} AND perfilid = {$value['perfilid']} AND estadoid = 1 ORDER BY fechacreacion ASC;");
                        $nuevo_array[] = [
                            'perfil' => $value['perfil'],
                            'heder' => $heder,
                            'participante' => [
                                [
                                    'usuarioid' => $value['usuarioid'],
                                    'participante' => $value['participante'],
                                    'empresa' => $value['empresa'],
                                    'puntoextras' => $value['puntoextras'],
                                    'etapas' => [
                                        // Utilizamos $value['categoriaid'] como índice del array etapas
                                        $value['categoriaid'] => [
                                            'etapaid' => $value['categoriaid'],
                                            'valor' => $value['valor']
                                        ]
                                        ]
                                ]
                            ]
                        ];
                    }
                }
                $data["error"] = false;
                $data["success"] = true;
                $data["datos"] = $nuevo_array;
                $data["eventos"] = $evento;
            
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["user"] = [];
            $data["message"] = $e->getMessage();
            $data["getQuery"] = $e->getQuery();
        }
        return $data;
    }
    public static function getDetails($usuario_id, $evento_id) {
        try {
            $data = [];
            $query = "SELECT 
                        dce.categoriaid,
                        UPPER(dce.nombre) AS categoria,
                        da.actividadid,
                        da.nombre AS actividad,
                        SUM(pu.valor) AS valor
                      FROM dsc_usuarios_eventos ue
                      JOIN dsc_preguntas_usuarios_evento pu ON pu.usuarioid = ue.usuarioid
                      JOIN dsc_preguntas p ON pu.preguntaid = p.preguntaid
                      JOIN dsc_categorias_actividades da ON p.actividadid = da.actividadid
                      JOIN dsc_categorias_eventos dce ON da.categoriaid = dce.categoriaid
                      WHERE ue.usuarioid = $usuario_id AND ue.eventoid = $evento_id
                      GROUP BY dce.categoriaid, da.actividadid
                      ORDER BY dce.categoriaid, da.nombre";
    
            $result = DB::query($query);
            $formattedResult = [];
            foreach ($result as $row) {
                $categoriaId = $row['categoriaid'];
                if (!isset($formattedResult[$categoriaId])) {
                    $formattedResult[$categoriaId] = [
                        'categoria' => $row['categoria'],
                        'actividades' => []
                    ];
                }
                $formattedResult[$categoriaId]['actividades'][] = [
                    'actividadid' => $row['actividadid'],
                    'actividad' => $row['actividad'],
                    'valor' => $row['valor']
                ];
            }
    
            $data["error"] = false;
            $data["success"] = true;
            $data["datos"] = array_values($formattedResult);
    
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["user"] = [];
            $data["message"] = $e->getMessage();
            $data["getQuery"] = $e->getQuery();
        }
        return $data;
    }
    public static function getPreguntasByActividad($actividad_id, $usuario_id, $evento_id) {
        try {
            $data = [];
            $query = "SELECT p.preguntaid, p.nombre AS pregunta, pue.valor, p.valor as puntos
                      FROM dsc_preguntas p
                      INNER JOIN dsc_preguntas_usuarios_evento pue ON p.preguntaid = pue.preguntaid
                      WHERE p.actividadid = $actividad_id
                        AND pue.eventoid = $evento_id
                        AND pue.usuarioid = $usuario_id";
            $result = DB::query($query);
    
            $data["error"] = false;
            $data["success"] = true;
            $data["datos"] = $result;
    
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["user"] = [];
            $data["message"] = $e->getMessage();
            $data["getQuery"] = $e->getQuery();
        }
        return $data;
    }
    public static function getCategoriesByStage($usuario_id, $evento_id, $stage) {
        try {
            $data = [];
            $query = "SELECT da.actividadid, da.nombre AS actividad,dce.porcentaje,
                     coalesce(pu.observacion, '') as observacion,
                      coalesce(pu.archivo, '') as archivo
                      FROM dsc_preguntas_usuarios_evento pu
                      JOIN dsc_preguntas p ON pu.preguntaid = p.preguntaid
                      JOIN dsc_categorias_actividades da ON p.actividadid = da.actividadid
                      JOIN dsc_categorias_eventos dce ON da.categoriaid = dce.categoriaid
                      WHERE pu.usuarioid = $usuario_id AND pu.eventoid = $evento_id AND dce.nombre = '$stage'
                      GROUP BY da.actividadid
                      ORDER BY da.nombre";
    
            $result = DB::query($query);
    
            $data["error"] = false;
            $data["success"] = true;
            $data["datos"] = $result;
    
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["user"] = [];
            $data["message"] = $e->getMessage();
            $data["getQuery"] = $e->getQuery();
        }
        return $data;
    }
   public static function getParticipantes($usuario_id, $evento_id) {
    try {
        $query = "
            SELECT 
                ce.categoriaid,
                UPPER(ce.nombre) AS etapa_nombre,
                ca.actividadid,
                ca.nombre AS actividad_nombre,
                p.preguntaid,
                p.nombre AS pregunta_nombre,
                pu.respuesta,
                pu.valor,
                u.nombres AS calificador_nombre,
                u.apellidos AS calificador_apellido
            FROM dsc_categorias_eventos ce
            LEFT JOIN dsc_categorias_actividades ca ON ca.categoriaid = ce.categoriaid
            LEFT JOIN dsc_preguntas p ON p.actividadid = ca.actividadid
            LEFT JOIN dsc_preguntas_usuarios_evento pu ON pu.preguntaid = p.preguntaid 
                AND pu.usuarioid = %i AND pu.eventoid = %i
            LEFT JOIN dsc_usuarios u ON u.usuarioid = pu.editorid
            WHERE ce.eventoid = %i
            ORDER BY ce.categoriaid, ca.actividadid, p.preguntaid
        ";

        $result = DB::query($query, $usuario_id, $evento_id, $evento_id);

        $estructura = [];

        foreach ($result as $row) {
            $categoriaId = $row['categoriaid'];
            $actividadId = $row['actividadid'];
            $preguntaId = $row['preguntaid'];

            // Agrupar por categoría (etapa)
            if (!isset($estructura[$categoriaId])) {
                $estructura[$categoriaId] = [
                    'categoria' => $row['etapa_nombre'],
                    'actividades' => []
                ];
            }

            // Agrupar por actividad
            if (!isset($estructura[$categoriaId]['actividades'][$actividadId])) {
                $estructura[$categoriaId]['actividades'][$actividadId] = [
                    'actividad' => $row['actividad_nombre'],
                    'preguntas' => []
                ];
            }

            // Agregar pregunta si existe
            if ($preguntaId) {
                $estructura[$categoriaId]['actividades'][$actividadId]['preguntas'][] = [
                    'pregunta'     => $row['pregunta_nombre'] ?: 'Sin nombre',
                    'respuesta'    => $row['respuesta'] ?? 'Sin respuesta',
                    'valor'        => is_numeric($row['valor']) ? floatval($row['valor']) : 0,
                    'calificador'  => ($row['calificador_nombre'] || $row['calificador_apellido']) 
                        ? trim("{$row['calificador_nombre']} {$row['calificador_apellido']}") 
                        : 'Sin calificador'
                ];
            }
        }

        // Convertir subarrays asociativos a indexados para evitar errores en JS
        foreach ($estructura as &$cat) {
            $cat['actividades'] = array_values($cat['actividades']);
        }

        return [
            "error" => false,
            "success" => true,
            "datos" => array_values($estructura)
        ];

    } catch (MeekroDBException $e) {
        return [
            "error" => true,
            "message" => $e->getMessage(),
            "getQuery" => $e->getQuery(),
            "datos" => []
        ];
    }
}


}
