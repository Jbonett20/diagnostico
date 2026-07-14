<?php
$method = $_SERVER["REQUEST_METHOD"];
$accion = $_POST['accion'] ?? null;
$imagen = "";
switch ($method) {
    case 'GET':
       
        break;
    case 'POST':
        include_once('../models/perfilEvento.php');
        if ($accion === 'crear') {
            $response = PerfilEvento::Crear($_POST);

        } elseif ($accion === 'editar') {
            $response = PerfilEvento::Editar($_POST);
        }
        elseif ($accion === 'eliminar') {
            $response = PerfilEvento::Eliminar($_POST);
        }
        elseif ($accion === 'perfilevento') {
            $response = PerfilEvento::perfilevento($_POST['selectedEventId']);
        }
        break;
}
if (isset($response) && !empty($response)) {
    echo json_encode($response);
}


