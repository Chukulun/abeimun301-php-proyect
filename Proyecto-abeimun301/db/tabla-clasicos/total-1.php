<?php

require_once "../../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Clasics - Total", MENU_Clasicos, PROFUNDIDAD_2);

$ordena = recoge("ordena", "", $cfg["tablaClasicosColumnasOrden"][0]);  // Si solo deseas el primer valor

// Comprobamos si la base de datos contiene registros
$hayRegistrosOk = false;

$consulta = "SELECT COUNT(*) FROM $cfg[tablaClasicos]";

$resultado = $pdo->query($consulta);
if (!$resultado) {
    print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
} elseif ($resultado->fetchColumn() == 0) {
    print "    <p class=\"aviso\">Any record founded</p>\n";
} else {
    $hayRegistrosOk = true;
}

// Si todas las comprobaciones han tenido éxito ...
if ($hayRegistrosOk) {
    // Calculamos el total de páginas
    $consultaPaginas = "SELECT SUM(paginas) AS total_paginas FROM $cfg[tablaClasicos]";
    $resultadoPaginas = $pdo->query($consultaPaginas);

    if (!$resultadoPaginas) {
        print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        $totalPaginas = $resultadoPaginas->fetchColumn();
        print "    <p class=\"parrafo\">The total amount of pages of all records is: <strong>$totalPaginas</strong>.</p>\n";
    }
}

    

pie();
