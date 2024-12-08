<?php

require_once "../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

cabecera("Administrador - Borrar todo 1", MENU_ADMINISTRADOR, PROFUNDIDAD_1);

print "    <form action=\"borrar-todo-2.php\" method=\"$cfg[formMethod]\">\n";
print "      <p class=\"parrafo\">¿Are you sure?</p>\n";
print "\n";
print "      <p>\n";
print "        <input type=\"submit\" name=\"borrar\" value=\"Yes\" class=\"botonform\">\n";
print "        <input type=\"submit\" name=\"borrar\" value=\"No\" class=\"botonform\">\n";
print "      </p>\n";
print "    </form>\n";

pie();

?>