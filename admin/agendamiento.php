<?php require("controllers/eventoId.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Inicio contenido administrador</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="../assets/images/favicon.ico">

    <!-- App css -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link href="../assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

    <link href="../assets/css/bootstrap-dark.min.css" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
    <link href="../assets/css/app-dark.min.css" rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled />

    <!-- icons -->
    <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- css personalizado -->
    <link href="assets/css/agendamiento.css" rel="stylesheet" type="text/css" />
</head>

<body class="authentication-bg">
    <div class="mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">


                    <div class="text-center">
                        <!-- Titulo del Evento -->
                        <div id="nombre-evento"></div>
                        <!-- Contenido Tabla  -->
                        <?php include_once("./src/agendamiento/contenido.php") ?>
                    </div> <!-- end /.text-center-->
                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <div id="add_user" class="modal" tabindex="-1" role="dialog" style="display: none;">
        <div id="modal-add-body"></div>
    </div>


    <!-- end page -->
    <!-- App js -->
    <script src="../assets/js/vendor.min.js"></script>
    <script src="../assets/js/app.min.js"></script>
    <script src="js/agendamiento.js" type="module"></script>

</body>

</html>