<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="./assets/logos/logo-autoTrain.png">
    <!-- App css -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link href="../assets/css/app.css" rel="stylesheet" type="text/css" id="app-default-stylesheet" />
    <link href="../assets/css/bootstrap-dark.min.css" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
    <link href="../assets/css/app-dark.min.css" rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled />
    <!-- icons -->
    <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
</head>

<body class="auth-fluid-pages pb-0 w-100 h-100">
    <div class="container-fluid m-0 p-0">
        <div class="row no-gutters">
            <div class="col-md-4 p-0">
                <div class="auth-fluid">
                    <div class="auth-fluid-form-box">
                        <div class="align-items-center d-flex h-100">
                            <div class="card-body">
                                <!-- Logo -->
                                <div class="auth-brand text-center text-lg-left">
                                    <div class="auth-logo">
                                        <a href="index.html" class="logo logo-dark text-center">
                                            <span class="logo-lg">
                                                <img src="./assets/logos/autotrain-logo.png" width="100%" height="70" style="  object-fit:fill;image-rendering: crisp-edges;image-rendering: pixelated; " >
                                            </span>
                                        </a>
                                        <a href="index.html" class="logo logo-light text-center">
                                            <span class="logo-lg">
                                                <img src="./assets/logos/autotrain-logo.png"  width="100%" alt="" height="70" style="  object-fit:fill;image-rendering: crisp-edges; image-rendering: pixelated; ">
                                            </span>
                                        </a>
                                    </div>
                                </div>
                                <br>
                                <!-- title-->
                                <h4 class="mt-0">Iniciar sesión</h4>
                                <p class="text-muted mb-4">Ingrese su dirección de correo electrónico y contraseña para acceder a la cuenta.</p>
                                <div id="notification-login"></div>
                                <!-- form -->
                                <form id="frmLogin">
                                    <div class="form-group">
                                        <label for="email" style=" text-shadow: 1px 1px 5px rgba(0, 0,0,0.1);">Correo Electrónico</label>
                                        <input class="form-control" type="email" name="email" id="email" required placeholder="Ingresa tu correo electrónico" style="box-shadow: 0px 10px 10px rgba(24, 23, 23, 0.1); ">
                                    </div>
                                    <div class="form-group">
                                        <a href="auth-recoverpw-2.html" class="text-muted float-right" style=" text-shadow: 2px 2px 5px rgba(0, 0,0,0.2);"><small>¿Olvidaste tu contraseña?</small></a>
                                        <label for="password" style=" text-shadow: 1px 1px 5px rgba(0, 0,0,0.1);">Contraseña</label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="password" name="password" class="form-control" placeholder="Ingresa tu contraseña" style="box-shadow: 0px 10px 10px rgba(24, 23, 23, 0.1); " required>
                                            <div class="input-group-append" data-password="false">
                                                <div class="input-group-text" style="box-shadow: 0px 10px 10px rgba(24, 23, 23, 0.2); ">
                                                    <span class="password-eye"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group d-none" id="form-group-otp">
                                        <label for="otp">Código Otp</label>
                                        <input class="form-control" type="text" name="otp" id="otp" required placeholder="Ingresa código OTP">
                                    </div>
                                    <div class="form-group mb-0 text-center">
                                        <button class="btn btn-block" type="button" id="frmLoginButton" style="background-color: #AA2B3E;color:#FFF;box-shadow: 0px 10px 10px rgba(24, 23, 23, 0.1); ">Ingresar </button>
                                    </div>
                                </form>
                                <!-- end form-->
                            </div> <!-- end .card-body -->
                        </div> <!-- end .align-items-center.d-flex.h-100-->
                    </div>
                    <!-- end auth-fluid-form-box-->
                </div>
            </div>
            <div class="d-none d-md-block d-lg-block col-md-8 ">
             <img src="../assets/images/bg-auth.jpg" class="auth-fluid-imagen"  alt="">
            </div>
        </div>
        <!-- fin de row -->
    </div>
    <!-- end auth-fluid-->

    <!-- Vendor js -->
    <script src="../assets/js/vendor.min.js"></script>
    <!-- App js -->
    <script src="../assets/js/app.min.js"></script>
    <script type="module" src="js/login.js"></script>
</body>

</html>
