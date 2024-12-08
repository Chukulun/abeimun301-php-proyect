<?php

require_once "../../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Users - List", MENU_USUARIOS, PROFUNDIDAD_2);

$ordena = recoge("ordena", "", $cfg["tablaUsuariosColumnasOrden"][0]);  // Si solo deseas el primer valor


// Comprobamos si la base de datos contiene registros
$hayRegistrosOk = false;

$consulta = "SELECT COUNT(*) FROM $cfg[tablaUsuarios]";

$resultado = $pdo->query($consulta);
if (!$resultado) {
    print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
} elseif ($resultado->fetchColumn() == 0) {
    print "    <p class=\"aviso\">No records created.</p>\n";
} else {
    $hayRegistrosOk = true;
}

// Si todas las comprobaciones han tenido éxito ...
if ($hayRegistrosOk) {
    // Recuperamos todos los registros para mostrarlos en una <table>
    $consulta = "SELECT * FROM $cfg[tablaUsuarios]
                 ORDER BY $ordena";

    $resultado = $pdo->query($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        print "    <p class=\"parrafo\">Records list:</p>\n";
        print "\n";
        print "    <form action=\"$_SERVER[PHP_SELF]\" method=\"$cfg[formMethod]\">\n";
        print "      <table class=\"conborde franjas\">\n";
        print "        <thead>\n";
        print "          <tr>\n";
        print "            <th>\n";
        print "              <button name=\"ordena\" value=\"usuario ASC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/down.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"15\" height=\"12\">\n";
        print "              </button>\n";
        print "              User\n";
        print "              <button name=\"ordena\" value=\"usuario DESC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/up.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"15\" height=\"12\">\n";
        print "              </button>\n";
        print "            </th>\n";
        print "            <th>\n";
        print "              <button name=\"ordena\" value=\"password ASC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/down.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"15\" height=\"12\">\n";
        print "              </button>\n";
        print "              Password\n";
        print "              <button name=\"ordena\" value=\"password DESC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/up.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"15\" height=\"12\">\n";
        print "              </button>\n";
        print "            </th>\n";
        print "          </tr>\n";
        print "        </thead>\n";
        foreach ($resultado as $registro) {
            print "        <tr>\n";
            print "          <td>$registro[usuario]</td>\n";
            print "          <td>$registro[password]</td>\n";
            print "        </tr>\n";
        }
        print "      </table>\n";
        print "    </form>\n";
    }
}

pie();
