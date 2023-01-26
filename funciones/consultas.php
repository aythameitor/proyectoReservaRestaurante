<?php

function select($campo, $email, $conexion)
{
    $sql = "SELECT $campo FROM usuarios WHERE email = '$email'";
    $consulta = $conexion->prepare($sql);
    $consulta->execute();
    return $consulta;
}
