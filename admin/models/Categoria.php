<?php

date_default_timezone_set('America/Bogota');

class CategoriaEvento
{
    public static function participantes($eventoid)
    {
        include_once("config/init_db.php");
        $data = [];
        try {
            if ($eventoid) {
                $resultado = DB::query("SELECT * FROM dsc_perfiles_eventos WHERE eventoid = %i", $eventoid);
                foreach ( $resultado as $key => $value) {
                      $evenid=$value['eventoid'];
                      $perfilid=$value['perfilid'];
                      $allUsuarios= DB::query("SELECT * FROM  dsc_usuarios_eventos WHERE eventoid= $evenid AND $perfilid AND estadoid=1");
                }
              
                $data = $allUsuarios;
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

    public static function Listar($eventoid)
    {
        include_once("config/init_db.php");
        $data = [];
        try {
            if ($eventoid) {
                $resultado = DB::query("SELECT * FROM dsc_perfiles_eventos WHERE eventoid = %i", $eventoid);
                $data = $resultado;
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

    public static function ListarCategoria($eventoid, $perfilid)
    {
        include_once("../config/init_db.php");
        $data = [];
        try {
            if ($eventoid && $perfilid) {
                $resultado = DB::query("SELECT * FROM dsc_categorias_eventos WHERE eventoid = %i AND perfilid = %i", $eventoid, $perfilid);
                $data = $resultado;
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

    public static function Crear($p)
    {
        include_once("../config/init_db.php");
        $data = [];
        try {
            @session_start();
            $creadorid = $_SESSION['DIAGNOSTICOSALESCONTESTAUTOTRAIN']['usuarioid'];
            $eventoid = $p['eventoid'];
            $perfilid = $p['perfilid'];
            $nombreCategoria = strtoupper($p['nombreCategoria']);
            $porcentaje = $p['porcentaje'];
            $calificacion = $p['tipoetapa'];
            $estadoid = 0;
            $fechacreacion = date('Y-m-d H:i:s');
            
            $categoriaData = array(
                'creadorid' => $creadorid,
                'editorid' => null,
                'estadoid' => $estadoid,
                'eventoid' => $eventoid,
                'perfilid' => $perfilid,
                'nombre' => $nombreCategoria,
                'porcentaje' => $porcentaje,
                'calificacion' => $calificacion,
                'fechacreacion' => $fechacreacion,
                'fechaedicion' => $fechacreacion
            );
    
            // Obtener el porcentaje total actual
            $totalPorcentajeCategoria = DB::queryFirstRow('SELECT SUM(porcentaje) AS totalporcentaje FROM dsc_categorias_eventos WHERE eventoid = %i AND perfilid = %i', $eventoid, $perfilid);
            $totalPorcentaje = $totalPorcentajeCategoria['totalporcentaje'] + $porcentaje;
    
            // Verificar si el nombre de la categoría ya existe
            $categoriaExist = DB::queryFirstRow('SELECT * FROM dsc_categorias_eventos WHERE LOWER(nombre) = LOWER(%s) AND eventoid = %i AND perfilid = %i', $nombreCategoria, $eventoid, $perfilid);
    
            if (!empty($categoriaExist)) {
                $data["error"] = true;
                $data["success"] = false;
                $data["datos"] = 'existe';
            } else {
                if ($totalPorcentaje > 100) {
                    $data["error"] = true;
                    $data["success"] = false;
                    $data["datos"] = 100 - $totalPorcentajeCategoria['totalporcentaje'];
                } else {
                    $resultado = DB::insert('dsc_categorias_eventos', $categoriaData);
                    if ($resultado) {
                        $categoriaInsertada = CategoriaEvento::ListarCategoria($eventoid, $perfilid);
    
                        $data["error"] = false;
                        $data["success"] = true;
                        $data["datos"] = $categoriaInsertada;
                    }
                }
            }
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
    
    //funcion para editar categoria por id
    public static function EditarCategoria($p) {
        include_once("../config/init_db.php");
       
        try {
            $data = [];
        
            if (!isset($p['categoria_id']) || empty($p['categoria_id'])) {
                $data["error"] = true;
                $data["success"] = false;
                $data["message"] = "No existe esa categoría";
                return $data;
            }
        
            $eventoid = $p['evento_id'];
            $nombre = strtoupper($p['cat_nombre']);
            $categoriaid = $p['categoria_id'];
            $perfilid = $p['perfil_id'];
            $porcentaje = $p['cat_porcentaje'];
            $currentCategoria = DB::queryFirstRow('SELECT * FROM dsc_categorias_eventos WHERE categoriaid = %i', $categoriaid);
        
            if (!$currentCategoria) {
                $data["error"] = true;
                $data["success"] = false;
                $data["message"] = "Categoría no encontrada";
                return $data;
            }
    
            $categoriaExist = DB::queryFirstRow('SELECT * FROM dsc_categorias_eventos WHERE LOWER(nombre) = LOWER(%s) AND eventoid = %i AND perfilid = %i AND categoriaid != %i', $nombre, $eventoid, $perfilid, $categoriaid);
        
            if (!empty($categoriaExist)) {
                $data["error"] = true;
                $data["success"] = false;
                $data["message"] = "El nombre de la categoría ya existe";
                return $data;
            }
            $totalPorcentajeCategoria = DB::queryFirstRow('SELECT SUM(porcentaje) AS totalporcentaje FROM dsc_categorias_eventos WHERE eventoid = %i AND perfilid = %i AND categoriaid != %i', $eventoid, $perfilid, $categoriaid);
            $totalPorcentaje = $totalPorcentajeCategoria['totalporcentaje'] + $porcentaje;
        
            if ($totalPorcentaje > 100) {
                $data["error"] = true;
                $data["success"] = false;
                $data["message"] = "El porcentaje total excede 100%. El porcentaje restante es " . (100 - $totalPorcentajeCategoria['totalporcentaje']) . "%.";
                return $data;
            }
        
            $estado = DB::queryFirstField("SELECT estadoid FROM dsc_categorias_eventos WHERE categoriaid = %i", $categoriaid);
            if ($estado == 1) {
                $data["error"] = true;
                $data["success"] = false;
                $data["activa"] = true;
                $data["message"] = "La etapa ya está activa y no se puede editar.";
                DB::rollback();
                DB::disconnect();
                return $data;
            }
    
            DB::startTransaction();

            DB::update('dsc_categorias_eventos', [
                'nombre' => $nombre,
                'porcentaje' => $porcentaje
            ], 'categoriaid = %i', $categoriaid);
            DB::commit();
            $data["error"] = false;
            $data["success"] = true;
            $data["message"] = "Categoría actualizada correctamente";
            $data["datos"] = CategoriaEvento::ListarCategoria($eventoid,$perfilid);
    
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["message"] = "Error de base de datos: " . $e->getMessage();
            DB::rollback();
        }
    
        DB::disconnect();
    
        return $data;
    }
    


    public static function Eliminar($p)
    {
        include_once("../config/init_db.php");
        $data = [];
    
        if (!isset($p['categoriaid']) || empty($p['categoriaid'])) {
            $data["error"] = true;
            $data["message"] = "No existe esa categoría";
            return $data;
        }
    
        $categoriaid = $p['categoriaid'];
    
        try {
            DB::startTransaction();
            $estado = DB::queryFirstField("SELECT estadoid FROM dsc_categorias_eventos WHERE categoriaid = %i", $categoriaid);
            if ($estado == 1) {
                $data["error"] = true;
                $data["activa"] = true;
                $data["message"] = "La etapa ya está activa y no se puede eliminar.";
                DB::rollback();
                DB::disconnect();
                return $data;
            }
    
            // Eliminar respuestas relacionadas con las preguntas de esta categoría
            DB::query("DELETE FROM dsc_respuestas WHERE preguntaid IN (SELECT preguntaid FROM dsc_preguntas WHERE actividadid IN (SELECT actividadid FROM dsc_categorias_actividades WHERE categoriaid = %i))", $categoriaid);
    
            // Eliminar preguntas relacionadas con las actividades de esta categoría
            DB::query("DELETE FROM dsc_preguntas WHERE actividadid IN (SELECT actividadid FROM dsc_categorias_actividades WHERE categoriaid = %i)", $categoriaid);
    
            // Eliminar actividades de esta categoría
            DB::query("DELETE FROM dsc_categorias_actividades WHERE categoriaid = %i", $categoriaid);
    
            // Eliminar la categoría
            $deleted = DB::delete('dsc_categorias_eventos', 'categoriaid=%i', $categoriaid);
    
            if ($deleted) {
                $data["error"] = false;
                $data["success"] = true;
                $data["message"] = "Etapa eliminada correctamente";
                DB::commit();
            } else {
                $data["error"] = true;
                $data["message"] = "No se pudo eliminar la etapa. Puede que no exista.";
                DB::rollback();
            }
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["message"] = "Error de base de datos: " . $e->getMessage();
            DB::rollback();
        }
    
        DB::disconnect();
    
        return $data;
    }
    
    
}
