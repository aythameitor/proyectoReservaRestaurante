<?php
function codificarHTML($html){
    return htmlspecialchars($html,ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
} ?>