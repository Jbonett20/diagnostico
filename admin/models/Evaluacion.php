<?php
require "../config/init_db.php";
// require "Email.php";
date_default_timezone_set('America/Bogota');
DB::$error_handler = false;
DB::$throw_exception_on_error = true;
session_start();


class Evaluacion
{

    public static function findByid($eid, $cid)
    {
        try {
            $data = [];
            $usuarioid = $_SESSION['DIAGNOSTICOSALESCONTESTAUTOTRAINALUMNO']['usuarioid'];

            $query_sql = "SELECT DISTINCT
    da.actividadid,
    da.nombre AS actividad,
    dp.preguntaid,
    dp.nombre AS pregunta,
    dp.tipopregunta,
    dp.valor,
    dp.max_items,
    dr.respuestaid,
    dr.nombre AS respuesta,
    dr.valor AS valorrespueta
FROM
    dsc_categorias_actividades da
INNER JOIN dsc_preguntas dp ON
    dp.actividadid = da.actividadid
INNER JOIN dsc_preguntas_usuarios_evento dpue ON
    dpue.preguntaid = dp.preguntaid
LEFT JOIN dsc_respuestas dr ON
    dr.preguntaid = dp.preguntaid
WHERE
    da.actividadid = $eid 
    AND dpue.usuarioid=$usuarioid
    AND da.categoriaid = $cid
    AND (dpue.respuesta IS NULL OR dpue.respuesta = '') 
    AND (dpue.valor IS NULL OR dpue.valor = 0)
ORDER BY RAND();";

            $response = DB::query($query_sql);
            $questions = [];
            $questionMap = [];

            foreach ($response as $row) {
                $questionid = $row['preguntaid'];

                if (!isset($questionMap[$questionid])) {
                    $questionMap[$questionid] = [
                        'actividadid' => $row['actividadid'],
                        'actividad' => $row['actividad'],
                        'preguntaid' => $row['preguntaid'],
                        'question' => $row['pregunta'],
                        'items' => $row['max_items'],
                        'type' => ($row['tipopregunta'] == 'única respuesta') ? 'single' : (($row['tipopregunta'] == 'opción múltiple') ? 'multiple' : 'text'),
                        'options' => []
                    ];
                }

                if ($row['tipopregunta'] !== 'libre') {
                    $questionMap[$questionid]['options'][] = [
                        'respuestaid'=>$row['respuestaid'],
                        'opc' => $row['respuesta'],
                        'value' => (float)$row['valorrespueta']
                    ];
                }
            }

            foreach ($questionMap as $question) {
                $questions[] = $question;
            }

            $data["error"] = false;
            $data["message"] = "cargando Datos";
            $data["data"] = $questions;
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
    public static function Editar($p)
    {  
        $usuarioid = $_SESSION['DIAGNOSTICOSALESCONTESTAUTOTRAINALUMNO']['usuarioid'];
        $eventoid = $p['eventoid'];
        $preguntaid = $p['preguntaid'];
        $categoriaid = $p['categoriaid'];
        $actividadid = $p['actividadid'];
        $respuestas = $p['respuestas'];
        $valorTotal = 0;
        $respuestasTexto = [];

        foreach ($respuestas as $respuesta) {
            $valorTotal += $respuesta['valor'];
            $Datos[] = $respuesta['respuestaid'];
        }

        $respuestaId= implode(", ", $Datos);

        try {
            $exisEval = DB::queryFirstRow("SELECT * FROM dsc_actividades_usuarios WHERE usuarioid = %i AND actividadid = %i", $usuarioid, $actividadid);
        
            if (empty($exisEval)) {
                
                DB::insert('dsc_actividades_usuarios', [
                    'usuarioid' => $usuarioid,
                    'actividadid' => $actividadid,
                    'estado' => 2
                ]);
            }
            $existing = DB::queryFirstRow("SELECT * FROM dsc_preguntas_usuarios_evento WHERE preguntaid = %i AND eventoid = %i AND categoriaid = %i", $preguntaid, $eventoid, $categoriaid);

            if ($existing) {
                $updateData = array(
                    'respuesta' => $respuestaId,
                    'valor' => $valorTotal,
                    'fechaedicion' => date('Y-m-d H:i:s'),
                    'editorid' => $usuarioid
                );
                  DB::update('dsc_preguntas_usuarios_evento', $updateData, "preguntaid = %i AND eventoid = %i AND categoriaid = %i AND usuarioid =%i", $preguntaid, $eventoid, $categoriaid, $usuarioid);
                  $sumaValores = DB::queryFirstField("SELECT SUM(pue.valor) FROM dsc_preguntas_usuarios_evento pue
                  INNER JOIN dsc_preguntas p ON pue.preguntaid = p.preguntaid
                  INNER JOIN dsc_categorias_actividades ca ON p.actividadid = ca.actividadid
                   WHERE ca.actividadid = %i AND pue.categoriaid = %i", $actividadid, $categoriaid);

                $data["error"] = false;
                $data["message"] = "Respuesta guardada exitosamente";
                $data["data"] = $sumaValores;
            } else {
                $data["error"] = true;
                $data["message"] = "La pregunta no existe en este evento y categoría";
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
