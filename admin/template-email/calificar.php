<?php require("./src/seguridad.php"); ?>
<!-- <?php require("controllers/categoria.php");
        /* echo '<pre>';
print_r($perfiles);
echo '</pre>';
die()  */

        ?> -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <?php include("./src/head.php"); ?>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .color {
            background-color: #ab2b3e;
        }

        .form-inline .form-group {
            display: flex;
            align-items: center;
        }

        .form-inline .form-group label {
            margin-right: 10px;
        }

        .form-inline .form-group select {
            margin-left: 10px;
        }

        .form-group.mx-sm-3.mb-2 {
            margin-bottom: 0 !important;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .card-header button {
            color: black;
            font-weight: bold;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header .details {
            display: flex;
            flex-direction: column;
        }

        .actividad-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            max-width: 60%;
            /* Ajusta el margen entre los elementos */
            padding: 10px;
            /* Espaciado interno */
            border: 1px solid #ddd;
            /* Contorno del elemento */
            border-radius: 4px;
            /* Bordes redondeados */
            background-color: #f9f9f9;
            /* Fondo ligero para mejor visibilidad */
        }

        .actividad-text {
            flex-grow: 1;
            margin-right: 10px;
            /* Ajusta el espacio entre el texto y el botón */
        }

        .responder-button {
            white-space: nowrap;
            /* Asegura que el texto del botón no se divida en varias líneas */
        }

        .modal-content.custom-modal-content {
            border-radius: 15px;
            /* Redondea todos los bordes del contenido de la modal */
        }

        .modal-header.custom-modal-header {
            width: 80%;
            background-color: #afafaf;
            border-radius: 15px;
            /* Redondea todos los bordes de la cabecera */
            margin: 20px auto 0 auto;
            /* Centra el encabezado horizontalmente y ajusta la distancia con la parte superior */
            padding: 5px;
            /* Hace el encabezado más pequeño */
            text-align: center;
            /* Centra el texto dentro del encabezado */
            border: 2px solid black;

        }

        .modal-title {
            color: black;
            font-size: 1.5rem;
            /* Aumenta el tamaño de la letra */
            margin: 0;
            padding: 10px 0;
            /* Ajusta el padding superior e inferior */
        }

        .modal-body {
            padding: 20px;
            /* Ajusta el padding del cuerpo de la modal */
        }

        .form-group.row {
            display: flex;
            align-items: center;
        }

        .col-form-label {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .form-control-sm {
            display: inline-block;
            width: auto;
        }

        .profile-info {
            display: flex;
            align-items: center;
        }

        .profile-image {
            width: 50px; 
            height: 50px; 
            border-radius: 50%; 
            margin-right: 15px;
            }

        @media (max-width: 768px) {
            .profile-info {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .profile-image {
                margin-bottom: 10px;
                width: 75px; 
                height: 75px; 
            }

            .form-inline {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .form-inline .form-group {
                margin-right: 0;
                margin-bottom: 1rem;
                width: 100%;
                justify-content: center;
            }

            .form-inline .form-group label,
            .form-inline .form-group select,
            .form-inline .form-group button {
                width: 100%;
                text-align: center;
            }

            .form-inline .form-group label {
                margin-bottom: 0.5rem;
            }

            .form-inline .form-group select,
            .form-inline .form-group button {
                max-width: 300px;
            }

            .actividad-container {
                max-width: 100%;
                flex-direction: column;
            }

            .actividad-text {
                margin-bottom: 10px;
                /* Space between text and button on small screens */
            }
        }
    </style>
</head>

<body data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "sidebar": { "color": "light", "size": "default", "showuser": false}, "topbar": {"color": "dark"}, "showRightSidebarOnPageLoad": true}'>
    <!-- Begin page -->
    <div id="wrapper">
        <!-- Topbar Start -->
        <?php include("./src/topbar.php"); ?>
        <!-- end Topbar -->
        <!-- ========== Left Sidebar Start ========== -->
        <?php include("./src/sidebar.php"); ?>


        <div class="content-page">
            <div class="content">
                <!-- Start Content-->
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box" style="margin-top: 5px;">
                                <!-- <h4 class="page-title">Sele Contest 2024</h4> -->
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="container">
                        <div class="row justify-content-center">
                            <form class="form-inline">
                                <div class="form-group" style="margin-right: 5px;">
                                    <label for="perfil">Seleccione Perfil:</label>
                                    <select id="perfil" name="perfil" class="form-control ml-2" required>
                                        <option value="">--Seleccione--</option>
                                        <!-- Agrega más opciones según tus necesidades -->
                                    </select>
                                </div>
                                <div class="form-group" style="margin-right: 15px;">
                                    <label for="participante">Seleccione Participante:</label>
                                    <select id="participante" name="participante" class="form-control ml-2" required>
                                        <option value="">--Seleccione--</option>
                                        <!-- Agrega más opciones según tus necesidades preference -->
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="button" onclick="llenarEtapas()" class="btn btn-secondary">Buscar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- END wrapper -->
                    <div id="yourContainerId">

                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="actividadModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
                        <div class="modal-dialog" role="document" style="background-color: #5e5e5e !important; padding: 10px 20px 30px 20px !important;">
                            <div class="modal-content">
                                <div class="modal-header custom-modal-header">
                                    <h5 class="modal-title text-center w-100" id="modalTitle"></h5>
                                    <!-- <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button> -->
                                </div>
                                <!-- <div class="modal-body" id="modalContent">
                                    
                                </div> -->
                                <div class="modal-body" style="padding: 30px;">
                                    <input type="hidden" id="actividadId" name="actividadId" value="">
                                    <form id="modalForm" style="padding: 10px;">

                                        <!-- Aquí se insertará el contenido del formulario -->
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php include("./src/script.php"); ?>

                    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                    <script src="./js/calificar.js"></script>

</body>

</html>