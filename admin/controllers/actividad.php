<?php
$method = $_SERVER["REQUEST_METHOD"];
$accion = $_POST['accion'] ?? null;
$imagen = "";
switch ($method) {
    case 'GET':

        break;
    case 'POST':
        include_once('../models/Actividad.php');
        if ($accion === 'listarActividades') {
            $response = Actividad::Listar($_POST['categoriaid']);
        } elseif ($accion === 'crear') {
            $nombre = $_POST['nombre'];
            $valor = $_POST['valor'];
            $calificacion = $_POST['calificacion'];
            $categoriaid = $_POST['categoriaid'];

            $response = Actividad::Crear($nombre, $valor, $calificacion, $categoriaid);
        } elseif ($accion === 'crearPregunta') {
            $response = Actividad::CrearPreguntas($_POST);
        } elseif ($accion === 'veractividad') {
            $response = Actividad::VerActividad($_POST['actividadid']);
        } elseif ($accion === 'EliminarActividad') {
            $actividadid = $_POST['actividadid'];
            $categoriaid =$_POST['categoriaid'];
            $response = Actividad::Eliminar($actividadid,$categoriaid);
        } 
        break;
}
if (isset($response) && !empty($response)) {
    echo json_encode($response);
}
?>
