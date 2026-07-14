<?php require("./src/seguridad.php"); ?>
<?php require("controllers/evento.php");
/* echo '<pre>';
print_r($eventos);
echo '</pre>';
die()   */
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="assets/css/eventos.css">
    <?php include("./src/head.php"); ?>
    <style>
        .default-color {
            background-color: #D3D3D3;
        }
        .page-item.active .page-link {
            color: white !important;
            background-color: gray !important;
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
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Eventos</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Card for Data Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card ">
                                <div class="col-12 text-sm-center form-inline">
                                    <div class="form-group m-3">
                                        <h4 class="header-title">En esta sección están los distintos eventos </h4>
                                    </div>
                                </div>
                                <div class="card-body table-responsive">
                                    <table id="basic-datatable" class="nowrap w-100">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Foto</th>
                                                <th>Evento</th>
                                                <th>Fecha Inicio</th>
                                                <th>Fecha Fin</th>
                                                <th>Estado evento</th>
                                                <th>Etapas</th>
                                                <th>Opciones</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php if (isset($eventos) && !empty($eventos)) {
                                                foreach ($eventos as $key => $value) { ?>
                                                    <tr class="evento-row" data-estadoid="<?php echo $value['estadoid']; ?>">
                                                        <td style="width: 36px;">
                                                            <img src="assets/imagen/<?php echo $value['img']; ?>" alt="contact-img" title="contact-img" class="rounded-circle avatar-sm">
                                                        </td>
                                                        <td class="text-center"><?php echo $value['nombre']; ?></td>
                                                        <td class="text-center"><?php echo $value['fechainicio']; ?></td>
                                                        <td class="text-center"><?php echo $value['fechafin']; ?></td>

                                                        <td class="text-center">
                                                            <?php
                                                            if ($value['estadoid'] == 1) {
                                                                echo '<span class="badge label-table badge-secondary">Activo</span>';
                                                            } elseif ($value['estadoid'] == 2) {
                                                                echo '<span class="badge label-table badge-danger">Cancelado</span>';
                                                            } elseif ($value['estadoid'] == 3) {
                                                                echo '<span class="badge label-table badge-secondary">En proceso</span>';
                                                            } elseif ($value['estadoid'] == 0) {
                                                                echo '<span class="badge label-table badge-secondary">Inactivo</span>';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <button class="btn btn-sm btn-secondary btn-xs btn-min" onclick="ActivarEvento(<?php echo $value['eventoid'] ?>)">Activar etapas</button>
                                                        </td>

                                                        <td>
                                                            <div class="d-flex justify-content-center">
                                                                <?php
                                                                if ($value['estadoid'] == 3) { ?>
                                                                    <span class="action-icon" onclick="editarEvento(<?php echo $value['eventoid'] ?>)"><i class="fas fa-pencil-alt" style="color:grey"></i></span>
                                                                    <span class="action-icon" onclick="eliminarEvento(<?php echo $value['eventoid'] . ',' . $value['estadoid']; ?>)"><i class="fas fa-trash-alt" style="color:#f1556c"></i></span>
                                                                <?php } else if ($value['estadoid'] == 0) { ?>
                                                                    <span class="action-icon" onclick="eliminarEvento(<?php echo $value['eventoid'] . ',' . $value['estadoid']; ?>)"><i class="fas fa-trash-alt" style="color:#f1556c"></i></span>
                                                                <?php } else if ($value['estadoid'] == 1) { ?>
                                                                    <span title="Ver resultado" class="action-icon" onclick="verEvento(<?php echo $value['eventoid'] ?>)"><i class="fas fa-eye" style="color:grey"></i></span>
                                                                    <span title="Calificar instructor" class="action-icon" onclick="redirigirCalificar(<?php echo $value['eventoid']; ?>)">
                                                                        <i class="fas fa-edit" style="color:grey"></i>
                                                                    </span>
                                                                     <span title="evaluación participantes" class="action-icon" onclick="copiarLink(<?php echo $value['eventoid']; ?>)">
                                                                        <i class="fas fa-link" style="color:grey"></i>
                                                                    </span> 
                                                                <?php } ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>

                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div><!-- end col-->
                    </div><!-- end row -->

                </div> <!-- container -->

            </div> <!-- content -->
            <!-- Footer Start -->
            <?php include("./src/footer.php"); ?>
            <!-- end Footer -->
        </div>
    </div> <!-- END wrapper -->
    <?php include("./src/script.php"); ?>
    <?php include("./src/eventosAdmin/modal_activar_etapas.php") ?>
    <!-- Bootstrap JS -->
    <script src="./js/listarevento.js"></script>
</body>

</html>