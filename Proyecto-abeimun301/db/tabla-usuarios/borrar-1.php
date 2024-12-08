<?php
require_once "../../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Usuarios - Borrar 1", MENU_USUARIOS, PROFUNDIDAD_2);

$ordena = recoge("ordena", "", $cfg["tablaUsuariosColumnasOrden"][0]); 
$id     = recoge("id", []);

// Comprobamos si la base de datos contiene registros
$hayRegistrosOk = false;

$consulta = "SELECT COUNT(*) FROM $cfg[tablaUsuarios]";

$resultado = $pdo->query($consulta);
if (!$resultado) {
    print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
} elseif ($resultado->fetchColumn() == 0) {
    print "    <p class=\"aviso\">Any records.</p>\n";
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
        print "    <p class=\"aviso\">Error en la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        print "    <form action=\"$_SERVER[PHP_SELF]\" method=\"$cfg[formMethod]\">\n";
        print "      <p class=\"parrafo\">Select the records for delete:</p>\n";
        print "\n";
        print "      <table class=\"conborde franjas\">\n";
        print "        <thead>\n";
        print "          <tr>\n";
        print "            <th>Delete</th>\n";
        print "            <th>\n";
        print "              <button name=\"ordena\" value=\"usuario ASC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/down.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"20\" height=\"22\">\n";
        print "              </button>\n";
        print "              User\n";
        print "              <button name=\"ordena\" value=\"usuario DESC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/up.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"20\" height=\"22\">\n";
        print "              </button>\n";
        print "            </th>\n";
        print "            <th>\n";
        print "              <button name=\"ordena\" value=\"password ASC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/down.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"20\" height=\"22\">\n";
        print "              </button>\n";
        print "              Password\n";
        print "              <button name=\"ordena\" value=\"password DESC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/up.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"20\" height=\"22\">\n";
        print "              </button>\n";
        print "            </th>\n";
        print "          </tr>\n";
        print "        </thead>\n";
        foreach ($resultado as $registro) {
            print "        <tr>\n";
            if (isset($id[$registro["id"]])) {
                print "          <td class=\"centrado\"><input type=\"checkbox\" name=\"id[$registro[id]]\" checked></td>\n";
            } else {
                print "          <td class=\"centrado\"><input type=\"checkbox\" name=\"id[$registro[id]]\"></td>\n";
            }
            print "          <td>$registro[usuario]</td>\n";
            print "          <td>$registro[password]</td>\n";
            print "        </tr>\n";
        }
        print "      </table>\n";
        print "\n";
        print "      <p>\n";
        print "        <input type=\"submit\" value=\"Delete Record\" formaction=\"borrar-2.php\" class=\"botonform\">\n";
        print "        <input type=\"reset\" value=\"Restart form\" class=\"botonform\">\n";
        print "      </p>\n";
        print "    </form>\n";
    }
}

pie();
