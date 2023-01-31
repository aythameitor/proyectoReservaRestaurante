<?php
session_name("reservaRestaurante");
session_start();
$error = false;

include "../funciones/consultas.php";
include "../funciones/codificar.php";
if(!isset($_SESSION["email"])){
    header("location:../login.php");
    die();
}
try{
    $conexion = conexion();
    
    if(select("idRol",$_SESSION["email"], $conexion)->fetchColumn() < 2){
        header("location:/index.php");
        die();
    };

    //Se comprueba el id de la sesión y si se recibe un get de eliminar, en ese caso
    //se comprueba el id del usuario a eliminar para evitar eliminar superadmins
    //en caso de no ser superadmin se elimina
    if(isset($_GET["eliminar"]) && $_SESSION["idRol"] == 3){
        $idEliminar = codificarHTML(strip_tags(trim($_GET["eliminar"])));
        $comprobar = "SELECT idRol from usuarios where IdUsuario = :idUsuario";
        $consultaCompr = $conexion->prepare($comprobar);
        $consultaCompr->bindParam(":idUsuario", $idEliminar);
        $consultaCompr->execute();

        //
            if($consultaCompr->fetchColumn() <3){
                $eliminar = "DELETE FROM usuarios WHERE idUsuario=:idUsuario";
                $borrar = $conexion->prepare($eliminar);
                $borrar->bindParam(":idUsuario", $idEliminar);
                $borrar->execute();
            }
    }
    //Se seleccionan los usuarios para cargar la lista con el usuario ya borrado
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
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Email</th>
                        <th>Creado</th>
                        <th>Modificado</th>
                        <th>Rol</th>
                        <?php
                         if($_SESSION["idRol"]>=3){
                        ?>
                        <th>Borrar</th>
                        <?php
                         }
                         ?>
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
                        if($_SESSION["idRol"]>=3){
                            if($fila["idRol"] <3){
                        ?>
                        <a href="mostrarUsuarios.php?eliminar=<?php echo $fila["idUsuario"]; ?>">Borrar</a>
                        </td>
                    </tr>
                    <?php
                                }
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-md-12">
                <a href="../index.php" class="btn btn-primary">Volver al inicio</a>
            </div>
        </div>
    </div>
</div>
<?php include '../partes/footer.php'?>