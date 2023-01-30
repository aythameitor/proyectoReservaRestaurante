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
    //Crea la conexión
    $dsn = $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    //Realiza la consulta que recoge las mesas totales para el posterior select
    $totalMesas = "select * from mesas";
    $consultaTotalMesas = $conexion->prepare($totalMesas);
    $consultaTotalMesas->execute();

    if (isset($_POST["submit"])) {
        $fechaReserva = strip_tags(trim($_POST["fechaReserva"]));
        $horaReserva = strip_tags(trim($_POST["horaReserva"]));
        $numeroMesa = strip_tags(trim($_POST["numeroMesa"]));

        //Recoge el id de usuario para crear la reserva
        $sqlId = "select idUsuario from usuarios where email = :email";
        $consultaSqlId = $conexion->prepare($sqlId);
        $consultaSqlId->bindParam(":email", $_SESSION["email"]);
        $consultaSqlId->execute();

        $idUsuario = $consultaSqlId->fetchColumn();

        //Recoge el id de mesa para crear la reserva
        $sqlMesa = "select idMesa from mesas where numeroMesa = :numeroMesa";
        $consultaSqlMesa = $conexion->prepare($sqlMesa);
        $consultaSqlMesa->bindParam(":numeroMesa", $numeroMesa);
        $consultaSqlMesa->execute();

        $idMesa = $consultaSqlMesa->fetchColumn();

        //concatena fecha y hora para dejarlo en un formato que MySQL entienda como timestamp
        $fechaYHora = $fechaReserva . " " . $horaReserva;

        //inserta los valores
        $sql = "insert into reservas (idUsuario, idMesa, fechaReserva) values ( :idUsuario, :idMesa, :fechaReserva)";
        $consulta = $conexion->prepare($sql);
        $consulta->bindParam(":idUsuario", $idUsuario);
        $consulta->bindParam(":idMesa", $idMesa);
        $consulta->bindParam(":fechaReserva", $fechaYHora);
        $consulta->execute();

        if ($consulta->rowCount() == 1) {
            $mensajeExito = "Reserva añadida con éxito";
        } else {
            $mensajeFallo = "No se ha podido añadir la reserva, la mesa solicitada puede no estar disponible para esa hora y día";
        }
    }

} catch (PDOException $error) {
    $error = "No se ha podido añadir la reserva, la mesa solicitada puede no estar disponible para esa hora y día";
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
                <?= $mensajeFallo ?>
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

<div>
    <form action="" method="post">
        <div class="form-group">
            <label>Fecha de reserva:</label>
            <input type="date" name="fechaReserva" id="fechaReserva"/>
        </div>
        <div class="form-group">
            <label>Hora de reserva:</label>
            <select name="horaReserva" id="hora">
                <option value="16:00">16:00-16:59</option>
                <option value="17:00">17:00-17:59</option>
                <option value="18:00">18:00-18:59</option>
                <option value="19:00">19:00-19:59</option>
                <option value="20:00">20:00-20:59</option>
                <option value="21:00">21:00-21:59</option>
                <option value="22:00">22:00-22:59</option>
                <option value="23:00">23:00-23:59</option>
            </select>
        </div>
        <div class="form-group">
            <label>Numero de mesa:</label>
            <select name="numeroMesa">
                <?php
                foreach ($consultaTotalMesas->fetchAll() as $value) {
                    echo "<option value='" . $value["numeroMesa"] . "'>" . $value["numeroMesa"] . "</option>";
                }
                ?>
            </select>

        </div>
        <br /><br />
        <input type="submit" value="submit" name="submit" /><br />
    </form>
</div>
<script>
document.getElementById('fechaReserva').valueAsDate = new Date();
    </script>
<?php include '../partes/footer.php'?>