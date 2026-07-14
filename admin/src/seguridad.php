<?php
@session_start();

if (!isset($_SESSION["DIAGNOSTICOSALESCONTESTAUTOTRAIN"]["usuarioid"])) {
    session_destroy();
    header("Location: login");
}
