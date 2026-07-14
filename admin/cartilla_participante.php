<?php 
session_start();
if(isset($_SESSION['DIAGNOSTICOSALESCONTESTAUTOTRAINALUMNO'])){
    $usuario = strtoupper($_SESSION['DIAGNOSTICOSALESCONTESTAUTOTRAINALUMNO']['nombres']). " ". strtoupper($_SESSION['DIAGNOSTICOSALESCONTESTAUTOTRAINALUMNO']['apellidos']);
}else{
    session_destroy();
    header("Location: login");
}
?>
<?php require("controllers/eventoId.php");?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <?php include("./src/head.php"); ?>
    <style>
        .authentication-bg {
            background-image: url('./assets/imagen/<?php echo $eventoId['img']; ?>');
            background-size: cover;
            background-position: center;
        }

        .card {
            background-color: #fff;
            border: 2px solid #fff;
            box-shadow: 0 4px 8px rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            opacity: 0.8;
        }

        .hamburger-menu {
            display: none;
        }

        @media (max-width: 770px) {
            .logout-button {
                display: none;
            }

            .hamburger-menu {
                display: block;
                color: #cdd4dc !important;
                font-size: 24px;
                cursor: pointer;
            }
        }
    </style>
</head>

<body class="authentication-bg" data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "sidebar": { "color": "light", "size": "default", "showuser": false}, "topbar": {"color": "dark"}, "showRightSidebarOnPageLoad": true}'>

    <!-- Begin page -->
    <div id="wrapper">

        <div class="navbar-custom">
            <div class="container-fluid">
                <ul class="list-unstyled topnav-menu float-right mb-0">

                    <li class="dropdown d-none d-lg-inline-block">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen" href="#">
                            <i class="fe-maximize noti-icon" style="color: #cdd4dc !important;"></i>
                        </a>
                    </li>

                    <li class="dropdown notification-list topbar-dropdown logout-button">
                        <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false" style="color: #cdd4dc !important;">
                            <span class="pro-user-name ml-1">
                                <?php echo $usuario ?> <i class="mdi mdi-chevron-down"></i>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown">
                            <a href="login_particapante.php?eventoid=<?php echo $eventoId['eventoid']; ?>" class="dropdown-item notify-item">
                                <i class="fe-log-out"></i>
                                <span>Salir</span>
                            </a>
                        </div>
                    </li>

                    <li class="hamburger-menu">
                        <button class="btn btn-light btn-xs btn-min" onclick="toggleMenu(<?php echo $eventoId['eventoid']; ?>)">Salir</button>   
                    </li>
                </ul>

                <!-- LOGO -->
                <div class="logo-box">
                    <a href="home" class="logo logo-dark text-center">
                        <span class="logo-sm">
                            <img src="./assets/logos/logo-autoTrain.png" alt="" height="10">
                        </span>
                        <span class="logo-lg">
                            <img src="./assets/logos/logo-autoTrain.png" alt="" height="20">
                        </span>
                    </a>

                    <a href="home" class="logo logo-light text-center">
                        <span class="logo-sm">
                            <img src="./assets/logos/logo-autoTrain.png" alt="Logo" height="10">
                        </span>
                        <span class="logo-lg">
                            <img src="./assets/logos/logo-autoTrain.png" alt="Logo" height="30">
                        </span>
                    </a>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <div class="content-page33">
            <div class="container">
                <!-- start page title -->
                <div class="row" style="display:flex; text-align: center;">
                    <div class="col-12">
                        <div class="page-title-box">
                            <h4 class="page-title" style="color:#fff !important">BIENVENIDO : <?php echo $usuario ?></h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <div class="row" style="justify-content: center;">
                    <div class="col-xl-8 col-md-8">
                        <!-- Portlet card -->
                        <div class="card">
                            <div class="card-body" style="background-color: #fff;">
                                <h3 class="card-title text-center" id="titulo-evento"></h3>

                                <div id="cardCollpase1" class="collapse pt-3 show">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-centered mb-0" id="tabla_cartila">
                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div> <!-- end collapse-->
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->
                </div>
            </div> <!-- content -->

            <!-- Footer Start -->
            <?php // include("./src/footer.php"); ?>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->
    <?php include("./src/script.php"); ?>

    <!-- Dashboard init-->
    <script type="module" type="module" src="js/loginParticipante.js"></script>
    <script>
        function toggleMenu(eventoid) {
            window.location.href = `login_particapante.php?eventoid=${eventoid}`;
        }
    </script>
</body>

</html>

