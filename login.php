<?php
session_name("reservaRestaurante");
session_start();
include "funciones/consultas.php";

if(isset($_SESSION["email"])){
    header("location:index.php");
    die();
}
try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = strip_tags(trim($_POST["email"]));
        $contrasena = strip_tags(trim($_POST["contrasena"]));
        
        $conexion = conexion();
        $consulta = select("idUsuario", $email, $conexion);
        $idRol = select("idRol", $email, $conexion);
        $consultaConstrasena = select("contrasena", $email, $conexion);
        $cuenta = $consulta->rowCount();
        if ($cuenta == 1 && password_verify($contrasena, $consultaConstrasena->fetchColumn())) {
            $_SESSION['email'] = $email;
            $_SESSION['idRol'] = $idRol->fetchColumn();
            header("location:index.php");
            die();
        } else {
            $error = "El email o contraseña no son válidos";
        }
    }
} catch (PDOException $error) {
    $error = $error->getMessage();
}
require 'partes/header.php';
?>
<div class="container">
    <?php
if (isset($error)) {
    ?>
    <div class="container p-2">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?=$error?>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

    <form action="" method="post">
    <h2 class="m-5">Login</h2>
        <label>Usuario :</label><input type="text" name="email" /><br /><br />
        <label>Constraseña :</label><input type="password" name="contrasena" /><br /><br />
        <input type="submit" value="Enviar" /><br />
    </form>
</div>
<?php

require 'partes/footer.php';
?>