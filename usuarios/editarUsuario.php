<?php
/**
 * Se encarga de la edición del usuario personal de cada uno
 * @author Aythami Miguel Cabrera Mayor
 * @category File
 * @throws PDOException
 * @uses /funciones/codificar.php
 * @uses /funciones/consultas.php
 */
session_name("reservaRestaurante");
session_start();
$error = false;

include "../funciones/consultas.php";
if (!isset($_SESSION["email"]) || !isset($_SESSION["idRol"])) {
    header("location:../login.php");
    die();
}

try {
    if (isset($_POST["submit"])) {
        $emailPre = strip_tags(trim($_POST["emailPre"]));
        $emailPost = strip_tags(trim($_POST["emailPost"]));
        $contrasenaPre = strip_tags(trim($_POST["contrasenaPre"]));
        $contrasenaPost = password_hash(strip_tags(trim($_POST["contrasenaPost"])), PASSWORD_DEFAULT);

        //Inicio conexión
        $conexion = conexion();

        //validación del antiguo email y confirmación de que los campos no están vacíos
        if (filter_var($emailPre, FILTER_VALIDATE_EMAIL) == false || $emailPre == "" || $contrasenaPre == "" || $contrasenaPost == "") {
            $mensajeFallo = "Asegúrate de haber introducido datos válidos en los campos";
        } else {
            //Se recoge el id para su posterior uso
            $consultaSqlId = select("idUsuario", $emailPre, $conexion);
            $idUsuario = $consultaSqlId->fetchColumn();

            //Se recoge la contraseña para su posterior uso
            $consultaConstrasena = select("contrasena", $emailPre, $conexion);

            //Si $emailPost está vacío, el email se mantiene igual, se realiza comprobación de la contraseña y se le asigna la nueva
            if ($emailPost == "") {
                if (password_verify($contrasenaPre, $consultaConstrasena->fetchColumn())) {
                    $sql = "update usuarios set actualizado = null, contrasena = :contrasenaPost where idUsuario = :idUsuario";
                    $consulta = $conexion->prepare($sql);
                    $consulta->bindParam(":contrasenaPost", $contrasenaPost);
                    $consulta->bindParam(":idUsuario", $idUsuario);
                    $consulta->execute();
                    if ($consulta->rowCount() == 1) {
                        $mensajeExito = "Usuario modificado con éxito";
                    } else {
                        $mensajeFallo = "No se ha podido modificar el usuario, ha ocurrido un error inesperado";
                    }
                } else {
                    $mensajeFallo = "La contraseña anterior no coincide con la almacenada";
                }
            } else {
                $comprobacion = "SELECT email FROM usuarios WHERE email = :email";
                $consultaCompr = $conexion->prepare($comprobacion);
                $consultaCompr->bindParam(":email", $emailPost);
                $consultaCompr->execute();
                //validación del nuevo email
                if (filter_var($emailPost, FILTER_VALIDATE_EMAIL) == false) {
                    $mensajeFallo = "Asegúrate de introducir un nuevo email válido";
                } else {
                    if ($consultaCompr->rowCount() == 1) {
                        $mensajeFallo = "ese email ya existe, por favor, elige otro";
                    } else {
                        if (password_verify($contrasenaPre, $consultaConstrasena->fetchColumn())) {
                            $sql = "update usuarios set email = :emailPost, actualizado = null, contrasena = :contrasenaPost where idUsuario = :idUsuario";
                            $consulta = $conexion->prepare($sql);
                            $consulta->bindParam(":emailPost", $emailPost);
                            $consulta->bindParam(":contrasenaPost", $contrasenaPost);
                            $consulta->bindParam(":idUsuario", $idUsuario);
                            $consulta->execute();
                            if ($consulta->rowCount() == 1) {
                                $_SESSION["email"] = $emailPost;
                                $mensajeExito = "Usuario modificado con éxito";
                            } else {
                                $mensajeFallo = "No se ha podido modificar el usuario, ha ocurrido un error inesperado";
                            }
                        } else {
                            $mensajeFallo = "La contraseña anterior no coincide con la almacenada";
                        }
                    }
                }
            }
        }
    }
} catch (PDOException $error) {
    $error = $error->getMessage();
}

?>

<?php include '../partes/header.php';


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
if ($error) {
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


<?php
if ($error) {
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

<div class="container">
    <form action="" method="post">
        <h2 class="m-5">Edita tu cuenta:</h2>
        <div class="form-group">
            <label>Email antiguo:</label>
            <input type="text" name="emailPre" value="<?= $_SESSION["email"] ?>" />
        </div>
        <div class="form-group">
            <label>Email nuevo:</label>
            <input type="text" name="emailPost" />
        </div>
        <div class="form-group">
            <label>Contraseña antigua:</label>
            <input type="password" name="contrasenaPre" />
        </div>
        <div class="form-group">
            <label>Contraseña nueva:</label>
            <input type="password" name="contrasenaPost" />
        </div>
        <br /><br />
        <input type="submit" value="Enviar" name="submit" /><br />
    </form>
</div>

<?php include '../partes/footer.php' ?>