<?php 
session_name("reservaRestaurante");
session_start();
$config = include 'config.php';
include "funciones/consultas.php";

include "partes/header.php"; ?>

    <div class="contenedor3">
        <div class="centrar">
            <p class="textoPlatos">
                Algunos de nuestros platos:
            </p>
        </div>
        <div class="contenedorImgPlato">
            <img class="platos" src="img/plato1.jpg" alt="">
            <img class="platos" src="img/plato2.jpg" alt="">
            <img class="platos" src="img/plato3.jpg" alt="">
            <img class="platos" src="img/plato4.jpg" alt="">
        </div>
        <div class="contenedorImgPlato">
            <img class="platos" src="img/plato5.jpg" alt="">
            <img class="platos" src="img/plato6.jpg" alt="">
            <img class="platos" src="img/plato7.jpg" alt="">
            <img class="platos" src="img/plato8.jpg" alt="">
        </div>
    </div>
    <div class="contenedorTexto">
        <div class="texto">
            <div  class="sobreNosotros">
                <strong>Sobre nosotros:</strong>
            </div>
            <p class="parrafoNosotros">Actualmente el Pote lo regenta Adal Santana con
                un equipo de profesionales que han mantenido la línea
                y la calidad del restaurante siendo todo un referente en la gastronomía de nuestra isla que apuesta
                fuertemente por la integración del "Mundo 2.0" y es por ello por lo que te ofrecemos un cómodo
                sistema de reservas online para que reserves tu mesa desde tu smartphone / ordenador.
            </p>
            <div style="display:flex; justify-content: space-between;">
                <button class="boton">Descargar nuestra app</button>
                <button class="boton">Mas información</button>
            </div>
        </div>
        <div class="divImgTexto">
            <img class="imgTexto" src="img/restaurante.jpg">
        </div>
    </div>
    <div class="contenedor2">
        <div class="contenedorVideos">
            <iframe class="videos" frameborder="0" scrolling="no" marginheight="0"
                marginwidth="0" width="450" height="280" type="text/html"
                src="https://www.youtube.com/embed/CNCVNDdJhu4?autoplay=0&fs=1&iv_load_policy=3&showinfo=0&rel=0&cc_load_policy=0&start=0&end=0&vq=large&origin=https://youtubeembedcode.com">
            </iframe>
            <iframe class="videos" frameborder="0" scrolling="no" marginheight="0"
                marginwidth="0" width="450" height="280" type="text/html"
                src="https://www.youtube.com/embed/MWW6oR5RMUc?autoplay=0&fs=1&iv_load_policy=3&showinfo=0&rel=0&cc_load_policy=0&start=0&end=0&vq=large&origin=https://youtubeembedcode.com">
            </iframe>
            <iframe class="videos" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" width="450"
                height="280" type="text/html"
                src="https://www.youtube.com/embed/eDvKcez6xp8?autoplay=0&fs=1&iv_load_policy=3&showinfo=0&rel=0&cc_load_policy=0&start=0&end=0&vq=large&origin=https://youtubeembedcode.com">
            </iframe>
        </div>
    </div>

    <?php
    include "partes/footer.php";
    ?>
<script src="javascript/index.js"></script>