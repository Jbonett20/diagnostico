<?php
$method = $_SERVER["REQUEST_METHOD"];
$accion = $_POST['accion'] ?? null;
$imagen = "";
switch ($method) {
    case 'GET':
        include_once('models/Evento.php');
        $eventos = Evento::Listar();
        break;
        case 'POST':
            include_once('../models/Evento.php');
            if ($accion === 'crear') {
                if (!empty($_FILES['img']['tmp_name']) && is_uploaded_file($_FILES['img']['tmp_name'])) {
                    $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
                    if ($_FILES['img']['type'] === "image/jpg" || $_FILES['img']['type'] === "image/jpeg" || $_FILES['img']['type'] === "image/png") {
                        $imagen = round(microtime(true)) . '.' . $ext;
                        move_uploaded_file($_FILES['img']['tmp_name'], "../assets/imagen/".$imagen);
                    } else {
                       $response = ["success" => false, "message" => "La extensión de la imagen no es válida. Solo se permiten archivos JPG, JPEG y PNG."];
                    }
                } else {
                    $imagen = "evento.jpg";
                }
                $response = Evento::Crear($_POST, $imagen);
            }elseif($accion === 'eliminar') {
                $response=Evento::Eliminar($_POST['eventoid'],$_POST['estadoid']);
            }
            elseif($accion === 'consultaevento') {
                $response=Evento::consultareventosperfiles($_POST['eventoid']);
            }elseif($accion === 'activarEvento') {
                $response=Evento::activarEvento($_POST['eventoid']);
            }elseif($accion === 'activarEtapaEventos') {
                $response=Evento::activarEtapaEventos($_POST);
            }
       
        break;
    case 'PUT':
        
        
        break;
}
if(isset($response)&& !empty($response)){
    echo json_encode($response);
}

