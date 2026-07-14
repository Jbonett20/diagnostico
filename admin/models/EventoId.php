<?php
date_default_timezone_set('America/Bogota');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 


class EventoId
{
  
    public static function ListarEventoId($id)
    {
        include_once("config/init_db.php");
        DB::$error_handler = false;
        DB::$throw_exception_on_error = true;
        $data = [];
        try {
          
           $resultado= DB::queryFirstRow("SELECT * FROM dsc_eventos WHERE eventoid=%i",$id);
        if(!$resultado){
            throw new MeekroDBException("El evento no existe");  
        }
        $data["error"] =false;
        $data =$resultado;
        $data["message"] = "Operación exitosa";
          
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
}
