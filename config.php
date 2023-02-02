<?php

/** Array de config de la base de datos */

    return [
        'db' => [
            'host' => 'mysql:host=localhost',
            'user' => 'administradorRestaurante',
            'pass' => 'admin',
            'name' => 'reservarestaurante',
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        ]
    ]
?>