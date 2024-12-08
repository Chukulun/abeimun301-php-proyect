<?php

require_once "../../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Clasics - Amount - 1", MENU_Clasicos, PROFUNDIDAD_2);

$ordena = recoge("ordena", "", $cfg["tablaClasicosColumnasOrden"][0]);
$id     = recoge("id", []);

// Comprobamos si la base de datos contiene registros
$hayRegistrosOk = false;

$consulta = "SELECT COUNT(*) FROM $cfg[tablaClasicos]";

$resultado = $pdo->query($consulta);
if (!$resultado) {
    print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
} elseif ($resultado->fetchColumn() == 0) {
    print "    <p class=\"aviso\">any records founded</p>\n";
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
        print "    <p class=\"aviso\">query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        print "    <form action=\"sumar-2.php\" method=\"$cfg[formMethod]\">\n";
        print "      <p class=\"parrafo\">Select the records to amount the pages.</p>\n";
        print "\n";
        print "      <table class=\"conborde franjas\">\n";
        print "        <thead>\n";
        print "          <tr>\n";
        print "            <th>Select</th>\n";
        print "            <th>Titleo</th>\n";
        print "            <th>Author</th>\n";
        print "            <th>Editorial</th>\n";
        print "            <th>Number of pages</th>\n";
        print "            <th>Disponibility</th>\n";
        print "          </tr>\n";
        print "        </thead>\n";
        foreach ($resultado as $registro) {
            print "        <tr>\n";
            if (isset($id[$registro["id"]])) {
                print "          <td class=\"centrado\"><input type=\"checkbox\" name=\"id[$registro[id]]\" checked></td>\n";
            } else {
                print "          <td class=\"centrado\"><input type=\"checkbox\" name=\"id[$registro[id]]\"></td>\n";
            }
            print "          <td>$registro[titulo]</td>\n";
            print "          <td>$registro[autor]</td>\n";
            print "          <td>$registro[editorial]</td>\n";
            print "          <td>$registro[paginas]</td>\n";
            print "          <td>$registro[disponibilidad]</td>\n";
            print "        </tr>\n";
        }
        print "      </table>\n";
        print "\n";
        print "      <p>\n";
        print "        <input type=\"submit\" value=\"Amount pages\" class=\"botonform\">\n";
        print "        <input type=\"reset\" value=\"Restart form\" class=\"botonform\">\n";
        print "      </p>\n";
        print "    </form>\n";
    }
}

pie();
