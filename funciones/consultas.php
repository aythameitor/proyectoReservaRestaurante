<?php

function select($campo, $email, $conexion)
{
    $sql = "SELECT $campo FROM usuarios WHERE email = '$email'";
    $consulta = $conexion->prepare($sql);
    $consulta->execute();
    return $consulta;
}
function selectMesa($campo, $idMesa, $conexion)
{
    $sql = "SELECT $campo FROM mesas WHERE idMesa = '$idMesa'";
    $consulta = $conexion->prepare($sql);
    $consulta->execute();
    return $consulta;
}