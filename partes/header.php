<?php
if(isset($_POST["salir"])){
    unset($_SESSION["email"]);
    unset($_SESSION["idRol"]);
    header("location:../login.php");
    die();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión reservas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../estilos/principal.css">
</head>
<body style="color:white; background-color: black;">
<div class="contenedor">
    <div style="display:flex;justify-content:space-evenly;background-color:grey; align-items: center; border-radius:10px" class="m-3 p-2">
        
        <?php
        if(isset($_SESSION["email"])){
            if(isset($_SESSION["idRol"])){
                switch ($_SESSION["idRol"]) {
                    case 1:
                        ?>
                        <a href="/reservas/crearReserva.php" class="btn btn-primary">Crear reserva</a>
                        <a href="/mesas/mostrarMesas.php" class="btn btn-primary">Mesas</a>
                        <a href="/reservas/verReservasPrivadas.php" class="btn btn-primary">Ver reservas personales</a>
                        <a href="/usuarios/editarUsuario.php" class="btn btn-primary">Editar tu cuenta</a>
                        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                            <input type="submit" name="salir" value="salir">
                        </form>
                        <?php
                        break;
        
                    case 2:
                        ?>
                        <a href="/registro.php" class="btn btn-primary">Registro</a>
                        <a href="/mesas/mostrarMesas.php" class="btn btn-primary">Mesas</a>
                        <a href="/usuarios/mostrarUsuarios.php" class="btn btn-primary">Usuarios</a>
                        <a href="/usuarios/editarUsuario.php" class="btn btn-primary">Editar tu cuenta</a>
                        <a href="/reservas/verReservasMesas.php" class="btn btn-primary">Ver reservas</a>
                        <a href="/reservas/crearReserva.php" class="btn btn-primary">Crear reserva</a>
                        <a href="/reservas/verReservasPrivadas.php" class="btn btn-primary">Ver reservas personales</a>
                        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                            <input type="submit" name="salir" value="salir">
                        </form>
                        <?php
                        break;
                    case 3:
                        ?>
                        <a href="/registro.php" class="btn btn-primary">Registro</a>
                        <a href="/usuarios/mostrarUsuarios.php" class="btn btn-primary">Usuarios</a>
                        <a href="/mesas/mostrarMesas.php" class="btn btn-primary">Mesas</a>
                        <a href="/usuarios/editarUsuario.php" class="btn btn-primary">Editar tu cuenta</a>
                        <a href="/reservas/verReservasMesas.php" class="btn btn-primary">Ver reservas</a>
                        <a href="/reservas/crearReserva.php" class="btn btn-primary">Crear reserva</a>
                        <a href="/reservas/verReservasPrivadas.php" class="btn btn-primary">Ver reservas personales</a>
                        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                            <input type="submit" name="salir" value="salir">
                        </form>
                    <?php
                        break;
                    
                }
            }
        } else{
            ?>
            <a href="/login.php" class="btn btn-primary">Login</a>
            <a href="/registro.php" class="btn btn-primary">Registro</a>
            <?php
        }
        
        ?>
    </div>
