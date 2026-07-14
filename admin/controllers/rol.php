<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "../models/Rol.php";

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
$data = json_decode(file_get_contents("php://input"), true);
$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {

    case 'GET':
        switch ($_GET["operation"]) {
            case 'listar':
                $response = Empresa::All();
                break;
            case 'listarSelect':
                $response = Rol::AllSelect();
                break;
        }
        break;
}

echo json_encode($response);
