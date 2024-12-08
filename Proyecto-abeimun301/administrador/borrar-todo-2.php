<?php

require_once "../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$borrar = recoge("borrar");

if (!in_array($borrar, ["No", "Yes"])) {
    $borrar = "No";
}

if ($borrar != "Yes") {
    header("Location:index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Administrador - Borrar todo 2", MENU_ADMINISTRADOR, PROFUNDIDAD_1);

borraTodo();

pie();
