<?php
/**
 * Elimina caracteres especiales de las sentencias
 * @param String $html
 * @return String $html decodificada
 */
function codificarHTML($html){
    return htmlspecialchars($html,ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
} ?>