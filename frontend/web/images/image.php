<?php

$tabla  = $_GET['t'];                               // TABLA
$campo  = $_GET['c'];                               // CAMPO A BUSCAR EN BD
$id     = isset($_GET['id']) ? $_GET['id'] : null;  // ID PARA EL WHERE

$tablasPosibles = [
    'directo'
];

if (!in_array($tabla, $tablasPosibles) && !isset($campo)) {
    exit;
}

$url = getenv('DATABASE_URL');

if ($url) {
    $aDatos   = parse_url($url);

    $host     = $aDatos['host'];
    $port     = $aDatos['port'];
    $dbname   = substr($aDatos['path'], 1);
    $username = $aDatos['user'];
    $password = $aDatos['pass'];
} else {
    $host     = 'localhost';
    $port     = '5432';
    $dbname   = 'skeletonstrap';
    $username = 'skeletonstrap';
    $password = 'skeletonstrap';
}

$pdo = new PDO("pgsql:;port={$port};host={$host};dbname={$dbname}", $username, $password);

$sql = 'SELECT ' . $campo . ' FROM ' . $tabla;

if (isset($id)) {
    $sql .= ' WHERE id = :id';
} else {
    $sql .= ' LIMIT 1';
}

$stm = $pdo->prepare($sql);

if (isset($id)) {
    $stm->bindValue(':id', $id);
}

$stm->execute();
$image = $stm->fetchObject()->{$campo};

if (!$image) {
    exit;
}

$image = base64_decode($image);

if (!$image) {
    exit;
}

header('Content-type: image/jpeg');
echo $image;
