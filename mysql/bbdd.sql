create database if not exists reservaRestaurante;
use reservaRestaurante;

create user if not exists 'administradorRestaurante'@'localhost' identified by 'admin';
grant all privileges on reservaRestaurante.* to 'administradorRestaurante'@'localhost' identified by 'admin';
flush privileges;


CREATE TABLE if not exists `roles` (
  `idRol` int(11) primary key AUTO_INCREMENT NOT NULL,
  `nombre` varchar(255) NOT NULL
);
CREATE TABLE if not exists `usuarios` (
  `idUsuario` int(11) primary key AUTO_INCREMENT NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `contrasena` varchar(255) NOT NULL,
  `creado` DATETIME NOT NULL DEFAULT current_timestamp(),
  `actualizado` DATETIME NOT NULL DEFAULT current_timestamp(),
  `idRol` int(11) DEFAULT 1,
  CONSTRAINT `FK_idRol` FOREIGN KEY (`idRol`) REFERENCES `roles`(`idRol`) ON DELETE CASCADE
);

CREATE TABLE if not exists `mesas` (
  `idMesa` int(11) primary key AUTO_INCREMENT NOT NULL,
  `numeroMesa` int(11) UNIQUE
);

CREATE TABLE if not exists `reservas` (
  `idUsuario` int(11),
  `idMesa` int(11),
  `fechaReserva` DATETIME NOT NULL,
  CONSTRAINT `FK_idUsuario` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios`(`idUsuario`) ON DELETE CASCADE,
  CONSTRAINT `FK_idMesa` FOREIGN KEY (`idMesa`) REFERENCES `mesas`(`idMesa`) ON DELETE CASCADE
);
ALTER TABLE `reservas` ADD UNIQUE `unique_ids`(`idMesa`, `fechaReserva`);
INSERT INTO `roles` (`nombre`) VALUES ('cliente');
INSERT INTO `roles` (`nombre`) VALUES ('admin');
INSERT INTO `roles` (`nombre`) VALUES ('superadmin');
INSERT INTO `usuarios` (`email`, `contrasena`,`idRol`) VALUES ('admin@administrador.com','$2y$10$iuhTcWOXiiREYcS0Tl2lcucGkqFq0zx3o6njkOXHe.A6YTv0BOkJS',3);
INSERT INTO `mesas` (`idMesa`, `numeroMesa`) VALUES (1,1);
/*
    La contraseña del usuario por defecto con superadmin es root, está encriptada en el Hash que usa php por defecto
*/
