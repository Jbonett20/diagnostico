<?php require("controllers/eventoId.php");?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Coming Soon | UBold - Responsive Admin Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="./assets/logos/logo-autoTrain.png">

    <!-- App css -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <!-- <link href="../assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-default-stylesheet" />
 -->
    <link href="../assets/css/bootstrap-dark.min.css" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
    <link href="../assets/css/app-dark.min.css" rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled />

    <!-- icons -->
    <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css" />


    <style>
        .counter-number {
            color: red !important;
        }
        .authentication-bg {
            background-image: url('./assets/imagen/<?php echo $eventoId['img']; ?>');
            background-size: cover;
            background-position: center;
        }
   /*   .card-body{
        opacity: 0.8;
     }
 */
   
    </style>

</head>

<body class="authentication-bg" id="exam-container">

    <div class="mt-5 mb-5">
        <div class="container" >
            <div class="row justify-content-center">
                <div class="col-md-8" style="border-radius: 30px;">


                    <div class="card-body" >
                        <div class="row mt-5 justify-content-center">

                            <div class="col-md-12">
                                <div>
                                    <h2 class="header-title text-center" id="tituloEvaluacion">
                                        </h2>
                                </div>

                            </div> <!-- end col-->
                        </div> <!-- end row-->
                        <div class="clearfix"></div>
                        <div class="card-box">

                            <form id="frm-evaluacion">

                                <div id="question-container">
                                    <!-- ===== pregunta unica respuesta ===== -->
                                    <div class="form-group" id="cuestionarioid">


                                    </div>


                                </div>


                                <div class="form-group mb-0 d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary" id="btn_next_question" style="border-radius:5px;">Siguiente</button>

                                </div>

                            </form>
                        </div>

                    </div>
                </div> <!-- end /.text-center-->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt">
        
    </footer>
    <!-- Vendor js -->
    <script src="../assets/js/vendor.min.js"></script>

    <!-- Plugins js-->
    <script src="../assets/libs/jquery-countdown/jquery.countdown.min.js"></script>
    <!-- Incluye SweetAlert2 desde un CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Countdown js -->
    <script src="../assets/js/pages/coming-soon.init.js"></script>
    <!-- App js -->
    <script src="../assets/js/app.min.js"></script>
    <script src="js/evaluacion.js" type="module"></script>

</body>

</html>