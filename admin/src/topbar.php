<div class="navbar-custom">
    <div class="container-fluid">
        <ul class="list-unstyled topnav-menu float-right mb-0">

            <li class="dropdown d-none d-lg-inline-block">
                <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen" href="#">
                    <i class="fe-maximize noti-icon" style="color: #cdd4dc !important;"></i>
                </a>
            </li>

            <li class="dropdown notification-list topbar-dropdown">
                <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false" style="color: #cdd4dc !important;">
                    <!-- <img src="../assets/images/users/user-1.jpg" alt="user-image" class="rounded-circle"> -->
                    <span class="pro-user-name ml-1">
                        <?php echo strtoupper($_SESSION["DIAGNOSTICOSALESCONTESTAUTOTRAIN"]["nombres"].' '.$_SESSION["DIAGNOSTICOSALESCONTESTAUTOTRAIN"]["apellidos"]) ?> <i class="mdi mdi-chevron-down"></i>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    <!-- item-->
                  <!--   <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Bienvenido!</h6>
                    </div> -->

                    <!-- item-->
             <!--        <a href="#" class="dropdown-item notify-item">
                        <i class="fe-user"></i>
                        <span>Mi perfil</span>
                    </a> -->
                    <!-- item-->
                    <a href="salir.php" class="dropdown-item notify-item">
                        <i class="fe-log-out"></i>
                        <span>Salir</span>
                    </a>

                </div>
            </li>


        </ul>

        <!-- LOGO -->
        <div class="logo-box">
            <a href="home" class="logo logo-dark text-center">
                <span class="logo-sm">
                    <img src="./assets/logos/logo-autoTrain.png" alt="" height="1-">
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

        <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
            <li>
                <button class="button-menu-mobile waves-effect waves-light">
                    <i class="fe-menu"></i>
                </button>
            </li>

            <li>
                <!-- Mobile menu toggle (Horizontal Layout)-->
                <a class="navbar-toggle nav-link" data-toggle="collapse" data-target="#topnav-menu-content">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
                <!-- End mobile menu toggle-->
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
</div>