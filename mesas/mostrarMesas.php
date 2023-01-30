<?php
session_name("reservaRestaurante");
session_start();
$error = false;
$config = include '../config.php';
include "../funciones/consultas.php";
if (!isset($_SESSION["email"]) || !isset($_SESSION["idRol"])) {
    header("location:../index.php");
    die();
}
try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $dsn = $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
        $numeroMesa = strip_tags(trim($_POST["numeroMesa"]));
        if (select("idRol", $_SESSION["email"], $conexion)->fetchColumn() < 2) {
            $comprobacion = "SELECT numeroMesa FROM mesas WHERE numeroMesa = $numeroMesa";
            $consultaCompr = $conexion->prepare($comprobacion);
            $consultaCompr->execute();
            if ($consultaCompr->rowCount() == 1) {
                $mensajeFallo = "Ya existe una mesa con ese número, por favor introduce otro";
            } else {
                $sql = "insert into mesas (numeroMesa) values (:numeroMesa)";
                $consulta = $conexion->prepare($sql);
                $consulta->bindParam(":numeroMesa", $numeroMesa, PDO::PARAM_STR);
                $consulta->execute();
                $mensajeExito = "Mesa añadida con éxito";
            }
        }
    }
} catch (PDOException $error) {
    $error = $error->getMessage();
}
try {
    $dsn = $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    if (select("idRol", $_SESSION["email"], $conexion)->fetchColumn() < 2) {
        if (isset($_GET["eliminar"])) {
            $id = strip_tags(trim($_GET["eliminar"]));
            $eliminar = "DELETE FROM mesas WHERE idMesa=:idMesa";
            $borrar = $conexion->prepare($eliminar);
            $borrar->bindParam(":idMesa", $id, PDO::PARAM_INT);
            $borrar->execute();
        }
    };
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
    echo "<p>" . $mensajeExito . "</p>";
}
if (isset($mensajeFallo)) {
    echo "<p>" . $mensajeFallo . "</p>";
}
if (isset($error)) {
    echo $error;
} ?>

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
    <?php if ($_SESSION["idRol"] > 1) {
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