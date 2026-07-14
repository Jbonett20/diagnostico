<?php require("./src/seguridad.php"); ?>
<?php require("controllers/categoria.php");
/*  echo '<pre>';
print_r($perfiles);
echo '</pre>';
die()   */
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <?php include("./src/head.php"); ?>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.11.0/dist/sweetalert2.min.css" rel="stylesheet">
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
                            <div class="page-title-box">
                                <h4 class="page-title">Creación de las etapas por perfil</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <?php
                                    $primerPerfil = false;
                                    ?>
                                    <div class="accordion" id="accordionExample">
                                        <?php
                                        if (isset($perfiles) && !empty($perfiles)) {
                                            foreach ($perfiles as $key => $value) {
                                                $perfilNombre = $value['nombre'];
                                                if ($value['bono'] == 1) {
                                                    $perfilNombre .= ' - BONO';
                                                } ?>
                                                <div class="accordion-item mb-2 w-100">
                                                    <h2 class="accordion-header" id="heading<?php echo $key; ?>">
                                                        <form id="listarCategoria<?php echo $key; ?>">
                                                            <input type="hidden" name="perfilid" value="<?php echo $value['perfilid']; ?>">
                                                            <input type="hidden" name="eventoId" value="<?php if (isset($_GET['eventoId']) && !empty($_GET['eventoId'])) {
                                                                                                            echo $_GET['eventoId'];
                                                                                                        } ?>">
                                                            <button class="accordion-button col-12 btn btn-secondary<?php if (!$primerPerfil) echo ' collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $key; ?>" aria-expanded="<?php echo $primerPerfil ? 'true' : 'false'; ?>" aria-controls="collapse<?php echo $key; ?>" onclick="listarCategoriaKey(<?php echo $key; ?>)">
                                                                <?php echo $perfilNombre; ?>
                                                            </button>
                                                        </form>
                                                    </h2>
                                                    <div id="collapse<?php echo $key; ?>" class="accordion-collapse collapse<?php if ($primerPerfil) echo ' show'; ?>" aria-labelledby="heading<?php echo $key; ?>" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                                <li class="nav-item" role="presentation">
                                                                    <button class="nav-link active" id="home-tab<?php echo $value['perfilid']; ?>" data-bs-toggle="tab" data-bs-target="#categoria<?php echo $value['perfilid']; ?>" type="button" role="tab" aria-controls="categoria" aria-selected="true" onclick="hideActividades(<?php echo $value['perfilid']; ?>)">Crear etapas</button>
                                                                </li>
                                                                <li class="nav-item" role="presentation">
                                                                    <button class="nav-link" id="actividad-tab<?php echo $value['perfilid']; ?>" data-bs-toggle="tab" data-bs-target="#actividad<?php echo $value['perfilid']; ?>" type="button" role="tab" aria-controls="actividad" aria-selected="false" style="display:none;">Categorias</button>
                                                                </li>
                                                                <li class="nav-item" role="presentation">
                                                                    <button class="nav-link carga-masiva" id="nav-carga-masiva-tab<?php echo $value['perfilid']; ?>" data-bs-toggle="tab" data-bs-target="#carga-masiva<?php echo $value['perfilid']; ?>" type="button" role="tab" aria-controls="carga-masiva" aria-selected="false" style="cursor: pointer;">Cargar participantes</button>
                                                                </li>
                                                            </ul>
                                                            <div class="tab-content" id="myTabContent">
                                                                <div class="tab-pane fade show active" id="categoria<?php echo $value['perfilid']; ?>" role="tabpanel" aria-labelledby="home-tab<?php echo $value['perfilid']; ?>">
                                                                    <form class="needs row g-3" name="category-form" id="form-category-<?php echo $key; ?>">
                                                                        <input type="hidden" class="form-control" id="eventoid" name="eventoid" value="<?php if (isset($_GET['eventoId']) && !empty($_GET['eventoId'])) {
                                                                                                                                                            echo $_GET['eventoId'];
                                                                                                                                                        } ?>">
                                                                        <input type="hidden" class="form-control" id="perfilid" name="perfilid" value="<?php echo $value['perfilid']; ?>">
                                                                        <div class="col-md-3">
                                                                            <label for="nombreCategoria" class="form-label">Nombre etapa</label>
                                                                            <input type="text" class="form-control" id="nombreCategoria" name="nombreCategoria" placeholder="Crear etapa" required>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="porcentaje" class="form-label">Porcentaje</label>
                                                                            <input type="number" class="form-control" id="porcentaje" name="porcentaje" placeholder="Ingrese el valor del porcentaje" required>
                                                                            <div class="porcentaje-message text-danger"></div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label for="tipoetapa" class="form-label">Tipo</label>
                                                                            <select class="custom-select" id="tipoetapa" name="tipoetapa" required>
                                                                                <option value="" selected disabled>Tipo</option>
                                                                                <option value="Calificacion">Calificación</option>
                                                                                <option value="Responder">Responder</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-3 align-self-end">
                                                                            <button type="submit" class="btn btn-secondary" id="btn-save-categoria">Crear</button>
                                                                        </div>
                                                                    </form>
                                                                    <div class="table-responsive">
                                                                        <table class="table mt-2">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Nombre</th>
                                                                                    <th>Porcentaje</th>
                                                                                    <th>Tipo</th>
                                                                                    <th>Opciones</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="categoriaTableBody-<?php echo $value['perfilid']; ?>">
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane fade" id="actividad<?php echo $value['perfilid']; ?>" role="tabpanel" aria-labelledby="nav-actividad-tab">
                                                                    <!-- Tarjeta para los Formularios -->
                                                                    <div class="card mb-3">
                                                                        <div class="card-body">
                                                                            <form class="needs-validation row g-3" name="activity-form" id="activity-form<?php echo $value['perfilid']; ?>">
                                                                                <input class="form-control" type="hidden" id="calificacion<?php echo $value['perfilid']; ?>" name="calificacion" readonly>
                                                                                <div class="col-12">
                                                                                    <p id="categoria-nom<?php echo $value['perfilid']; ?>"></p>
                                                                                    <input type="hidden" class="form-control" id="categoriaid" name="categoriaid">
                                                                                </div>

                                                                                <div class="col-md-4">
                                                                                    <label for="nombre<?php echo $value['perfilid']; ?>" class="form-label">Nombre de la categoría</label>
                                                                                    <input type="text" class="form-control" id="nombre<?php echo $value['perfilid']; ?>" name="nombre" placeholder="Nombre de la categoría" required>
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <label for="valor<?php echo $value['perfilid']; ?>" class="form-label">Valor de la categoría</label>
                                                                                    <input type="number" class="form-control" id="valor<?php echo $value['perfilid']; ?>" name="valor" placeholder="Valor de la categoría" required>
                                                                                    <p id="valor-error<?php echo $value['perfilid']; ?>" class="text-danger" style="display:none;">El valor debe estar entre 1 y 100 puntos.</p>
                                                                                </div>
                                                                                <div class="col-4 d-flex align-items-end justify-content-end">
                                                                                    <button type="button" class="btn btn-secondary" id="btn-save-actividad" data-form-id="<?php echo $value['perfilid']; ?>">Crear categoria</button>
                                                                                </div>
                                                                            </form>



                                                                            <hr> <!-- Línea separadora -->

                                                                            <h4>Agregue las preguntas a la categoria seleccionada:</h4>
                                                                            <form class="needs row g-3" name="question-form" id="form-question<?php echo $value['perfilid']; ?>">
                                                                                <div class="col-12 mb-3">
                                                                                    <select class="custom-select" id="actividades<?php echo $value['perfilid']; ?>" name="actividades" required>
                                                                                        <option value="">Seleccione la categoria</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-12 mb-3">
                                                                                    <input type="text" class="form-control" id="pregunta" name="pregunta" placeholder="Pregunta" required>
                                                                                </div>
                                                                                <div class="col-12 mb-3">
                                                                                    <div class="row">
                                                                                        <div class="col-md-6 mb-3">
                                                                                            <input type="number" class="form-control" id="valor-pregunta<?php echo $value['perfilid']; ?>" name="valor-pregunta" placeholder="Valor de la pregunta" required>
                                                                                            <p id="valor-error-preg<?php echo $value['perfilid']; ?>" class="text-danger" style="display:none;">El valor debe estar entre 1 y 100 puntos.</p>
                                                                                        </div>
                                                                                        <div class="col-md-6 mb-3">
                                                                                            <select class="custom-select" id="tipo-pregunta<?php echo $value['perfilid']; ?>" name="tipo-pregunta" required>
                                                                                                <option value="" selected disabled>Seleccione el tipo de pregunta</option>
                                                                                                <option value="única respuesta">Única respuesta</option>
                                                                                                <option value="opción múltiple">Opción múltiple</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <!-- solo debe aparecer si es de tipo responder -->
                                                                                        <div class="col-12 mb-3" id="caja-respuesta<?php echo $value['perfilid']; ?>" style="display: none" ;>
                                                                                           <h4>Agregar las opciones de respuestas </h4>

                                                                                            <input type="hidden" id="rowCoun<?php echo $value['perfilid']; ?>">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 mb-3">
                                                                                                <label for="tipoetapa" class="form-label">Opción </label>
                                                                                                    <input id="np<?php echo $value['perfilid']; ?>0" name="np<?php echo $value['perfilid']; ?>0" type="text" placeholder="Respuesta" class="form-control">
                                                                                                </div>
                                                                                                <div class="col-md-5 mb-3">
                                                                                                <label for="tipoetapa" class="form-label">Valor </label>
                                                                                                    <input id="cant<?php echo $value['perfilid']; ?>0" name="cant<?php echo $value['perfilid']; ?>0" type="number" placeholder="Valor respuesta" class="form-control">
                                                                                                </div>
                                                                                                <div class="col-md-1 mb-3 d-flex align-items-end justify-content-end">
                                                                                                    <button type="button" class="auto btn btn-secondary" id="btn-add-row<?php echo $value['perfilid']; ?>" onclick="addNewRow(<?php echo $value['perfilid']; ?>)" style="cursor: pointer;">
                                                                                                        <i class="fa fa-plus-circle"></i>
                                                                                                    </button>
                                                                                                </div>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 text-right mt-3">
                                                                                    <button type="button" class="btn btn-secondary" id="btn-save-question" data-form-id="<?php echo $value['perfilid']; ?>">Crear pregunta</button>
                                                                                </div>
                                                                            </form>
                                                                            <hr> <!-- Línea separadora -->
                                                                        </div>
                                                                    </div>

                                                                    <!-- Tarjeta para el Datatable -->
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <h4>Listado de categoria creadas:</h4>
                                                                            <div class="table-responsive">
                                                                                <table class="table mt-2">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Categoria</th>
                                                                                            <th>Valor</th>
                                                                                            <th>Tipo</th>
                                                                                            <th>Cant-Preguntas</th>
                                                                                            <th>Cant-Respuestas</th>
                                                                                            <th>Opciones</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody id="actividadTableBody-<?php echo $value['perfilid']; ?>">
                                                                                    </tbody>
                                                                                </table>
                                                                                <div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                </div>
                                                                <div class="tab-pane fade" id="carga-masiva<?php echo $value['perfilid']; ?>" role="tabpanel" aria-labelledby="nav-carga-masiva-tab<?php echo $value['perfilid']; ?>">
                                                                    <div class="card mb-3">
                                                                        <div class="card-body">
                                                                            <div class="descargar_plantilla" style="float: right;">
                                                                                <a href="assets/estrctura_cargamasiva/cargaMasivaUsuariosEventos.xlsx">
                                                                                    <button type="button" class="btn btn-secondary waves-effect btn-xs btn-min" data-dismiss="modal"><i class="fas fa-file-excel"></i> Descargar Plantilla</button>
                                                                                </a>
                                                                            </div>
                                                                            <h4>Carga Masiva de Datos</h4>
                                                                            <div class="row align-items-center">
                                                                                <form id="frm_cargar_participantes<?php echo $value['perfilid']; ?>" name="frm_cargar_participantes" class="col-md-6">
                                                                                    <input type="hidden" id="evenId" name="evenId" value="<?php if (isset($_GET['eventoId']) && !empty($_GET['eventoId'])) {
                                                                                                                                                echo $_GET['eventoId'];
                                                                                                                                            } ?>">
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <div class="form-group">
                                                                                                <label for="archivo" class="control-label">Cargar Archivo</label>
                                                                                                <input class="form-control" type="file" id="archivo" name="archivo">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>
                                                                                <div class="modal-footer" class="col-md-6" style="margin-top: 12px;">

                                                                                    <?php
                                                                                    $perfilIdActual = $value['perfilid'];
                                                                                    $usuariosTotales = $allUsuarios;
                                                                                    $perfilTieneUsuarios = false;

                                                                                    // Verificar si el perfil actual tiene usuarios
                                                                                    foreach ($usuariosTotales as $usuario) {
                                                                                        if ($usuario['perfilid'] == $perfilIdActual) {
                                                                                            $perfilTieneUsuarios = true;
                                                                                            break;
                                                                                        }
                                                                                    }

                                                                                    // Mostrar el enlace y el botón "Ver Participantes" solo si el perfil actual tiene usuarios
                                                                                    if ($perfilTieneUsuarios) { ?>
                                                                                        <a target="_black" href="participantes.php?eventoid=<?php if (isset($_GET['eventoId']) && !empty($_GET['eventoId'])) {
                                                                                                                                                echo $_GET['eventoId'];
                                                                                                                                            } ?>&perfilid=<?php echo $value['perfilid']; ?>"> <button type="button" id="btn_ver_participantes" class="btn btn-secondary waves-effect waves-light btn-xs btn-min" data-form-id="<?php echo $value['perfilid']; ?>">Ver Participantes</button> </a>
                                                                                    <?php }
                                                                                    ?>
                                                                                    <a target="_black" href="participantes.php?eventoid=<?php if (isset($_GET['eventoId']) && !empty($_GET['eventoId'])) {
                                                                                                                                            echo $_GET['eventoId'];
                                                                                                                                        } ?>&perfilid=<?php echo $value['perfilid']; ?>">
                                                                                        <button type="button" id="btn_ver_participantes2" class="btn btn-secondary waves-effect waves-light btn-xs btn-min" data-form-id="<?php echo $value['perfilid']; ?>" style="display: none;">Ver Participantes</button>
                                                                                    </a>

                                                                                    <button type="submit" id="btn_cargar_participantes" class="btn btn-secondary waves-effect waves-light btn-xs btn-min" data-form-id="<?php echo $value['perfilid']; ?>">Cargar Participantes</button>
                                                                                </div>
                                                                            </div>

                                                                            <div id="carga-masiva-mensaje-<?php echo $value['perfilid']; ?>" class="mt-3"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php
                                                $primerPerfil = false; // Cambia la variable después del primer perfil
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php include("./modales/modalactividades.php"); ?>
                    <?php include("./modales/modalCategoria.php"); ?>
                    <?php include("./src/footer.php"); ?>
                </div>
                <?php include("./src/script.php"); ?>
                <script src="./js/categoria.js"></script>
            </div>
        </div>
    </div>
</body>

</html>