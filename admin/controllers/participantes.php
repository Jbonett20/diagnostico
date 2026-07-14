<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "../models/Participnates.php";

// Incluir el autoloader de Composer
require '../../vendor/autoload.php';

use Shuchkin\SimpleXLSX;

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
$data = json_decode(file_get_contents("php://input"), true);

$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {

    case 'POST':
        switch ($_POST["opcn"]) {
            case 'cargaPartcicipante':
                $xlsx = SimpleXLSX::parse($_FILES['archivo']['tmp_name']);
                $idEvento = $_POST["eventoid"];
                $idPerfil = $_POST["perfilid"];
                /*  print_r($xlsx->rowsEx());
                die();  */
                $contador = 0;
                $p = [];
                $data = [];

                if (!empty($xlsx->rowsEx())) {
                    foreach ($xlsx->rowsEx() as $data) {
                        if ($contador > 0 && !empty($data[1]['value'])) {
                            $p['identificacion']    =              $data[0]['value'];
                            $p['nombres']           =              $data[1]['value'];
                            $p['apellidos']         =              $data[2]['value'];
                            $p['empresa']           =              $data[3]['value'];
                            $p['bono']          =                  $data[4]['value'];

                            /* se pasan los usuarios al modelo*/
                            $res = Participantes::add($p, $idEvento, $idPerfil);
                        }
                        $contador++;
                    }
                    $response = $res;
                }


             break;
        }
        break;

    case 'GET':
        
        switch ($_GET['opcn']) {
            case 'getAll':
                $response = Participantes::getAll($_GET['eventoid'], $_GET['perfilid']);
                break;
            case 'findById':
                $response = Participantes::findById($_GET['id']);
                    break;
        }
        break;

    case 'PUT':
            switch ($data["opcn"]) {
                case 'activar':
                    $response = Participantes::inactivar($data["id"]);
                    break;
                case 'editarP':
                   
                    $response = Participantes::update($data);
                    break;
            }
        break;
}

echo json_encode($response);
