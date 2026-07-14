<?php require("controllers/eventoId.php");
session_start();
 session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Log In | UBold - Responsive Admin Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="./assets/logos/logo-autoTrain.png">

		<!-- App css -->
		<link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
		<!-- <link href="../assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-default-stylesheet" /> -->

		<link href="../assets/css/bootstrap-dark.min.css" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
		<link href="../assets/css/app-dark.min.css" rel="stylesheet" type="text/css" id="app-dark-stylesheet"  disabled />

		<!-- icons -->
		<link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<style>
  
        .authentication-bg {
            background-image: url('./assets/imagen/<?php echo $eventoId['img']; ?>');
            background-size: cover;
            background-position: center;
        }

</style>
    </head>

    <body class="authentication-bg authentication-bg-pattern">

        <div class="account-pages mt-5 mb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-pattern">

                            <div class="card-body p-4">
                                
                                <div class="text-center w-75 m-auto">
                                    <div class="auth-logo">
                                        <a href="index.html" class="logo logo-dark text-center">
                                            <span class="logo-lg">
                                            <img src="./assets/logos/logo-autoTrain.png" alt="" height="22">
                                            </span>
                                        </a>
                    
                                        <a href="index.html" class="logo logo-light text-center">
                                            <span class="logo-lg">
                                                <img src="../assets/images/logo-light.png" alt="" height="22">
                                            </span>
                                        </a>
                                    </div>
                                    <p class="text-muted mb-4 mt-3">Ingrese su usuario y clave para iniciar sesión.</p>
                                    <div id="notification-login-participante"></div>
                                </div>

                                <form id="frm-login-participante">

                                    <div class="form-group mb-3">
                                        <label for="emailaddress">Usuario(Identificación)</label>
                                        <input class="form-control" type="text" id="txt_usuario" name="txt_usuario" required="" placeholder="Ingrese su usuario">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="password">Clave(Identificación)</label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="txt_clave" name="txt_clave" class="form-control" placeholder="Ingrese clave">
                                            <div class="input-group-append" data-password="false">
                                                <div class="input-group-text">
                                                    <span class="password-eye"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-0 text-center">
                                        <button  id="btn-login-participantes"  class="btn btn-secondary btn-block" type="submit"> Ingresar </button>
                                    </div>

                                </form>

                               

                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                               <!--  <p> <a href="auth-recoverpw.html" class="text-white-50 ml-1">Forgot your password?</a></p> -->
                               
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->

                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->


        <footer class="footer footer-alt">
           <!--  2015 - <script>document.write(new Date().getFullYear())</script> &copy; UBold theme by <a href="" class="text-white-50">Coderthemes</a>  -->
        </footer>

        <!-- Vendor js -->
        <script src="../assets/js/vendor.min.js"></script>

        <!-- App js -->
        <script src="../assets/js/app.min.js"></script>
        <script type="module" src="js/loginParticipante.js"></script>
        
    </body>
</html>