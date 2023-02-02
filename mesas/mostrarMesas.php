<?php
/**
 * Se encarga de la visualización de las mesas y del manejo de las mismas solo para usuarios con el rol de administrador
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
include "../funciones/codificar.php";
//Como esta es una página pública, pero requiere registro, se comprueba que la sesión
//esté correctamente, de no ser el caso, el usuario sería mandado al índice
if (!isset($_SESSION["email"]) || !isset($_SESSION["idRol"])) {
    header("location:../index.php");
    die();
}
try {
    //Este if comprueba si vienes del método POST, si vienes con el mismo, significa que quieres añadir una mesa nueva
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //Se crea la conexión y se recoge y procesa el número de mesa enviado
        $conexion = conexion();
        $numeroMesa = codificarHTML(strip_tags(trim($_POST["numeroMesa"])));
        //comprueba que sea un número
        if (filter_var($numeroMesa, FILTER_VALIDATE_INT) == false) {
            $mensajeFallo = "Por favor introduce un número";
        } else {
            //Se realiza una consulta a la base de datos con tu email y se comprueba el resultado
            if (select("idRol", $_SESSION["email"], $conexion)->fetchColumn() >= 2) {
                $comprobacion = "SELECT numeroMesa FROM mesas WHERE numeroMesa = $numeroMesa";
                $consultaCompr = $conexion->prepare($comprobacion);
                $consultaCompr->execute();

                //Si la consulta devuelve 1 fila significa que ya existe una mesa con ese número, los cuales son únicos
                if ($consultaCompr->rowCount() == 1) {
                    $mensajeFallo = "Ya existe una mesa con ese número, por favor introduce otro";
                } else {
                    //y si no, se inserta la nueva mesa
                    $sql = "insert into mesas (numeroMesa) values (:numeroMesa)";
                    $consulta = $conexion->prepare($sql);
                    $consulta->bindParam(":numeroMesa", $numeroMesa, PDO::PARAM_STR);
                    $consulta->execute();
                    $mensajeExito = "Mesa añadida con éxito";
                }
            }
        }
    }
} catch (PDOException $error) {
    $error = $error->getMessage();
}
try {
    $conexion = conexion();

    //Selecciona el id del rol en base
    if (select("idRol", $_SESSION["email"], $conexion)->fetchColumn() >= 2) {
        //Comprueba si existe el id de la mesa a eliminar
        if (isset($_GET["eliminar"])) {
            $id = strip_tags(trim($_GET["eliminar"]));
            $eliminar = "DELETE FROM mesas WHERE idMesa=:idMesa";
            $borrar = $conexion->prepare($eliminar);
            $borrar->bindParam(":idMesa", $id, PDO::PARAM_INT);
            $borrar->execute();
        }
    };
    //Muestra las mesas ordenandolas por el número de mesa
    $consultaSQL = 'select * from mesas order by numeroMesa asc';
    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();
    $mesas = $sentencia->fetchAll();
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
    <?php
    //Si el idRol es superior a 1, significa que eres admin o superadmin por lo que puedes añadir mesa
    if ($_SESSION["idRol"] > 1) {
    ?>
        <div class="row">


            <form action="" method="post">
                <div class="row">
                    <label style="display:flex; align-items:center">Añadir mesa con número:</label>
                    <input class="m-3" type="text" name="numeroMesa" /><br /><br />

                    <input class="m-3" type="submit" value="submit" /><br />
                </div>

            </form>
        </div>
    <?php }
    ?>
    <div class="row">
        <div class="col-md-12">
            <h2 class="p-2">Lista de mesas</h2>
            <table class="table table-striped table-dark">
                <thead>
                    <tr>

                        <th>Número de mesa</th>
                        <?php
                        if ($_SESSION["idRol"] > 1) {

                        ?>
                            <th>Id</th>
                            <th>Eliminar</th>
                            <th>Editar</th>
                        <?php
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($mesas && $sentencia->rowCount() > 0) {
                        foreach ($mesas as $fila) {
                    ?>
                            <tr>

                                <td><?php echo $fila["numeroMesa"]; ?></td>
                                <?php if ($_SESSION["idRol"] > 1) {
                                ?>
                                    <td><?php echo $fila["idMesa"]; ?></td>
                                    <td>
                                        <a href="mostrarMesas.php?eliminar=<?php echo $fila["idMesa"]; ?>">Borrar</a>
                                    </td>
                                    <td>
                                        <a href="editarMesa.php?idMesa=<?php echo $fila["idMesa"]; ?>&numeroMesa=<?php echo $fila["numeroMesa"]; ?>">Editar</a>
                                    </td>
                                <?php } ?>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="row mb-5">
            <div class="col-md-12">
                <a href="../index.php" class="btn btn-primary">Volver al inicio</a>
            </div>
        </div>
    </div>
</div>
<?php include '../partes/footer.php' ?>