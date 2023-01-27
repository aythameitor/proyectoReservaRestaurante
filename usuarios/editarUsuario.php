<?php
session_name("reservaRestaurante");
session_start();
$error = false;
$config = include '../config.php';
include "../funciones/consultas.php";
if(!isset($_SESSION["email"]) || !isset($_SESSION["idRol"])){
    header("location:../login.php");
    die();
}
try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $emailPre = strip_tags(trim($_POST["emailPre"]));
        $emailPost = strip_tags(trim($_POST["emailPost"]));
        $contrasenaPre = strip_tags(trim($_POST["contrasenaPre"]));
        $contrasenaPost = strip_tags(trim($_POST["contrasenaPost"]));
        
        $dsn = $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

        $sqlId = "select idUsuario from usuarios where email = :email";
        $consultaSqlId = $conexion->prepare($sqlId);
        $consultaSqlId->bindParam(":email", $emailPre);
        $consultaSqlId->execute();

        $idUsuario = $consultaSqlId->fetchColumn();

        echo $idUsuario;
        $comprobacion = "SELECT email FROM usuarios WHERE email = :email";
        $consultaCompr = $conexion->prepare($comprobacion);
        $consultaCompr->bindParam(":email", $emailPost);
        $consultaCompr->execute();
        if($consultaCompr->rowCount() == 1){
            $mensajeFallo = "ese email ya existe, por favor, elige otro";
        } else{
            echo "b";
            $sql = "update usuarios set email = :emailPost, actualizado = null, contrasena = :contrasenaPost where idUsuario = :idUsuario";
            $consulta = $conexion->prepare($sql);
            echo "b";
            $consulta->bindParam(":emailPost", $emailPost);
            $consulta->bindParam(":contrasenaPost", $contrasenaPost);
            $consulta->bindParam(":idUsuario", $idUsuario);
            $consulta->execute();
            if($consulta->rowCount() == 1){
                $_SESSION["email"] = $emailPost;
                $mensajeExito = "Usuario modificado con éxito";
            } else{
                $mensajeFallo = "No se ha podido modificar el usuario, ha ocurrido un error inesperado";
            }
            
        }
        
    }
} catch (PDOException $error) {
    $error = $error->getMessage();
}

?>

<?php include '../partes/header.php';

if(isset($mensajeExito)){
    echo "<p>" . $mensajeExito . "</p>";
}
if(isset($mensajeFallo)){
    echo "<p>" . $mensajeFallo . "</p>";
}
if(isset($error)){
    echo $error;
}?>

<?php
    if($error){
?>
<div class="container p-2">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
                <?= $error ?>
            </div>
        </div>
    </div>
</div>
<?php
    }
?>

<div>
<form action="" method="post">
    <div class="form-group">
        <label>Email antiguo:</label>
        <input type="text" name="emailPre"/>
    </div>
    <div class="form-group">
        <label>Email nuevo:</label>
        <input type="text" name="emailPost"/>
    </div>
    <div class="form-group">
        <label>Contraseña antigua:</label>
        <input type="text" name="contrasenaPre"/>
    </div>
    <div class="form-group">
        <label>Contraseña nueva:</label>
        <input type="text" name="contrasenaPost"/>
    </div>
         <br /><br />
        <input type="submit" value="submit" /><br />
    </form>
</div>

<?php include '../partes/footer.php'?>