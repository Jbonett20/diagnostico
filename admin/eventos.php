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
                                <h4 class="page-title">Eventos y Perfiles</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- Card for Tabs -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="mb-1">
                                    <!-- inicio row -->
                                    <div class="row">
                                        <!--  <div class="col-12 text-sm-center form-inline">
                                            <div class="form-group m-3">
                                                <h4 class="header-title">Crear eventos </h4>
                                            </div>
                                        </div> -->
                                    </div>
                                    <!-- fin row -->
                                </div>
                                <!-- inicio btn crear evento -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-12">
                                                    <ul class="nav nav-tabs nav-pills nav-fill" id="myTab" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" id="evento-tab"  data-toggle="tab" href="#evento" role="tab" aria-controls="evento" aria-selected="true">Eventos</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="perfil-tab" data-toggle="tab" href="#perfil" role="tab" aria-controls="perfil" aria-selected="false">Perfiles</a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content" id="myTabContent">
                                                        <div class="tab-pane fade show active" id="evento" role="tabpanel" aria-labelledby="evento-tab">
                                                            <form class="needs-validation" name="event-form" id="form-event" novalidate>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Nombre del evento</label>
                                                                            <input class="form-control" placeholder="Ingrese nombre del evento" type="text" name="nombre" id="nombre" required />
                                                                            <div class="invalid-feedback">Por favor ingrese un nombre para el evento</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Imagen del evento</label>
                                                                            <input class="form-control" type="file" name="img" id="img" accept="image/*" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Color del evento</label>
                                                                            <input class="form-control" type="color" name="color" id="color" value="#D3D3D3" required />
                                                                            <div class="invalid-feedback">Escoja el color del evento</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Fecha de inicio</label>
                                                                            <input class="form-control" type="date" name="fechainicio" id="fechainicio" required />
                                                                            <div class="invalid-feedback">Ingrese una fecha</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="control-label">Fecha de fin</label>
                                                                            <input class="form-control" type="date" name="fechafin" id="fechafin" required />
                                                                            <div class="invalid-feedback">Ingrese una fecha</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-2">
                                                                    <div class="col-12 text-right" style="margin-bottom: 8px;">
                                                                        <button type="submit" class="btn btn-secondary" id="btn-save-event">Crear</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="tab-pane fade" id="perfil" role="tabpanel" aria-labelledby="perfil-tab">
                                                            <form class="needs row g-3" name="profile-form" id="form-profile" novalidate>
                                                                <div class="col-4">
                                                                    <select class="form-control select2" id="selectEventoId" name="selectEventoId" required>
                                                                        <option value="" selected disabled>Seleccione el evento</option>
                                                                        <?php
                                                                        if (isset($eventos) && !empty($eventos)) {
                                                                            foreach ($eventos as $key => $value) { ?>
                                                                                <option value="<?php echo $value['eventoid']; ?>"><?php echo $value['nombre']; ?></option>
                                                                            <?php }
                                                                        } else { ?>
                                                                            <option value="" selected disabled>No hay eventos para seleccionar</option>
                                                                        <?php } ?>
                                                                    </select>

                                                                </div>
                                                                <div class="col-4">
                                                                    <input type="text" class="form-control" id="nombrePerfil" name="nombrePerfil" placeholder="Crear perfil" required>
                                                                </div>

                                                                <div class="col-1">
                                                                    <button type="submit" class="btn btn-secondary" id="btn-save-profile">Crear</button>
                                                                </div>


                                                            </form>
                                                            <div class="table-responsive">
                                                            <table class="table mt-3">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Nombre</th>
                                                                        <th>Bono</th>
                                                                        <th>Fecha de creación</th>
                                                                        <th>Opción</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="perfilTableBody">
                                                                </tbody>
                                                            </table>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-12 d-flex justify-content-end" style="margin-bottom: 8px;">
                                                                    <button type="button" class="btn btn-secondary btn-xs btn-min" id="btn-next" style="display: none;"><i class="fa fa-cog" aria-hidden="true"></i> Configurar etapas de los perfiles</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div> <!-- end col -->
                    </div> <!-- end row -->

                </div> <!-- container -->

            </div> <!-- content -->
            <!-- Footer Start -->
            <?php include("./src/footer.php"); ?>
            <!-- end Footer -->
        </div>
    </div> <!-- END wrapper -->
    <?php include("./src/script.php"); ?>
    <!-- Bootstrap JS -->
    <script src="./js/evento.js"></script>
</body>

</html>