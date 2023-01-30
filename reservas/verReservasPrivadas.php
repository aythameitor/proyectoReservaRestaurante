<?php
session_name("reservaRestaurante");
session_start();
$error = false;
$config = include '../config.php';
include "../funciones/consultas.php";
if (!isset($_SESSION["email"]) || !isset($_SESSION["idRol"])) {
    header("location:../login.php");
    die();
}

try {
    $dsn = $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    //Recoge el id del usuario para luego usarlo    
    $idUsuarioSQL = 'select idUsuario from usuarios where email = :email';
    $consultaId = $conexion->prepare($idUsuarioSQL);
    $consultaId->bindParam(":email", $_SESSION["email"]);
    $consultaId->execute();
    $idUsuario = $consultaId->fetchColumn();

    //Comprueba si hay GETS de reserva e idReserva, si los hubiera significa que el botón de eliminar de las
    //reservas ha sido pulsado
    if(isset($_GET["reserva"]) && isset($_GET["idReserva"])){
        $reserva = strip_tags(trim($_GET["reserva"]));
        $idMesa = strip_tags(trim($_GET["idReserva"]));
        $id = $consultaId->fetchColumn();

        //Se recoge el id de usuario de la reserva 
        $comprId = "SELECT idUsuario from reservas where fechaReserva=:fechaReserva AND idMesa=:idMesa ";
        $consultaCompr = $conexion->prepare($comprId);
        $consultaCompr->bindParam(":idMesa", $idMesa);
        $consultaCompr->bindParam(":fechaReserva", $reserva);
        $consultaCompr->execute();
        $idUsuarioReserva = $consultaCompr->fetchColumn();
        
        //Se comprueban ambos Ids para añadir seguridad
        if($idUsuario == $idUsuarioReserva){
            //Una vez ambos están comprobados se borra
            $eliminar = "DELETE FROM reservas WHERE fechaReserva=:fechaReserva AND idMesa=:idMesa ";
            $borrar = $conexion->prepare($eliminar);
            $borrar->bindParam(":idMesa", $idMesa);
            $borrar->bindParam(":fechaReserva", $reserva);
            $borrar->execute();
        }
        
    }
    //Se muestran las reservas privadas
    $consultaSQL = 'select * from reservas where idUsuario = :idUsuario order by fechaReserva DESC';
    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->bindParam(":idUsuario", $idUsuario);
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
                            $mesa = "select numeroMesa from mesas where idMesa = :idMesa";
                            $consultaMesa = $conexion->prepare($mesa);
                            $consultaMesa->bindParam(":idMesa", $fila["idMesa"]);
                            $consultaMesa->execute();
                            $nombreMesa = $consultaMesa->fetchColumn();
                            echo $nombreMesa;
                            ?>
                        </td>
                        <td>
                            <a href="verReservasPrivadas.php?reserva=<?= $fila["fechaReserva"] ?>&idReserva=<?= $fila["idMesa"] ?>">Eliminar</a>
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