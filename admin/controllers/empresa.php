<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "../models/Empresa.php";

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
$data = json_decode(file_get_contents("php://input"), true);
$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {
    case 'POST':
        switch ($data["operation"]) {
            case 'crear':
                $response = Empresa::save($data);
                break;
        }
        break;

    case 'PUT':
        switch ($data["operation"]) {
            case 'editar':
                $response = Empresa::edit($data);
                break;
        }
        break;

    case 'GET':
        switch ($_GET["operation"]) {
            case 'listar':
                $response = Empresa::All();
                break;
            case 'listarSelect':
                $response = Empresa::AllSelect();
                break;
            case 'findById':
                $response = Empresa::findById($_GET["registroid"]);
                break;
        }
        break;
}

echo json_encode($response);
