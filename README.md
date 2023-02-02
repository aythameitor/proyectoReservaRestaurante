# Índice
1. [Resumen](#Resumen)
2. [Idea general](#Idea)
3. [Requisitos](#Requisitos)
4. [Instalación](#Instalacion)
5. [Diferentes accesos](#Accesos)
6. [Tecnologías usadas](#Tecnologias)
7. [despliegue](#Despliegue)


***Proyecto de reservas de un restaurante***<a name="Resumen"></a>

El proyecto consta de diferentes áreas, entre las cuales se pueden distinguir 3 roles principales, usuario (por defecto), administrador y superadministrador.

***Idea general***<a name="Idea"></a>

Para este proyecto, la idea era simple, permitir que una página web tuviera una conexión a una base de datos y esta permitiera a diferentes usuarios añadir datos dependiendo de su rol.

Este proyecto es altamente escalable, permite añadir nuevos roles de manera sencilla, con un fácil mantenimiento y manejo.

***Requisitos***<a name="Requisitos"></a>

Los requisitos básicos para hacer funcionar el proyecto son:
XAMPP
Visual studio code (con php live server)
Permisos de administrador en un servidor MySQL

***Instalación***<a name="Instalacion"></a>

Para instalar el proyecto, necesitamos cambiar los datos del archivo config.php por los datos del administrador de la base de datos, tras esto, ejecutamos el archivo instalar/instalar.php, este nos creará un usuario llamado 'admin@administrador.com' con contraseña 'admin', una vez creada la base de datos y el usuario, podemos cambiar los datos del archivo config por los ya proporcionados.

***Diferentes accesos***<a name="Accesos"></a>

Los usuarios sin loguear solo podrán ver la página principal del restaurante, donde se muestran imágenes y vídeos del mismo, el menú para ellos solo mostrará dos opciones que son login o registrarse.

Los usuarios logueados, tienen acceso a sus reservas, pueden crear reservas nuevas y eliminar las mismas

Los administradores tienen acceso a las reservas de los usuarios, también pueden crear usuarios nuevos, pueden crear reservas personales y añadir, editar y eliminar mesas.

Los superadministradores tienen acceso a todo lo que los administradores tienen, y además, pueden crear nuevos administradores y borrar usuarios (excepto superadministradores)

***Tecnologías usadas***<a name="Tecnologias"></a>

-Html

-Css

-Javascript

-Php

-Pdo

-Doxygen


***Despliegue del proyecto***<a name="Despliegue"></a>

Por ahora el proyecto está desplegado en Heroku con el siguiente link

<a href="https://proyecto-reserva-restaurante.herokuapp.com/index.php">Click aquí</a>

El índice aún no es responsive por completo, el resto de páginas no deberían tener problemas, aunque falta adaptar el diseño para tablets

