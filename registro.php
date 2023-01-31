<?php
session_name("reservaRestaurante");
session_start();

include "funciones/consultas.php";
include "funciones/codificar.php";
try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = codificarHTML(strip_tags(trim($_POST["email"])));
        $contrasena = password_hash($_POST["contrasena"], PASSWORD_DEFAULT);
        $conexion = conexion();
        $consultaComprobacion = select("idUsuario", $email, $conexion);
        if(filter_var($email, FILTER_VALIDATE_EMAIL) == false){
            $mensajeFallo = "Por favor, introduce un email válido";
        } else{
            if($consultaComprobacion->rowCount() == 0){
                if(isset($_POST['admin']) && $_POST["admin"] == "valido" && select("idRol", $_SESSION["email"], $conexion)->fetchColumn()==3){
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
        }
    } else if(isset($_SESSION["email"])){
        $email = $_SESSION["email"];
        $conexion = conexion();
        $idRol = $_SESSION["idRol"];
        if($idRol == 1){
            header("location:index.php");
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