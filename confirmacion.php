<?php
session_name("reservaRestaurante");
session_start();
$config = include 'config.php';
include "funciones/consultas.php";
if(!isset($_SESSION["email"])){
    header("location:login.php");
    die();
}
$email = $_SESSION["email"];
require 'partes/header.php';
?>
<div>
    <p>
        <?php
        
        echo $_SESSION["email"] . " " .$_SESSION['idRol'];

        switch ($_SESSION["idRol"]) {
            case 1:
                echo " es un usuario";
                break;

            case 2:
                echo " es un admin";
                break;
            case 3:
                echo " es un superadmin";
                break;
        }
        ?>
    </p>
</div>
<?php

require 'partes/footer.php';
