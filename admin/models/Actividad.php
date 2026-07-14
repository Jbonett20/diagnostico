<?php

date_default_timezone_set('America/Bogota');

class Actividad
{
    public static function Listar($categoriaid)
    {
        include_once("../config/init_db.php");
        $data = [];
        try {
            if ($categoriaid) {
                $resultado = DB::query("SELECT ca.*, IFNULL(p.totalpreguntas, 0) AS totalpreguntas, IFNULL(r.totalrespuestas, 0) AS totalrespuestas 
                FROM dsc_categorias_actividades ca
                LEFT JOIN (SELECT actividadid, COUNT(*) AS totalpreguntas 
                FROM dsc_preguntas
                GROUP BY actividadid) p ON ca.actividadid = p.actividadid 
                LEFT JOIN (SELECT p.actividadid, COUNT(*) AS totalrespuestas 
                FROM dsc_preguntas p INNER JOIN dsc_respuestas r ON p.preguntaid = r.preguntaid 
                GROUP BY p.actividadid) r ON ca.actividadid = r.actividadid 
                where ca.categoriaid= %i", $categoriaid);
                if (!empty($resultado)) {
                    $data["error"] = false;
                    $data["success"] = true;
                    $data["datos"] = $resultado;
                } else {
                    $data["error"] = true;
                    $data["success"] = false;
                }
            } else {
                $data = [];
            }
            return $data;
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["message"] = $e->getMessage();
        }

        DB::$error_handler = 'meekrodb_error_handler';
        DB::$throw_exception_on_error = false;
        DB::disconnect();
        return $data;
    }
    public static function veractividad($actividadid)
    {
        include_once("../config/init_db.php");
        DB::$encoding = 'utf8';
        $data = [];
        try {
            if ($actividadid) {
                $resultado = DB::query(
                    "SELECT DISTINCT 
    ca.actividadid, 
    ca.nombre AS actividad_nombre, 
    ca.valor, 
    ca.calificacion,
    p.preguntaid, 
    p.nombre AS pregunta_nombre, 
    p.tipopregunta, 
    p.valor AS pregunta_valor,
    COALESCE(t.totalrespuestas, 0) AS totalrespuestas
FROM 
    dsc_categorias_actividades ca
LEFT JOIN 
    dsc_preguntas p ON ca.actividadid = p.actividadid
LEFT JOIN (
    SELECT 
        r.preguntaid, 
        COUNT(*) AS totalrespuestas
    FROM 
        dsc_respuestas r
    GROUP BY 
        r.preguntaid
) t ON p.preguntaid = t.preguntaid
WHERE 
    ca.actividadid = %i
GROUP BY 
    ca.actividadid, 
    ca.nombre, 
    ca.valor, 
    ca.calificacion,
    p.preguntaid, 
    p.nombre, 
    p.tipopregunta, 
    p.valor, 
    t.totalrespuestas;
",
                    $actividadid
                );

                if (!empty($resultado)) {
                    $data["error"] = false;
                    $data["success"] = true;
                    $data["datos"] = $resultado;
                } else {
                    $data["error"] = true;
                    $data["success"] = false;
                }
            } else {
                $data = [];
            }
            return $data;
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["message"] = $e->getMessage();
        }

        DB::$error_handler = 'meekrodb_error_handler';
        DB::$throw_exception_on_error = false;
        DB::disconnect();
        return $data;
    }

    public static function Crear($nombre, $valor, $calificacion, $categoriaid)
    {
        include_once("../config/init_db.php");
        @session_start();
        $creadorid = $_SESSION['DIAGNOSTICOSALESCONTESTAUTOTRAIN']['usuarioid'];
        $data = [];

        $nombre = strtoupper($nombre);

        try {
            $estadoCategoria = DB::queryFirstField("SELECT estadoid FROM dsc_categorias_eventos WHERE categoriaid = %i", $categoriaid);
            if ($estadoCategoria == 1) {
                $data["error"] = true;
                $data["success"] = false;
                $data["message"] = 'No se puede crear la categoria, la etapa está activa.';
                return $data;
            }

            $existente = DB::queryFirstField("SELECT COUNT(*) FROM dsc_categorias_actividades WHERE nombre = %s AND categoriaid = %i", $nombre, $categoriaid);

            if ($existente > 0) {
                $data["error"] = true;
                $data["success"] = false;
                $data["message"] = 'Ya existe una actividad con ese nombre en la categoría seleccionada';
                $data["datos"] = 'existe';
            } else {
                $porc_categ = DB::queryFirstField("SELECT porcentaje FROM dsc_categorias_eventos WHERE categoriaid=$categoriaid");
                $currentTotalValue = DB::queryFirstField("SELECT SUM(valor) FROM dsc_categorias_actividades WHERE categoriaid = %i", $categoriaid);
                $restaValue = $porc_categ - $currentTotalValue;

                if ($valor > $restaValue) {
                    $data["error"] = true;
                    $data["success"] = false;
                    $data["message"] = 'El valor de la nueva actividad excede el límite';
                    $data["resta"] = $restaValue;
                } else {
                    // Insert the new activity
                    DB::insert('dsc_categorias_actividades', [
                        'categoriaid' => $categoriaid,
                        'estadoid' => 3,
                        'nombre' => $nombre,
                        'valor' => $valor,
                        'calificacion' => $calificacion,
                        'creadorid' => $creadorid
                    ]);

                    if (DB::affectedRows() > 0) {
                        $datos = DB::query("SELECT * FROM dsc_categorias_actividades WHERE categoriaid = %i", $categoriaid);
                        $data["error"] = false;
                        $data["success"] = true;
                        $data['datos'] = $datos;
                        $data["message"] = 'Actividad creada exitosamente';
                    } else {
                        $data["error"] = true;
                        $data["success"] = false;
                        $data["message"] = 'Error al crear la actividad';
                    }
                }
            }
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["message"] = $e->getMessage();
        }

        DB::$error_handler = 'meekrodb_error_handler';
        DB::$throw_exception_on_error = false;
        DB::disconnect();
        return $data;
    }



    public static function crearpreguntas($postData)
    {
        include_once("../config/init_db.php");
        @session_start();
        $creadorid = $_SESSION['DIAGNOSTICOSALESCONTESTAUTOTRAIN']['usuarioid'];
        $data = [];
        try {
            $categoriaid = $postData['categoriaid'];
            $actividadid = $postData['actividadid'];
            $pregunta = $postData['pregunta'];
            $valorPregunta = $postData['valor-pregunta'];
            $tipoPregunta = $postData['tipo-pregunta'];
            $tipoCalificacion = $postData['tipoCalificacion'];

            $estadoCategoria = DB::queryFirstField("SELECT estadoid FROM dsc_categorias_eventos WHERE categoriaid = %i", $categoriaid);

            if ($estadoCategoria == 1) {
                $data["error"] = true;
                $data["success"] = false;
                $data["message"] = 'No se puede crear la pregunta, la etapa está activa.';
                return $data;
            }
            $porc_act = DB::queryFirstField("SELECT valor FROM dsc_categorias_actividades WHERE actividadid=$actividadid");
            $totalValor = DB::queryFirstField("SELECT SUM(valor) FROM dsc_preguntas WHERE actividadid = %i", $actividadid);

            if ($totalValor + $valorPregunta > $porc_act) {
                $data["error"] = true;
                $data["resta"] = $porc_act - $totalValor;
                $data["message"] = 'El valor sobrepasa el límite de puntos.';
            } else {
                // Insertar la pregunta
                $maxItems = ($tipoCalificacion !== 'calificación') ? 0 : 1;
                DB::insert('dsc_preguntas', [
                    'actividadid' => $actividadid,
                    'nombre' => $pregunta,
                    'valor' => $valorPregunta,
                    'tipopregunta' => $tipoPregunta,
                    'creadorid' => $creadorid,
                    'estadoid' => 3,
                    'max_items' => $maxItems
                ]);
                $preguntaid = DB::insertId();

                if (DB::affectedRows() > 0) {
                    if ($tipoCalificacion !== 'calificación') {
                        // Insertar respuestas en dsc_respuestas
                        $items = 0;
                        foreach ($postData as $key => $value) {
                            if (preg_match('/np(\d+)/', $key, $matches)) {
                                $index = $matches[1];
                                $nombreRespuesta = $value;
                                $valorRespuesta = $postData["cant$index"];

                                if ($nombreRespuesta && is_numeric($valorRespuesta) && $valorRespuesta >= 0) {
                                    DB::insert('dsc_respuestas', [
                                        'preguntaid' => $preguntaid,
                                        'editorid' => $creadorid,
                                        'nombre' => $nombreRespuesta,
                                        'creadorid' => $creadorid,
                                        'valor' => $valorRespuesta
                                    ]);
                                    if ($valorRespuesta > 0) {
                                        $items++;
                                    }
                                }
                            }
                        }

                        DB::update('dsc_preguntas', ['max_items' => $items], 'preguntaid=%i', $preguntaid);
                    }

                    $datos = DB::query("SELECT * FROM dsc_categorias_actividades WHERE categoriaid = %i", $categoriaid);
                    $data["error"] = false;
                    $data["success"] = true;
                    $data["datos"] = $datos;
                    $data["message"] = 'Pregunta creada exitosamente';
                } else {
                    $data["error"] = true;
                    $data["success"] = false;
                    $data["message"] = 'Error al crear la pregunta';
                }
            }
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["message"] = $e->getMessage();
        }

        DB::$error_handler = 'meekrodb_error_handler';
        DB::$throw_exception_on_error = false;
        DB::disconnect();
        return $data;
    }





    public static function Eliminar($actividadid, $categoriaid)
    {
        include_once("../config/init_db.php");
        $data = [];
        try {
            $estadoCategoria = DB::queryFirstField("SELECT estadoid FROM dsc_categorias_eventos WHERE categoriaid = %i", $categoriaid);

            if ($estadoCategoria == 1) {
                $data["error"] = true;
                $data["success"] = false;
                $data["message"] = 'No se puede eliminar, la etapa está activa.';
                return $data;
            }

            $preguntas_actividad = DB::queryFirstColumn("SELECT preguntaid FROM dsc_preguntas WHERE actividadid = %i", $actividadid);

            if (!empty($preguntas_actividad)) {
                DB::delete('dsc_respuestas', 'preguntaid IN %li', $preguntas_actividad);
                DB::delete('dsc_preguntas', 'actividadid=%i', $actividadid);
            }
            DB::delete('dsc_categorias_actividades', 'actividadid=%i', $actividadid);

            $data["error"] = false;
            $data["success"] = true;
            $data["message"] = 'Categoría eliminada correctamente';
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["message"] = $e->getMessage();
        }

        DB::$error_handler = 'meekrodb_error_handler';
        DB::$throw_exception_on_error = false;
        DB::disconnect();
        return $data;
    }
}
