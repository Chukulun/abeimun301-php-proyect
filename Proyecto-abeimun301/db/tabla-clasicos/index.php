<?php

require_once "../../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../../index.php");
    exit;
}

cabecera("Clasics - Index", MENU_Clasicos, PROFUNDIDAD_2);

pie();
