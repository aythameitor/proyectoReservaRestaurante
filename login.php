<?php
session_name("reservaRestaurante");
session_start();
$config = include 'config.php';
include "funciones/consultas.php";

if(isset($_SESSION["email"])){
    header("location:index.php");
    die();
}
try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = strip_tags(trim($_POST["email"]));
        $contrasena = strip_tags(trim($_POST["contrasena"]));
        
        $dsn = $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
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
    if(isset($error)){
        echo "<p>" . $error . "</p>";
    }
    ?>
    <form action="" method="post">
        <label>UserName :</label><input type="text" name="email" /><br /><br />
        <label>Password :</label><input type="password" name="contrasena" /><br /><br />
        <input type="submit" value=" Submit " /><br />
    </form>
</div>
<?php

require 'partes/footer.php';
?>