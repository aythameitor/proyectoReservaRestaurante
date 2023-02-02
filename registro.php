<?php
/**
 * Se encarga de realizar el registro de los usuarios
 * @author Aythami Miguel Cabrera Mayor
 * @category File
 * @throws PDOException
 * @uses /funciones/codificar.php
 * @uses /funciones/consultas.php
 */
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
        //valida que el campo sea un email, y en caso de serlo comprueba si hay post de admin y si tu idRol es de superadmin
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

require 'partes/header.php';

if (isset($mensajeExito)) {
    echo "<div class='container p-2'>
    <div class='row'>
        <div class='col-md-12'>
            <div class='alert alert-danger' role='alert'>
                 $mensajeExito
            </div>
        </div>
    </div>
</div>";
}
if (isset($mensajeFallo)) {
    echo "<div class='container p-2'>
    <div class='row'>
        <div class='col-md-12'>
            <div class='alert alert-danger' role='alert'>
                 $mensajeFallo 
            </div>
        </div>
    </div>
</div>";
}?>

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

<div class="container">
    <form action="" method="post">
    <h2 class="m-5">Registro</h2>
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
        <input type="submit" value="Enviar" /><br />
    </form>
</div>
<?php

require 'partes/footer.php';
?>