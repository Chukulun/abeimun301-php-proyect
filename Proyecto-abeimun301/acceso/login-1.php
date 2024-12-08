<?php

require_once "../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Login 1", MENU_VOLVER, PROFUNDIDAD_1);

if (!existenTablas()) {
    print "<p>Doesn't exist a Database. it begin to creation of a database.</p>\n";
    print "\n";
    borraTodo();
}

$aviso = recoge("aviso");

if ($aviso != "") {
    print "    <p class=\"aviso\">$aviso</p>\n";
    print "\n";
}

print "    <form action=\"login-2.php\" method=\"$cfg[formMethod]\">\n";
print "      <p class=\"parrafo\">Write your username nad password:</p>\n";
print "\n";
print "      <table>\n";
print "        <tr>\n";
print "          <td class=\"parrafo\">User:</td>\n";
print "          <td><input type=\"text\" name=\"usuario\" size=\"$cfg[formUsuariosTamUsuario]\" maxlength=\"$cfg[formUsuariosMaxUsuario]\" autofocus/></td>\n";
print "        </tr>\n";
print "        <tr>\n";
print "          <td class=\"parrafo\">Password:</td>\n";
print "          <td><input type=\"password\" name=\"password\" size=\"$cfg[formUsuariosTamPassword]\" maxlength=\"$cfg[formUsuariosMaxPassword]\"/></td>\n";
print "        </tr>\n";
print "      </table>\n";
print "\n";
print "      <p >\n";
print "        <input type=\"submit\" value=\"Identify\" class=\"botonform\">\n";
print "        <input type=\"reset\" value=\"Delete\" class=\"botonform\">\n";
print "      </p>\n";
print "    </form>\n";

pie();
