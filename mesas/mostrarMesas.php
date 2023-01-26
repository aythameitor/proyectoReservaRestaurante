<?php
session_name("reservaRestaurante");
session_start();
$error = false;
$config = include '../config.php';
include "../funciones/consultas.php";
if(!isset($_SESSION["email"]) || !isset($_SESSION["idRol"]) || $_SESSION["idRol"] <2){
    header("location:../confirmacion.php");
    die();
}
try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $dsn = $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
        $numeroMesa = strip_tags(trim($_POST["numeroMesa"]));

        $comprobacion = "SELECT numeroMesa FROM mesas WHERE numeroMesa = $numeroMesa";
        $consultaCompr = $conexion->prepare($comprobacion);
        $consultaCompr->execute();
        if($consultaCompr->rowCount() == 1){
            $mensajeFallo = "Ya existe una mesa con ese número, por favor introduce otro";
        } else{
            $sql = "insert into mesas (numeroMesa) values (:numeroMesa)";
            $consulta = $conexion->prepare($sql);
            $consulta->bindParam(":numeroMesa", $numeroMesa, PDO::PARAM_STR);
            $consulta->execute();
            $mensajeExito = "Mesa añadida con éxito";
        }
        
    }
} catch (PDOException $error) {
    $error = $error->getMessage();
}
try{
    $dsn = $config['db']['host'].';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    if(select("idRol",$_SESSION["email"], $conexion)->fetchColumn() < 2){
        header("location:/confirmacion.php");
        die();
    };
    if(isset($_GET["eliminar"])){
        $eliminar = "DELETE FROM mesas WHERE idMesa='". strip_tags(trim($_GET["eliminar"])) ."'";
        $borrar = $conexion->prepare($eliminar);
        $borrar->execute();
    }
    $consultaSQL = 'select * from mesas';
    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();
    $usuarios = $sentencia->fetchAll();
    
} catch(PDOException $error){
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
<div class="container">
    <div class="row">
    <form action="" method="post">
        <label>numeroMesa:</label><input type="text" name="numeroMesa" /><br /><br />

        <input type="submit" value="submit" /><br />
    </form>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h2 class="p-2">Lista de mesas</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Número</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if($usuarios && $sentencia->rowCount()>0){
                            foreach($usuarios as $fila){
                    ?>
                    <tr>
                        <td><?php echo $fila["idMesa"];?></td>
                        <td><?php echo $fila["numeroMesa"];?></td>
                        <td>
                        <a href="mostrarMesas.php?eliminar=<?php echo $fila["idMesa"]; ?>">Borrar</a>
                        </td>
                    </tr>
                    <?php
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-md-12">
                <a href="../confirmacion.php" class="btn btn-primary">Volver al inicio</a>
            </div>
        </div>
    </div>
</div>
<?php include '../partes/footer.php'?>