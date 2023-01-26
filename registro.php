<?php
session_name("reservaRestaurante");
session_start();
$config = include 'config.php';
include "funciones/consultas.php";
try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = strip_tags(trim($_POST["email"]));
        $contrasena = password_hash($_POST["contrasena"], PASSWORD_DEFAULT);
        $dsn = $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
        $consultaComprobacion = select("idUsuario", $email, $conexion);
        
        if($consultaComprobacion->rowCount() == 0){
            if(isset($_POST['admin']) && $_POST["admin"] == "valido" && $_SESSION["idRol"]==3){
                $sql = "insert into usuarios (email, contrasena, idRol) values (:email, :contrasena, 2)";
                $consulta = $conexion->prepare($sql);
                $consulta->bindParam(":email", $email, PDO::PARAM_STR);
                $consulta->bindParam(":contrasena", $contrasena, PDO::PARAM_STR);
                $consulta->execute();
                $mensajeExito = "Usuario creado con éxito";
            }else{
                $sql = "insert into usuarios (email, contrasena) values (:email, :contrasena)";
                $consulta = $conexion->prepare($sql);
                $consulta->bindParam(":email", $email, PDO::PARAM_STR);
                $consulta->bindParam(":contrasena", $contrasena, PDO::PARAM_STR);
                $consulta->execute();
                $mensajeExito = "Usuario creado con éxito";
            }
            
        } else{
            $mensajeFallo = "El email ya existe";
        }
    } else if(isset($_SESSION["email"])){
        $email = $_SESSION["email"];
        $dsn = $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
        $idRol = $_SESSION["idRol"];
        if($idRol == 1){
            header("location:confirmacion.php");
            die();
        }
    }
} catch (PDOException $error) {
    $error = $error->getMessage();
}

if(isset($mensajeExito)){
    echo "<p>" . $mensajeExito . "</p>";
}
if(isset($mensajeFallo)){
    echo "<p>" . $mensajeFallo . "</p>";
}
if(isset($error)){
    echo $error;
}
require 'partes/header.php';
?>
<div class="container">
    <form action="" method="post">
        <label>Usuario:</label><input type="text" name="email" /><br /><br />
        <label>Contraseña:</label><input type="password" name="contrasena" /><br /><br />
        <?php
        if(isset($_SESSION["idRol"])){
            
            if ($_SESSION["idRol"] == 3) {
                ?>
                <label for="admin">Admin:</label>
                <input type="checkbox" name="admin" value="valido"><br>
            <?php
            }
        }
           
        ?>
        <input type="submit" value=" Submit " /><br />
    </form>
</div>
<?php

require 'partes/footer.php';
?>