<style> 
a{
    color: #AA2B3E !important;   
}
.nav-second-level li > a
.active > a {
  color: #6e768e !important;}
  
#sidebar-menu > ul > li > a:hover, 
#sidebar-menu > ul > li > a:focus, 
#sidebar-menu > ul > li > a:active {
      color: #6e768e !important;
      text-decoration: none; }

.nav-second-level li a:focus, 
.nav-second-level li a:hover,
.nav-second-level li a:active{
    color: #6e768e !important;
    text-decoration: none; }



</style>
<div class="left-side-menu">
    <div class="h-100" data-simplebar>
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul id="side-menu">
                <li class="menu-title">Menu</li>
                <!-- <li>
                    <a href="#sidebarDashboards" data-toggle="collapse">
                        <i data-feather="airplay"></i>
                        <span class="badge badge-success badge-pill float-right">1</span>
                        <span> Resultados </span>
                    </a>
                    <div class="collapse" id="sidebarDashboards">
                        <ul class="nav-second-level">
                            <li>
                                <a href="home">Dashboard</a>
                            </li>
                        </ul>
                    </div>
                </li> -->
                <?php if($_SESSION["DIAGNOSTICOSALESCONTESTAUTOTRAIN"]['rolid'] == 1) { ?>
                <li>
                    <a href="#sidebarConfig" data-toggle="collapse">
                        <i data-feather="settings"></i>
                        <span> Configuraciones </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarConfig">
                        <ul class="nav-second-level">
                            <li>
                                <a href="usuario.php">Usuarios</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <?php } ?>
                <li>
                    <a href="#sidebarEvents" data-toggle="collapse">
                        <i data-feather="calendar"></i>
                        <span> Eventos </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarEvents">
                        <ul class="nav-second-level">
                           <?php if($_SESSION["DIAGNOSTICOSALESCONTESTAUTOTRAIN"]['rolid'] == 1) { ?>
                            <li>
                                <a href="eventos">Gestionar eventos</a>
                            </li>
                            <?php } ?>
                            <li>
                                <a href="index">Todos los eventos</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>
    </div>
    <!-- Sidebar -left -->
</div>


