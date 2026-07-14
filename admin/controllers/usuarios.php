<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "../models/Usuarios.php";

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
$data = json_decode(file_get_contents("php://input"), true);
$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {
    case 'POST':
        switch ($data["operation"]) {
            case 'crear':
                $response = Usuario::save($data);
                break;
        }
        break;

    case 'PUT':
        switch ($_GET["operation"]) {
            case 'editar':
                $response = Usuario::edit($data);
                break;
            case 'restablecer':
                $response = Usuario::updateClave($data);
                break;
            case 'inactivar':
                $response = Usuario::inactivar($data);
                break;
        }
        break;

    case 'GET':
        switch ($_GET["operation"]) {
            case 'listar':
                $response = Usuario::All();
                break;
            case 'getByid':
                $response = Usuario::findById($_GET["id"]);
                break;
        }
        break;
}

echo json_encode($response);
