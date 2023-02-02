Proyecto de reservas de un restaurante

El proyecto consta de diferentes áreas, entre las cuales se pueden distinguir 3 roles principales, usuario (por defecto), administrador y superadministrador.



Idea general

Para este proyecto, la idea era simple, permitir que una página web tuviera una conexión a una base de datos y esta permitiera a diferentes usuarios añadir datos dependiendo de su rol.

Este proyecto es altamente escalable, permite añadir nuevos roles de manera sencilla, con un fácil mantenimiento y manejo.

Requisitos

Los requisitos básicos para hacer funcionar el proyecto son:
XAMPP
Visual studio code (con php live server)
Permisos de administrador en un servidor MySQL

Instalación

Para instalar el proyecto, necesitamos cambiar los datos del archivo config.php por los datos del administrador de la base de datos, tras esto, ejecutamos el archivo instalar/instalar.php, este nos creará un usuario llamado 'admin@administrador.com' con contraseña 'admin', una vez creada la base de datos y el usuario, podemos cambiar los datos del archivo config por los ya proporcionados.

Diferentes accesos

Los usuarios sin loguear solo podrán ver la página principal del restaurante, donde se muestran imágenes y vídeos del mismo, el menú para ellos solo mostrará dos opciones que son login o registrarse.

Los usuarios logueados, tienen acceso a sus reservas, pueden crear reservas nuevas y eliminar las mismas

Los administradores tienen acceso a las reservas de los usuarios, también pueden crear usuarios nuevos, pueden crear reservas personales y añadir, editar y eliminar mesas.

Los superadministradores tienen acceso a todo lo que los administradores tienen, y además, pueden crear nuevos administradores y borrar usuarios (excepto superadministradores)

Tecnologías usadas

-Html
-Css
-Javascript
-Php
-Pdo
-Doxygen