<?php

require_once "../../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Clasics - Search 1", MENU_Clasicos, PROFUNDIDAD_2);

// Comprobamos si la base de datos contiene registros
$hayRegistrosOk = false;

$consulta = "SELECT COUNT(*) FROM $cfg[tablaClasicos]";

$resultado = $pdo->query($consulta);
if (!$resultado) {
    print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
} elseif ($resultado->fetchColumn() == 0) {
    print "    <p class=\"aviso\">Any record created yet.</p>\n";
} else {
    $hayRegistrosOk = true;
}

// Si todas las comprobaciones han tenido éxito ...
if ($hayRegistrosOk) {
    // Mostramos el formulario
    print "    <form action=\"buscar-2.php\" method=\"$cfg[formMethod]\">\n";
    print "      <p class=\"parrafo\">Writhe the search criteria (can gap some fields if apply):</p>\n";
    print "\n";
    print "      <table>\n";
    print "        <tr>\n";
    print "          <td class=\"parrafo\">Title:</td>\n";
    print "          <td><input type=\"text\" name=\"titulo\" size=\"$cfg[formClasicosTamTitulo]\" maxlength=\"$cfg[formClasicosMaxTitulo]\" autofocus></td>\n";
    print "        </tr>\n";
    print "        <tr>\n";
    print "          <td class=\"parrafo\">Author:</td>\n";
    print "          <td><input type=\"text\" name=\"autor\" size=\"$cfg[formClasicosTamAutor]\" maxlength=\"$cfg[formClasicosMaxAutor]\"></td>\n";
    print "        </tr>\n";
    print "        <tr>\n";
    print "          <td class=\"parrafo\">Editorial:</td>\n";
    print "          <td><input type=\"text\" name=\"editorial\" size=\"$cfg[formClasicosTamEditorial]\" maxlength=\"$cfg[formClasicosMaxEditorial]\"></td>\n";
    print "        </tr>\n";
    print "        <tr>\n";
    print "          <td class=\"parrafo\">Language:</td>\n";
    print "          <td><input type=\"text\" name=\"idioma\" size=\"$cfg[formClasicosTamIdioma]\" maxlength=\"$cfg[formClasicosMaxIdioma]\"></td>\n";
    print "        </tr>\n";
    print "          <td class=\"parrafo\">Number of pages:</td>\n";
    print "          <td><input type=\"integer\" name=\"paginas\" size=\"$cfg[formClasicosTamPaginas]\" maxlength=\"$cfg[formClasicosMaxPaginas]\"></td>\n";
    print "        </tr>\n";
    print "        <tr>\n";
    print "          <td class=\"parrafo\">Disponibility:</td>\n";
    print "          <td>\n";
    print "            <select name=\"disponibilidad\" class=\"botonform\">\n";
    print "              <option value=\"\">-- Select --</option>\n";
    print "              <option value=\"Sí\">Yes</option>\n";
    print "              <option value=\"No\">No</option>\n";
    print "            </select>\n";
    print "          </td>\n";
    print "        </tr>\n";
    print "      </table>\n";
    print "\n";
    print "      <p>\n";
    print "        <input type=\"submit\" value=\"Search\" class=\"botonform\">\n";
    print "        <input type=\"reset\" value=\"Restart the form\" class=\"botonform\">\n";
    print "      </p>\n";
    print "    </form>\n";
}


pie();
