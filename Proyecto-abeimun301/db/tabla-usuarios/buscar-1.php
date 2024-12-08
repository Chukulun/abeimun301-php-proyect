<?php

require_once "../../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Users - Search 1", MENU_USUARIOS, PROFUNDIDAD_2);

// Comprobamos si la base de datos contiene registros
$hayRegistrosOk = false;

$consulta = "SELECT COUNT(*) FROM $cfg[tablaUsuarios]";

$resultado = $pdo->query($consulta);
if (!$resultado) {
    print "    <p class=\"aviso\">Error en la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
} elseif ($resultado->fetchColumn() == 0) {
    print "    <p class=\"aviso\">No se ha creado todavía ningún registro.</p>\n";
} else {
    $hayRegistrosOk = true;
}

// Si todas las comprobaciones han tenido éxito ...
if ($hayRegistrosOk) {
    // Mostramos el formulario
    print "    <form action=\"buscar-2.php\" method=\"$cfg[formMethod]\">\n";
    print "      <p class=\"parrafo\">Write search criteria (characters or numbers):</p>\n";
    print "\n";
    print "      <table>\n";
    print "        <tr>\n";
    print "          <td class=\"parrafo\">User:</td>\n";
    print "          <td><input type=\"text\" name=\"usuario\" size=\"$cfg[formUsuariosTamUsuario]\" maxlength=\"$cfg[formUsuariosMaxUsuario]\" autofocus></td>\n";
    print "        </tr>\n";
    print "      </table>\n";
    print "\n";
    print "      <p>\n";
    print "        <input type=\"submit\" value=\"Search\" class=\"botonform\">\n";
    print "        <input type=\"reset\" value=\"Restart form\" class=\"botonform\">\n";
    print "      </p>\n";
    print "    </form>\n";
}

pie();
