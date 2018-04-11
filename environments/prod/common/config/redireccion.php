<?php
$urlCompleta = ($_SERVER['REQUEST_ESQUEMA'] === null ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$urlParse    = explode('://', $urlCompleta);
$urlFinal    = $urlCompleta;
if ($urlParse[0] === 'http') {
    $urlParse[0] = 'https';
    $urlFinal = $urlParse[0] . '://' . $urlParse[1];
    header("Location: $urlFinal");
    die();
}
