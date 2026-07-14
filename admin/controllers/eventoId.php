<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "models/EventoId.php";
/* header("Content-Type: application/json"); */
$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {

    case 'GET':
     $evenid= $_GET["eventoid"];
     $eventoId =  EventoId::ListarEventoId($evenid);
     break;
}

