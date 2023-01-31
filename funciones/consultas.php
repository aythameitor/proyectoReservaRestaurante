<?php

//Selecciona un campo único de la tabla usuarios para realizar comprobaciones usando como condición el email
function select($campo, $email, $conexion)
{
    $sql = "SELECT $campo FROM usuarios WHERE email = :email";
    $consulta = $conexion->prepare($sql);
    $consulta->bindParam(":email", $email, PDO::PARAM_STR);
    $consulta->execute();
    return $consulta;
}
//Selecciona un campo único de la tabla mesas para realizar comprobaciones usando como condición el id de la mesa
function selectMesa($campo, $idMesa, $conexion)
{
    $sql = "SELECT $campo FROM mesas WHERE idMesa = :idMesa";
    $consulta = $conexion->prepare($sql);
    $consulta->bindParam(":idMesa", $idMesa, PDO::PARAM_INT);
    $consulta->execute();
    return $consulta;
}
function conexion(){
    $config = include $_SERVER['DOCUMENT_ROOT'].'/config.php';
    $dsn = $config['db']['host'].';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    return $conexion;
}