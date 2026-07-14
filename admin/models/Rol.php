<?php
require "../config/init_db.php";
date_default_timezone_set('America/Bogota');
DB::$error_handler = false;
DB::$throw_exception_on_error = true;
@session_start();
class Rol
{
    public static function AllSelect()
    {
        $data = [];
        // $empresaid = $_SESSION["TYLFLYI"]["empresaid"];
        try {
            $data["res"] = DB::query("SELECT
                                        rolid,
                                        nombre
                                    FROM
                                        dsc_roles where estadoid = 1");

            $data["error"] = false;
            $data["message"] = "";
            $data["getQuery"] = "";
        } catch (MeekroDBException $e) {
            $data["error"] = true;
            $data["res"] = [];
            $data["message"] = $e->getMessage();
            $data["getQuery"] = $e->getQuery();
        }
        DB::$error_handler = 'meekrodb_error_handler';
        DB::$throw_exception_on_error = false;
        DB::disconnect();
        return $data;
    }
}
