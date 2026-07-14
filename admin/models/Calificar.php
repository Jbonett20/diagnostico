<?php
class Calificar {
  
public static function listarPerfiles($v){
    include_once('../config/init_db.php');
    DB::$encoding = 'utf8';
    $resultado = DB::query("SELECT * FROM `dsc_perfiles_eventos` WHERE eventoid  = %s", $v['id']);
    
    return $resultado; 
}

public static function listarTitulo($v){
    include_once('../config/init_db.php');
    DB::$encoding = 'utf8';
    $resultado = DB::queryFirstRow("SELECT * FROM `dsc_eventos` WHERE eventoid  = %s", $v['id']);
    
    return $resultado; 
}

public static function Participantes($v){
    include_once('../config/init_db.php');
    DB::$encoding = 'utf8';
    $resultado = DB::query("SELECT * FROM `dsc_usuarios_eventos` WHERE `estadoid` = %i AND `perfilid` = %s", 1, $v['id']);
    
    return $resultado; 
}

public static function Etapas($v) {
    include_once('../config/init_db.php');
    DB::$encoding = 'utf8';

    // Consulta 1: Obtener los eventos
    $eventos = DB::query("SELECT 
                            e.*
                        FROM 
                            dsc_categorias_eventos e
                        WHERE 
                            e.calificacion = 'calificacion' AND e.estadoid = '1' AND e.perfilid = %s 
                    ", $v['idperfil']);

    // Obtener los IDs de los eventos
    $eventosIds = array_column($eventos, 'categoriaid');

    // Construcción manual de placeholders
    if (!empty($eventosIds)) {
        $placeholders = implode(',', array_fill(0, count($eventosIds), '%d'));
        $actividades = DB::query("
            SELECT 
                a.*,
                a.categoriaid AS evento_id,
                au.estado
            FROM 
                dsc_categorias_actividades a
            LEFT JOIN 
                dsc_actividades_usuarios au ON a.actividadid = au.actividadid AND au.usuarioid = %d
            WHERE 
                a.categoriaid IN ($placeholders)
        ", $v['iduser'], ...$eventosIds);
    } else {
        $actividades = [];
    }

    // Construcción de la estructura `data`
    $data = [];
    foreach ($eventos as $evento) {
        $evento_id = $evento['categoriaid'];
        $data[$evento_id] = [
            'nombre' => $evento['nombre'],
            'porcentaje' => $evento['porcentaje'],
            'categoriaId' => $evento['categoriaid'],
            'actividades' => []
        ];
    }

    foreach ($actividades as $actividad) {
        $evento_id = $actividad['evento_id'];

        $data[$evento_id]['actividades'][] = [
            'nombre' => $actividad['nombre'],
            'valor' => $actividad['valor'],
            'id' => $actividad['actividadid'],
            'estado' => isset($actividad['estado']) ? $actividad['estado'] : '0', // Añadir estado a la estructura
        ];
    }

    return array_values($data);

}



public static function Preguntas($v){
    include_once('../config/init_db.php');
    DB::$encoding = 'utf8';
    $resultado = DB::query("SELECT * FROM `dsc_preguntas` WHERE actividadid  = %s", $v['id']);

    //  $insertQuery = DB::query("INSERT INTO `dsc_actividades_usuarios` 
    //                        (`usuarioid`, `actividadid`, `estado`) 
    //                        VALUES (%i, %i, 1)",
    //                        $v['participante'], $v['id']);
    
    return $resultado; 
}

public static function Carga_pregunta($v) {
    
    include_once('../config/init_db.php');
    DB::$encoding = 'utf8';
    @session_start();
    $usuarioid = $_SESSION['DIAGNOSTICOSALESCONTESTAUTOTRAIN']['usuarioid'];

    $eventoId = $v['evento'];
    $usuarioId = $v['participante'];
    $categoriaId = $v['categoriaide'];
    $observacion = $v['observacion'];
    $archivoNombre = $v['archivo_nombre'];
    $archivoTmp = $v['archivo_tmp'];

    $rutaArchivo = null;

    // Guardar archivo si existe
    if (!empty($archivoNombre) && !empty($archivoTmp)) {
        $nombreFinal = time() . '_' . basename($archivoNombre);
        $rutaDestino = '../assets/archivos/' . $nombreFinal;

        if (move_uploaded_file($archivoTmp, $rutaDestino)) {
            $rutaArchivo = 'assets/archivos/' . $nombreFinal;
        }
    }

    foreach ($v['respuestasPreguntas'] as $respuesta) {
        $preguntaid = $respuesta['id'];
        $respuestaTexto = $respuesta['valor'];

        $existePregunta = DB::queryFirstRow("SELECT * FROM `dsc_preguntas_usuarios_evento` WHERE usuarioid = %i AND preguntaid = %i", $usuarioId, $preguntaid);

        if (empty($existePregunta)) {
            DB::insert('dsc_preguntas_usuarios_evento', [
                'usuarioid'    => $usuarioId,
                'preguntaid'   => $preguntaid,
                'eventoid'     => $eventoId,
                'categoriaid'  => $categoriaId,
                'creadorid'    => $usuarioid,
                'editorid'     => $usuarioid,
                'valor'        => $respuestaTexto,
                'observacion'  => $observacion,
                'archivo'      => $rutaArchivo
            ]);
        } else {
            DB::update('dsc_preguntas_usuarios_evento', [
                'valor'       => $respuestaTexto,
                'editorid'    => $usuarioid,
                'observacion' => $observacion,
                'archivo'     => $rutaArchivo
            ], "usuarioid=%i AND preguntaid=%i", $usuarioId, $preguntaid);
        }
    }

   DB::insert('dsc_actividades_usuarios', [
    'usuarioid' => $usuarioId,
    'actividadid' => $v['actividad'],
    'estado' => 2
]);


    return [
        'success' => true,
        'message' => 'Respuestas y observación guardadas correctamente.'
    ];
}




   

}