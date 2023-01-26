<?php
$config = include '../config.php';
try {
    $conexion = new PDO($config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    $sql = file_get_contents('../mysql/bbdd.sql');
    $conexion->exec($sql);
    echo 'La base de datos y la tabla de login se han creado con éxito';
    $conexion = null;
} catch (PDOException $error) {
    echo $error->getMessage();
}
