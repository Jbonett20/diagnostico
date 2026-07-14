<?php
require("../config/init_db.php");
date_default_timezone_set('America/Bogota');
DB::$error_handler = false;
DB::$throw_exception_on_error = true;
class PerfilEvento
{    

    
        public static function Listar($eventoid)
        {
            include_once("../config/init_db.php");
            $data = [];
            try {
                if ($eventoid) {
                    $resultado = DB::query("SELECT 
                    pe.*,
                    ce.categoriaid AS categoria_id, 
                    ce.perfilid AS perfil_id, 
                    ce.creadorid AS creador_id, 
                    ce.editorid AS editor_id, 
                    ce.estadoid AS estado_id, 
                    ce.nombre AS categoria_nombre, 
                    ce.porcentaje AS categoria_porcentaje, 
                    ce.fechacreacion AS categoria_fechacreacion, 
                    ce.fechaedicion AS categoria_fechaedicion
                FROM 
                    dsc_perfiles_eventos pe
                LEFT JOIN 
                    dsc_categorias_eventos ce
                ON 
                    pe.eventoid = ce.eventoid
                WHERE 
                    pe.eventoid = %i", $eventoid);
                    $data=$resultado;
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
        public static function perfilevento($eventoid)
        {
            include_once("../config/init_db.php");
            $data = [];
            try {
                if ($eventoid) {
                    $resultado = DB::query("SELECT 
                        e.*,
                        pe.*
                    FROM 
                        dsc_eventos e
                    INNER JOIN 
                        dsc_perfiles_eventos pe
                    ON 
                        e.eventoid = pe.eventoid
                    WHERE 
                        e.eventoid = %i", $eventoid);
        
                    if (!empty($resultado)) {
                        $data["error"] = false;
                        $data["success"] = true;
                        $data["datos"] = $resultado;
                    } else {
                        $data["error"] = true;
                        $data["success"] = false;
                        $data["datos"] = [];
                    }
                } else {
                    $data["error"] = true;
                    $data["success"] = false;
                    $data["message"] = 'El ID del evento es inválido';
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
        
    
        public static function Crear($p)
        {
            include_once("../config/init_db.php");
            $data = [];
            try {
                @session_start();
                $creadorid = $_SESSION['DIAGNOSTICOSALESCONTESTAUTOTRAIN']['usuarioid'];
                $eventoid = $p['eventoid'];
                $nombrePerfil = strtoupper($p['nombrePerfil']);
                $fechacreacion = date('Y-m-d H:i:s');
                $estadoid = 3;  
        
                $perfilData = array(
                    'creadorid' => $creadorid,
                    'estadoid' => $estadoid,
                    'eventoid' => $eventoid,
                    'nombre' => $nombrePerfil,
                    'fechacreacion' => $fechacreacion,
                    'fechaedicion' => $fechacreacion, 
                    'bono' => 0
                );
                $perfilExist = DB::queryFirstRow('SELECT * FROM dsc_perfiles_eventos WHERE LOWER(nombre) = LOWER(%s) AND eventoid = %i', $nombrePerfil, $eventoid);
                if (!empty($perfilExist)) {
                    $data["error"] = true;
                    $data["success"] = false;
                    $data["datos"] = 'existe'; 
                } else {
                    $resultado = DB::insert('dsc_perfiles_eventos', $perfilData);
                    if ($resultado) {
                        $perfilInsertado = PerfilEvento::Listar($eventoid);
        
                        $data["error"] = false;
                        $data["success"] = true;
                        $data["datos"] = $perfilInsertado; 
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
        

    public static function Editar($p)
    {
        include_once("../config/init_db.php");
        $data = [];
        try {
            @session_start();
            $perfilid = $p['perfilid'];
            $bono = $p['bono'];
            $fechaedicion = date('Y-m-d H:i:s');

            $perfilData = array(
                'bono' => $bono,
                'fechaedicion' => $fechaedicion
            );

            $resultado = DB::update('dsc_perfiles_eventos', $perfilData, "perfilid=%i", $perfilid);
            if ($resultado) {

                $data["error"] = false;
                $data["success"] = true;
            } else {
                $data["error"] = true;
                $data["success"] = false;
                $data["message"] = "No se pudo actualizar el perfil o no hubo cambios";
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
    public static function Eliminar($p)
    {
        include_once("../config/init_db.php");
        
        try {
            $data = [];
            if (!isset($p['perfilid']) || empty($p['perfilid'])) {
                throw new MeekroDBException("No existe el perfil");
            }
            
            $perfilid = $p['perfilid'];
             //TODO: VERICAMOS Q LAS ETAPAS ESTEN EN ESTADO INACTIVO
            $estadoEtapa= DB::query("SELECT * FROM `dsc_categorias_eventos` WHERE estadoid=1  AND perfilid=$perfilid");
            if($estadoEtapa){
                throw new MeekroDBException("El perfil no se puede eliminar porque tiene etapas activas");
            }
            DB::startTransaction();
        
            // Eliminar respuestas relacionadas
            DB::query("DELETE FROM dsc_respuestas WHERE preguntaid IN (SELECT preguntaid FROM dsc_preguntas WHERE actividadid IN (SELECT actividadid FROM dsc_categorias_actividades WHERE categoriaid IN (SELECT categoriaid FROM dsc_categorias_eventos WHERE perfilid = %i)))", $perfilid);
        
            // Eliminar preguntas relacionadas
            DB::query("DELETE FROM dsc_preguntas WHERE actividadid IN (SELECT actividadid FROM dsc_categorias_actividades WHERE categoriaid IN (SELECT categoriaid FROM dsc_categorias_eventos WHERE perfilid = %i))", $perfilid);
        
            // Eliminar actividades relacionadas
            DB::query("DELETE FROM dsc_categorias_actividades WHERE categoriaid IN (SELECT categoriaid FROM dsc_categorias_eventos WHERE perfilid = %i)", $perfilid);
        
            // Eliminar categorías relacionadas
            DB::delete('dsc_categorias_eventos', 'perfilid=%i', $perfilid);
        
            // Eliminar perfil
            $deleted = DB::delete('dsc_perfiles_eventos', 'perfilid=%i', $perfilid);
        
            if ($deleted) {
                $data["error"] = false;
                $data["success"] = true;
                $data["message"] = "Perfil eliminado correctamente";
                DB::commit();
            } else {
                $data["error"] = true;
                $data["message"] = "No se pudo eliminar el perfil. Puede que no exista.";
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

