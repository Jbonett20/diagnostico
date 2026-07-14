<?php require("./src/seguridad.php"); ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <?php include("./src/head.php"); ?>
    <style>
        .action-icon{
            cursor: pointer !important;
        }
        .page-item.active .page-link{
            border-color: #5a6268 !important;
            background-color: #5a6268 !important;
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
                            <h4 class="page-title">Listado Participantes</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <!-- Portlet card -->
                            <div class="card">
                                <div class="card-body" dir="ltr">

                                    <div id="cardCollpase1" class="collapse pt-3 show">
                                       
                                        <?php include_once "./src/usuarios/participantes.php" ; ?>

                                    </div> <!-- end collapse-->
                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div> <!-- end col-->


                    </div>

                </div> <!-- container -->


            <?php include_once("./src/usuarios/modal_editar_participantes.php") ?>

            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include("./src/footer.php"); ?>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->
    <?php include("./src/script.php"); ?>
    <!-- Dashboard init-->
    <script type="module" type="module" src="js/participantes.js"></script>

</body>

</html>