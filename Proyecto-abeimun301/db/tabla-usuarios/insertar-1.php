<?php

require_once "../../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Users - Add 1", MENU_USUARIOS, PROFUNDIDAD_2);

// Comprobamos si se ha alcanzado el número máximo de registros en la tabla
$limiteRegistrosOk = false;

$consulta = "SELECT COUNT(*) FROM $cfg[tablaUsuarios]";

$resultado = $pdo->query($consulta);
if (!$resultado) {
    print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
} elseif ($resultado->fetchColumn() >= $cfg["tablaUsuariosMaxReg"]) {
    print "    <p class=\"aviso\">reached maximum number of records</p>\n";
    print "\n";
    print "    <p class=\"aviso\">Please, delete at least once record to add a new one.</p>\n";
} else {
    $limiteRegistrosOk = true;
}

// Si todas las comprobaciones han tenido éxito ...
if ($limiteRegistrosOk) {
    // Mostramos el formulario
    print "    <form action=\"insertar-2.php\" method=\"$cfg[formMethod]\">\n";
    print "      <p class=\"parrafo\">Add the data of a new record</p>\n";
    print "\n";
    print "      <table>\n";
    print "        <tr>\n";
    print "          <td class=\"parrafo\">User:</td>\n";
    print "          <td><input type=\"text\" name=\"usuario\" size=\"$cfg[formUsuariosTamUsuario]\" maxlength=\"$cfg[formUsuariosMaxUsuario]\" autofocus></td>\n";
    print "        </tr>\n";
    print "        <tr>\n";
    print "          <td class=\"parrafo\">Password:</td>\n";
    print "          <td><input type=\"text\" name=\"password\" size=\"$cfg[formUsuariosTamPassword]\" maxlength=\"$cfg[formUsuariosMaxPassword]\"></td>\n";
    print "        </tr>\n";
    print "      </table>\n";
    print "\n";
    print "      <p>\n";
    print "        <input type=\"submit\" value=\"Add\"class=\"botonform\">\n";
    print "        <input type=\"reset\" value=\"Restart form\" class=\"botonform\">\n";
    print "      </p>\n";
    print "    </form>\n";
}

pie();
