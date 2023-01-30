<?php

//Array de config de la base de datos

    return [
        'db' => [
            'host' => 'mysql:host=localhost',
            'user' => 'root',
            'pass' => '',
            'name' => 'reservarestaurante',
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        ]
    ]
?>