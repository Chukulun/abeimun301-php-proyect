<?php


require_once "../../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Clasics - Modify 1", MENU_Clasicos, PROFUNDIDAD_2);

$ordena = recoge("ordena", "", $cfg["tablaClasicosColumnasOrden"][0]);
$id     = recoge("id");

// Comprobamos si la base de datos contiene registros
$hayRegistrosOk = false;

$consulta = "SELECT COUNT(*) FROM $cfg[tablaClasicos]";

$resultado = $pdo->query($consulta);
if (!$resultado) {
    print "    <p class=\"aviso\">query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
} elseif ($resultado->fetchColumn() == 0) {
    print "    <p class=\"aviso\">No records created yet.</p>\n";
} else {
    $hayRegistrosOk = true;
}

// Si todas las comprobaciones han tenido éxito ...
if ($hayRegistrosOk) {
    // Recuperamos todos los registros para mostrarlos en una <table>
    $consulta = "SELECT * FROM $cfg[tablaClasicos]
                 ORDER BY $ordena";

    $resultado = $pdo->query($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        print "    <form action=\"$_SERVER[PHP_SELF]\" method=\"$cfg[formMethod]\">\n";
        print "      <p class=\"parrafo\">Select the record to modify it:</p>\n";
        print "\n";
        print "      <table class=\"conborde franjas\">\n";
        print "        <thead>\n";
        print "          <tr>\n";
        print "            <th>Modify</th>\n";
        print "            <th>\n";
        print "              <button name=\"ordena\" value=\"titulo ASC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/down.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"15\" height=\"12\">\n";
        print "              </button>\n";
        print "              title\n";
        print "              <button name=\"ordena\" value=\"titulo DESC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/up.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"15\" height=\"12\">\n";
        print "              </button>\n";
        print "            </th>\n";
        print "            <th>\n";
        print "              <button name=\"ordena\" value=\"autor ASC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/down.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"15\" height=\"12\">\n";
        print "              </button>\n";
        print "              author\n";
        print "              <button name=\"ordena\" value=\"autor DESC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/up.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"15\" height=\"12\">\n";
        print "              </button>\n";
        print "            </th>\n";
        print "            <th>\n";
        print "              <button name=\"ordena\" value=\"editorial ASC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/down.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"15\" height=\"12\">\n";
        print "              </button>\n";
        print "              Editorial\n";
        print "              <button name=\"ordena\" value=\"editorial DESC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/up.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"15\" height=\"12\">\n";
        print "              </button>\n";
        print "            </th>\n";
        print "            <th>\n";
        print "              <button name=\"ordena\" value=\"idioma ASC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/down.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"15\" height=\"12\">\n";
        print "              </button>\n";
        print "              Language\n";
        print "              <button name=\"ordena\" value=\"idioma DESC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/up.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"15\" height=\"12\">\n";
        print "              </button>\n";
        print "            </th>\n";
        print "            <th>\n";
        print "              <button name=\"ordena\" value=\"paginas ASC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/down.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"20\" height=\"22\">\n";
        print "              </button>\n";
        print "              Nº Pages\n";
        print "              <button name=\"ordena\" value=\"paginas DESC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/up.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"20\" height=\"22\">\n";
        print "              </button>\n";
        print "            </th>\n";
        print "            <th>\n";
        print "              <button name=\"ordena\" value=\"disponibilidad ASC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/down.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"15\" height=\"12\">\n";
        print "              </button>\n";
        print "              Disponibility\n";
        print "              <button name=\"ordena\" value=\"disponibilidad DESC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/up.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"15\" height=\"12\">\n";
        print "              </button>\n";
        print "            </th>\n";
        print "          </tr>\n";
        print "        </thead>\n";
        foreach ($resultado as $registro) {
            print "        <tr>\n";
            if ($id == $registro["id"]) {
                print "          <td class=\"centrado\"><input type=\"radio\" name=\"id\" value=\"$registro[id]\" checked></td>\n";
            } else {
                print "          <td class=\"centrado\"><input type=\"radio\" name=\"id\" value=\"$registro[id]\"></td>\n";
            }
            print "          <td>$registro[titulo]</td>\n";
            print "          <td>$registro[autor]</td>\n";
            print "          <td>$registro[editorial]</td>\n";
            print "          <td>$registro[idioma]</td>\n";
            print "          <td>$registro[paginas]</td>\n";
            print "          <td>$registro[disponibilidad]</td>\n";
            print "        </tr>\n";
        }
        print "      </table>\n";
        print "\n";
        print "      <p>\n";
        print "        <input type=\"submit\" value=\"Modify record\" formaction=\"modificar-2.php\" class=\"botonform\">\n";
        print "        <input type=\"reset\" value=\"Restart form\" class=\"botonform\">\n";
        print "      </p>\n";
        print "    </form>\n";
    }
}

pie();
