<?php
if (isset($_POST["salir"])) {
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../estilos/principal.css">

</head>

<body style="color:white; background-color: black; min-height: 100vh;">
    <div class="contenedor">
        <div style="background-color:white; align-items: center; border-radius:10px" class="m-3 p-2">

            <?php
            if (isset($_SESSION["email"])) {
                if (isset($_SESSION["idRol"])) {
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
                            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                                <a class="navbar-brand ml-1 btn btn-primary" href="/index.php">Inicio</a>
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>

                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav justify-content-between w-100">
                                        <li class="nav-item">
                                            <a href="/mesas/mostrarMesas.php" class="btn btn-primary">Mesas</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/reservas/crearReserva.php" class="btn btn-primary">Crear reserva</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/reservas/verReservasPrivadas.php" class="btn btn-primary">Ver reservas personales</a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="/usuarios/editarUsuario.php" class="btn btn-primary">Editar tu cuenta</a>
                                        </li>
                                        <li class="nav-item">
                                            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                                                <input type="submit" name="salir" value="salir">
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        <?php
                            break;

                        case 2:
                        ?>
                            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                                <a class="navbar-brand ml-1" href="/index.php"><img src="/img/elpotelogo.png" class="imagen" alt="Logo"></a>
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>

                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav justify-content-around w-100">
                                        <li class="nav-item active">
                                            <a href="/registro.php" class="btn btn-primary">Registro</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/usuarios/mostrarUsuarios.php" class="btn btn-primary">Usuarios</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/mesas/mostrarMesas.php" class="btn btn-primary">Mesas</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/reservas/verReservasMesas.php" class="btn btn-primary">Ver reservas</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/reservas/crearReserva.php" class="btn btn-primary">Crear reserva</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/reservas/verReservasPrivadas.php" class="btn btn-primary">Ver reservas personales</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/usuarios/editarUsuario.php" class="btn btn-primary">Editar tu cuenta</a>
                                        </li>
                                        <li class="nav-item">
                                            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                                                <input type="submit" name="salir" value="salir">
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        <?php
                            break;
                        case 3:
                        ?>
                            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                                <a class="navbar-brand ml-1 btn btn-primary" href="/index.php">Inicio</a>
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>

                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav justify-content-around w-100">
                                        <li class="nav-item active">
                                            <a href="/registro.php" class="btn btn-primary">Registro</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/usuarios/mostrarUsuarios.php" class="btn btn-primary">Usuarios</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/mesas/mostrarMesas.php" class="btn btn-primary">Mesas</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/reservas/verReservasMesas.php" class="btn btn-primary">Ver reservas</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/reservas/crearReserva.php" class="btn btn-primary">Crear reserva</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/reservas/verReservasPrivadas.php" class="btn btn-primary">Ver reservas personales</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/usuarios/editarUsuario.php" class="btn btn-primary">Editar tu cuenta</a>
                                        </li>
                                        <li class="nav-item">
                                            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                                                <input type="submit" name="salir" value="salir">
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </nav>

                <?php
                            break;
                    }
                }
            } else {
                ?>
                
                
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                                <a class="navbar-brand ml-1 btn btn-primary" href="/index.php">Inicio</a>
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>

                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav justify-content-around w-100">
                                        <li class="nav-item active">
                                            <a href="/registro.php" class="btn btn-primary">Registro</a>
                                        </li>
                                        <li class="nav-item">
                                        <a href="/login.php" class="btn btn-primary">Login</a>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
            <?php
            }

            ?>
        </div>