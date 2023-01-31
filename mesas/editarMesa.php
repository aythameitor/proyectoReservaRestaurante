<?php
session_name("reservaRestaurante");
session_start();
$error = false;

$config = include '../config.php';

include '../funciones/codificar.php';

include "../funciones/consultas.php";
//Se realiza la comprobación de los datos del usuario y del número de rol almacenado en la sesión
if (!isset($_SESSION["email"]) || !isset($_SESSION["idRol"]) || $_SESSION["idRol"] < 2) {
    header("location:../index.php");
    die();
}
try {
    //Comprueba si el rol del usuario es el adecuado para entrar a la página, en caso de no ser así, el usuario es
    //redireccionado al inicio
    $conexion = conexion();
    if (select("idRol", $_SESSION["email"], $conexion)->fetchColumn() < 2) {
        header("location:/index.php");
        die();
    };
    //Si no es redireccionado se guardan el número e id de mesa para tener una persistencia de datos 
    //correcta en los formularios
    $numeroMesa = codificarHTML(strip_tags(trim($_GET["numeroMesa"])));
    $idMesa = codificarHTML(strip_tags(trim($_GET["idMesa"])));
} catch (PDOException $error) {
    $error = $error->getMessage();
}
try {
    //Comprueba si el método del servidor es por POST, y si es así, empieza a realizar las comprobaciones pertinentes para  editar una mesa
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conexion = conexion();

        //Recoge los valores pasados por la URL y el formulario
        $numeroMesa = codificarHTML(strip_tags(trim($_POST["numeroMesa"])));
        $idMesa = codificarHTML(strip_tags(trim($_GET["idMesa"])));
        //comprueba que ambos sean números
        if (filter_var($numeroMesa, FILTER_VALIDATE_INT) == false || filter_var($idMesa, FILTER_VALIDATE_INT) == false) {
            $mensajeFallo = "Comprueba que ambos valores sean números y vuelve a intentarlo";
        } 
        else {
            //Con esos dos valores comprueba si el número de mesa ya existe
            $comprobacion = "SELECT numeroMesa FROM mesas WHERE numeroMesa = :numeroMesa";
            $consultaCompr = $conexion->prepare($comprobacion);
            $consultaCompr->bindParam(":numeroMesa", $numeroMesa);
            $consultaCompr->execute();
            //En caso de devolver 1 significa que el número de mesa estaría repetido, por lo que se guarda en la variable $mensajeFallo
            if ($consultaCompr->rowCount() == 1) {
                $mensajeFallo = "Ya existe una mesa con ese número, por favor introduce otro";
            } else {
                //Al llegar aquí, el número de mesa no existe por lo que realiza la actualización
                $sql = "update mesas set numeroMesa = :numeroMesa where idMesa = :idMesa";
                $consulta = $conexion->prepare($sql);
                $consulta->bindParam(":numeroMesa", $numeroMesa, PDO::PARAM_INT);
                $consulta->bindParam(":idMesa", $idMesa, PDO::PARAM_INT);
                $consulta->execute();

                //Si este if devuelve una línea significa que la mesa se modificó, por lo que modifico las variables correspondientes para una 
                //correcta retroalimentación al usuario
                if ($consulta->rowCount() == 1) {
                    $mensajeExito = "Mesa modificada con éxito";
                } else {
                    $mensajeFallo = "No se ha podido modificar la mesa, ha ocurrido un error inesperado";
                }
            }
        }
    }
} catch (PDOException $error) {
    $error = $error->getMessage();
}



?>

<?php include '../partes/header.php';
//se comprueba si existen los mensajes y se muestran, por como está organizado el código, 
//no puede haber dos mensajes simultáneos 
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
    <form action="" method="post">
        <label>id mesa:</label>
        <input type="text" name="idMesa" value="<?php
                                                //Se pone como valor el id de la mesa para mantener los datos en el formulario
                                                echo codificarHTML(strip_tags(trim($_GET["idMesa"])));
                                                ?>" disabled />
        <label>número mesa:</label>
        <input type="text" name="numeroMesa" value="<?php
                                                    //Se comprueba el método del servidor para saber de donde seleccionar los datos
                                                    //que irán en el formulario
                                                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                                        echo codificarHTML(strip_tags(trim($_POST["numeroMesa"])));
                                                    } else {
                                                        echo codificarHTML(strip_tags(trim($_GET["numeroMesa"])));
                                                    }
                                                    ?>" />
        <br /><br />

        <input type="submit" value="submit" /><br />
    </form>
</div>

<?php include '../partes/footer.php' ?>