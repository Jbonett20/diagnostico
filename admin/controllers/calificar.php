<?php
$method = $_SERVER['REQUEST_METHOD'];
header('Content-Type: application/json');
switch ($method) {
    case 'POST':

        switch ($_POST['opcn']) {
            case 'cargar':
                include_once('../models/Calificar.php');
                $respuesta = Calificar::listarPerfiles($_POST);
                echo json_encode($respuesta);
                break;

            case 'titulo':
                include_once('../models/Calificar.php');
                $respuesta = Calificar::listarTitulo($_POST);
                echo json_encode($respuesta);
                break;



            case 'participantes':
                include_once('../models/Calificar.php');
                $respuesta = Calificar::Participantes($_POST);
                echo json_encode($respuesta);
                break;



            case 'Etapas':
                include_once('../models/Calificar.php');
                $respuesta = Calificar::Etapas($_POST);
                echo json_encode($respuesta);
                break;



            case 'preguntas':
                include_once('../models/Calificar.php');
                $respuesta = Calificar::Preguntas($_POST);
                echo json_encode($respuesta);
                break;

            case 'carga_pregunta':
                include_once('../models/Calificar.php');
                
                // json_decode porque viene como string
                $_POST['respuestasPreguntas'] = json_decode($_POST['respuestasPreguntas'], true);
                
                // Enviar también archivo y observación
                $_POST['observacion'] = $_POST['observacion'] ?? '';
                $_POST['archivo_nombre'] = $_FILES['archivo']['name'] ?? '';
                $_POST['archivo_tmp'] = $_FILES['archivo']['tmp_name'] ?? '';

                $respuesta = Calificar::Carga_pregunta($_POST);
                echo json_encode($respuesta);
                break;

        }
}
