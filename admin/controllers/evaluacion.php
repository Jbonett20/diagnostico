<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 require "../models/Evaluacion.php";

// Incluir el autoloader de Composer
require '../../vendor/autoload.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
$data = json_decode(file_get_contents("php://input"), true);

$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {

  case 'POST':
    switch ($_POST["opcn"]) {
      case 'cargaPartcicipante':
        //      $response = $res;
    }
    break;

  case 'GET':
    switch ($_GET["opcn"]) {
      case 'getEvaluacion':
       /*  print_r($_GET);
        die(); */
       $response = Evaluacion::findByid($_GET['eid'],$_GET['cid']);
        break;
      case 'evento':
        $response = EventoAdmin::getEventoId($_GET['evento_id']);
        break;
    }

    break;
    case 'PUT':
      $response = Evaluacion::editar($data); 
      break;
}
echo json_encode($response);
