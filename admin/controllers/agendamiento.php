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

        break;

    case "GET":

        switch ($_GET["opcn"]) {
            case 'getperfil':
                // $response = Agendamiento::getPerfilByid($_GET['evento_id']);
                $response = Agendamiento::getGeneralEventos($_GET['evento_id']);

                break;
            case 'getDetails':
                $usuario_id = $_GET['usuario_id'];
                $evento_id = $_GET['evento_id'];
                $response = Agendamiento::getDetails($usuario_id, $evento_id);
                break;
            case 'getParticipantes':
                $usuario_id = $_GET['usuario_id'];
                $evento_id = $_GET['evento_id'];
                $response = Agendamiento::getParticipantes($usuario_id, $evento_id);
                break;
        }
        break;

    case "PUT":
        //$result = Agendamiento::editar($data);
        break;

    case "DELETE":
        //print_r($data);
        break;
}

echo json_encode($response);
