<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
$method = $_SERVER["REQUEST_METHOD"];
$data = json_decode(file_get_contents("php://input"), true);
require "../models/Agendamiento.php";

switch ($method) {
    case "POST":
        case "POST":
            if (isset($data['actividad_id']) && isset($data['usuario_id']) && isset($data['evento_id'])) {
                $actividad_id = $data['actividad_id'];
                $usuario_id = $data['usuario_id'];
                $evento_id = $data['evento_id'];
                $response = Agendamiento::getPreguntasByActividad($actividad_id, $usuario_id, $evento_id);
            } else {
                $response = ["error" => true, "message" => "Datos incompletos en la solicitud POST."];
            }
         break;

    case "GET":
      
        
        break;

    case "PUT":
        //$result = Agendamiento::editar($data);
        break;

    case "DELETE":
        //print_r($data);
        break;
}

echo json_encode($response);
