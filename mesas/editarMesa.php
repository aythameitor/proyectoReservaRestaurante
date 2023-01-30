<?php
session_name("reservaRestaurante");
session_start();
$error = false;
$config = include '../config.php';
include "../funciones/consultas.php";
if(!isset($_SESSION["email"]) || !isset($_SESSION["idRol"]) || $_SESSION["idRol"] <2){
    header("location:../index.php");
    die();
}
try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $dsn = $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
        $numeroMesa = strip_tags(trim($_POST["numeroMesa"]));
        $idMesa = strip_tags(trim($_GET["idMesa"]));
        $comprobacion = "SELECT numeroMesa FROM mesas WHERE numeroMesa = :numeroMesa";
        $consultaCompr = $conexion->prepare($comprobacion);
        $consultaCompr->bindParam(":numeroMesa", $numeroMesa);
        $consultaCompr->execute();
        if($consultaCompr->rowCount() == 1){
            $mensajeFallo = "Ya existe una mesa con ese número, por favor introduce otro";
        } else{
            $sql = "update mesas set numeroMesa = :numeroMesa where idMesa = :idMesa";
            $consulta = $conexion->prepare($sql);
            $consulta->bindParam(":numeroMesa", $numeroMesa, PDO::PARAM_INT);
            $consulta->bindParam(":idMesa", $idMesa, PDO::PARAM_INT);
            $consulta->execute();
            if($consulta->rowCount() == 1){
                $mensajeExito = "Mesa modificada con éxito";
            } else{
                $mensajeFallo = "No se ha podido modificar la mesa, ha ocurrido un error inesperado";
            }
            
        }
        
    }
} catch (PDOException $error) {
    $error = $error->getMessage();
}
try{
    $dsn = $config['db']['host'].';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    if(select("idRol",$_SESSION["email"], $conexion)->fetchColumn() < 2){
        header("location:/index.php");
        die();
    };
    $numeroMesa = strip_tags(trim($_GET["numeroMesa"]));
    $idMesa = strip_tags(trim($_GET["idMesa"]));
    
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

<div>
<form action="" method="post">
        <label>id mesa:</label>
        <input type="text" name="idMesa" value="<?php
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            echo strip_tags(trim($_GET["idMesa"]));
        } else{
            echo strip_tags(trim($_GET["idMesa"]));
        }
        ?>" disabled/>
        <label>número mesa:</label>
        <input type="text" name="numeroMesa" value="<?php
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                echo strip_tags(trim($_POST["numeroMesa"]));
            } else{
                echo strip_tags(trim($_GET["numeroMesa"]));
            }
        ?>"/>
        <br /><br />

        <input type="submit" value="submit" /><br />
    </form>
</div>

<?php include '../partes/footer.php'?>