<?php require("./src/seguridad.php"); ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <?php include("./src/head.php"); ?>
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
                                <h4 class="page-title">Usuarios</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card-box">
                                <div class="card-header d-flex align-items-center" style="gap: 20px;">
                                    <h4>Listado de usuarios</h4>
                                    <button type="button" class="btn btn-secondary btn-xs" data-toggle="modal" data-target="#modalAgregar">Crear</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-borderless table-nowrap table-hover table-centered m-0" id="tablaDatos">

                                        <thead class="thead-light">
                                            <tr>
                                                <th>NOMBRES</th>
                                                <th>APELLIDOS</th>
                                                <th>CORREO</th>
                                                <th>IDENTIFICACIÓN</th>
                                                <th>TELÉFONO</th>
                                                <th>ROL</th>
                                                <th>EMPRESA</th>
                                                <th>ESTADO</th>
                                                <th>OPCIONES</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div> <!-- end .table-responsive-->
                            </div> <!-- end card-box-->
                        </div>
                    </div>
                    <!-- end row -->

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include("./src/footer.php"); ?>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>

    <!-- modal crear -->
    <div id="modalAgregar" class="modal fade" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="btnCrear">Crea Usuario</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form id="frmCrear">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input class="form-control" type="text" name="nombres" id="nombres" required placeholder="Nombres">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Apellidos</label>
                                    <input class="form-control" type="text" name="apellidos" id="apellidos" required placeholder="Apellidos">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Email</label>
                                    <input class="form-control" type="text" name="correo" id="correo" required placeholder="Email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Teléfono</label>
                                    <input class="form-control" type="text" name="telefono" id="telefono" required placeholder="Teléfono">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Identificación</label>
                                    <input class="form-control" type="text" name="identificacion" id="identificacion" required placeholder="Identificación">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Rol</label>
                                    <select name="rol" id="rol" class="form-control cs-form_field" required>
                                        <option value="">Tipo documento</option>
                                        <option value="">Tipo documento</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Empresa</label>
                                    <select name="empresaid" id="empresaid" class="form-control cs-form_field" required>
                                        <option value="">Empresa 1</option>
                                        <option value="">Empresa 2</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex align-items-end justify-content-end">
                    <button type="button" class="btn btn-secondary btn-xs btn-min" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnCrear" class="btn btn-secondary btn-xs btn-min">Crear</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- modal editar -->
    <div id="modalEditar" class="modal fade" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="btnCrear">Editar</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                <form id="frmEditar">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input class="form-control" type="text" name="nombres" id="nombresEdit" required placeholder="Nombres">
                                    <input class="form-control" type="hidden" name="usuarioid" id="usuarioidEdit">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Apellidos</label>
                                    <input class="form-control" type="text" name="apellidos" id="apellidosEdit" required placeholder="Apellidos">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Email</label>
                                    <input class="form-control" type="text" name="correo" id="correoEdit" required placeholder="Email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Teléfono</label>
                                    <input class="form-control" type="text" name="telefono" id="telefonoEdit" required placeholder="Teléfono">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Identificación</label>
                                    <input class="form-control" type="text" name="identificacion" id="identificacionEdit" required placeholder="Identificación">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Rol</label>
                                    <select name="rol" id="rolEdit" class="form-control cs-form_field" required>
                                        <option value="">Tipo documento</option>
                                        <option value="">Tipo documento</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Empresa</label>
                                    <select name="empresaid" id="empresaidEdit" class="form-control cs-form_field" required>
                                        <option value="">Empresa 1</option>
                                        <option value="">Empresa 2</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-lg-between">
                    <button type="button" class="btn btn-xs btn-info" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnEditar" class="btn btn-xs btn-primary">Editar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- END wrapper -->
    <?php include("./src/script.php"); ?>
    <script type="module" src="js/usuarios.js"></script>

</body>

</html>