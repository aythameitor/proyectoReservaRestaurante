<?php
session_name("reservaRestaurante");
session_start();
$error = false;
$config = include '../config.php';
include "../funciones/consultas.php";
if(!isset($_SESSION["email"])){
    header("location:login.php");
    die();
}
try{
    $dsn = $config['db']['host'].';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    if(select("idRol",$_SESSION["email"], $conexion)->fetchColumn() < 3){
        header("location:/confirmacion.php");
        die();
    };
    if(isset($_GET["eliminar"])){
        $eliminar = "DELETE FROM usuarios WHERE idUsuario='". $_GET["eliminar"] ."'";
        $borrar = $conexion->prepare($eliminar);
        $borrar->execute();
    }
    $consultaSQL = 'select * from usuarios';
    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();
    $usuarios = $sentencia->fetchAll();
    
} catch(PDOException $error){
    $error = $error->getMessage();
}
?>

<?php include '../partes/header.php'?>
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
        <div class="col-md-12">
            <h2 class="p-2">Lista de usuarios</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Email</th>
                        <th>Creado</th>
                        <th>Modificado</th>
                        <th>Rol</th>
                        <th>Borrar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if($usuarios && $sentencia->rowCount()>0){
                            foreach($usuarios as $fila){
                    ?>
                    <tr>
                        <td><?php echo $fila["idUsuario"];?></td>
                        <td><?php echo $fila["email"];?></td>
                        <td><?php echo $fila["creado"];?></td>
                        <td><?php echo $fila["actualizado"];?></td>
                        <td><?php 
                        $rol = 'select nombre from roles where idRol=' . $fila["idRol"];
                        $consultaRol = $conexion->prepare($rol);
                        $consultaRol->execute();
                        $nombreRol = $consultaRol->fetchColumn();
                        echo $nombreRol;
                        ?></td>
                        <td>
                        <?php    
                            if($fila["idRol"] <3){
                        ?>
                        <a href="mostrarUsuarios.php?eliminar=<?php echo $fila["idUsuario"]; ?>">Borrar</a>
                        </td>
                    </tr>
                    <?php
                                }
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