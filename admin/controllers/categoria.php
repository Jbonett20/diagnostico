<?php
$method = $_SERVER["REQUEST_METHOD"];
$accion = $_POST['accion'] ?? null;
$imagen = "";
switch ($method) {
    case 'GET':
        include_once('./models/Categoria.php');
        $perfiles= CategoriaEvento::Listar($_GET['eventoId']);
        $allUsuarios= CategoriaEvento::participantes($_GET['eventoId']);
        break;
    case 'POST':
        include_once('../models/Categoria.php');
        if ($accion === 'crear') {
            $response = CategoriaEvento::Crear($_POST);
        }elseif($accion === 'listarCategorias') {
            $categoria["datos"]= CategoriaEvento::ListarCategoria($_POST['eventoId'],$_POST['perfilid']);
            $response=$categoria;
        }elseif($accion === 'editar') {
            $response= CategoriaEvento::EditarCategoria($_POST);
            
        }elseif ($accion === 'eliminar') {
            $response = CategoriaEvento::Eliminar($_POST);
        }
      
        break;
}
if (isset($response) && !empty($response)) {
    echo json_encode($response);
}