<?php
session_name("reservaRestaurante");
session_start();
$error = false;
$config = include '../config.php';
include "../funciones/consultas.php";
if (!isset($_SESSION["email"]) || !isset($_SESSION["idRol"]) || $_SESSION["idRol"] < 2) {
    header("location:../login.php");
    die();
}

try {
    $dsn = $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    //Comprueba si eres administrador
    if(select("idRol", $_SESSION["email"], $conexion)->fetchColumn()<2){
        header("location:../login.php");
        die();
    }
    if (isset($_GET["reserva"]) && $_SESSION["idRol"] >= 2) {
        //Realiza la consulta de eliminar
        $eliminar = "DELETE FROM reservas WHERE fechaReserva=:fechaReserva AND idMesa=:idMesa";
        $borrar = $conexion->prepare($eliminar);
        $varReserva = strip_tags(trim($_GET["reserva"]));
        $varIdMesa = strip_tags(trim($_GET["idReserva"]));
        $borrar->bindParam(":fechaReserva", $varReserva);
        $borrar->bindParam(":idMesa", $varIdMesa);
        $borrar->execute();
    }

    //Recoge el total de mesas y las ordenas por fecha
    $consultaSQL = 'select * from reservas order by fechaReserva DESC';
    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();
    $reservas = $sentencia->fetchAll();
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


<div>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>fecha de reserva</th>
                <th>Nombre de usuario</th>
                <th>numero de mesa</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($reservas && $sentencia->rowCount() > 0) {
                foreach ($reservas as $fila) {
            ?>
                    <tr>
                        <td><?php echo $fila["fechaReserva"]; ?></td>
                        <td>
                            <?php
                            $usuario = "select email from usuarios where idUsuario = :idUsuario";
                            $consultaUsuario = $conexion->prepare($usuario);
                            $consultaUsuario->bindParam(":idUsuario", $fila["idUsuario"]);
                            $consultaUsuario->execute();
                            $nombreUsuario = $consultaUsuario->fetchColumn();
                            echo $nombreUsuario;
                            ?>
                        </td>


                        <td>
                            <?php
                            $mesa = "select numeroMesa from mesas where idMesa = :idMesa";
                            $consultaMesa = $conexion->prepare($mesa);
                            $consultaMesa->bindParam(":idMesa", $fila["idMesa"]);
                            $consultaMesa->execute();
                            $nombreMesa = $consultaMesa->fetchColumn();
                            echo $nombreMesa;
                            ?>
                        </td>
                        <td>
                            <a href="verReservasMesas.php?reserva=<?= $fila["fechaReserva"] ?>&idReserva=<?= $fila["idMesa"] ?>">Eliminar</a>
                        </td>
                    </tr>
            <?php
                }
            }


            ?>
        </tbody>
    </table>
</div>

<?php include '../partes/footer.php' ?>