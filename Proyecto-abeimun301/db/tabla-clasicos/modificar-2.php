<?php

require_once "../../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Clasics - Modify 2", MENU_Clasicos, PROFUNDIDAD_2);

$id = recoge("id");

// Comprobamos el dato recibido
$idOk = false;

if ($id == "") {
    print "    <p class=\"aviso\">No se ha seleccionado ningún registro.</p>\n";
} else {
    $idOk = true;
}

// Comprobamos que el registro con el id recibido existe en la base de datos
$registroEncontradoOk = false;

if ($idOk) {
    $consulta = "SELECT COUNT(*) FROM $cfg[tablaClasicos]
                 WHERE id = :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":id" => $id])) {
        print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif ($resultado->fetchColumn() == 0) {
        print "    <p class=\"aviso\">Cant find the record</p>\n";
    } else {
        $registroEncontradoOk = true;
    }
}

// Si todas las comprobaciones han tenido éxito ...
if ($idOk && $registroEncontradoOk) {
    // Recuperamos el registro con el id recibido para incluir sus valores en el formulario
    $consulta = "SELECT * FROM $cfg[tablaClasicos]
                 WHERE id = :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":id" => $id])) {
        print "    <p class=\"aviso\">Error al ejecutar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        $registro = $resultado->fetch();
        print "    <form action=\"modificar-3.php\" method=\"$cfg[formMethod]\">\n";
        print "      <p class=\"parrafo\">Modifique los campos que desee:</p>\n";
        print "\n";
        print "      <table>\n";
        print "        <tr>\n";
        print "          <td class=\"parrafo\">Title:</td>\n";
        print "          <td><input type=\"text\" name=\"titulo\" size=\"$cfg[formClasicosTamTitulo]\" maxlength=\"$cfg[formClasicosMaxTitulo]\" value=\"$registro[titulo]\" autofocus></td>\n";
        print "        </tr>\n";
        print "        <tr>\n";
        print "          <td class=\"parrafo\">Author:</td>\n";
        print "          <td><input type=\"text\" name=\"autor\" size=\"$cfg[formClasicosTamAutor]\" maxlength=\"$cfg[formClasicosMaxAutor]\" value=\"$registro[autor]\"></td>\n";
        print "        </tr>\n";
        print "        <tr>\n";
        print "          <td class=\"parrafo\">Editorial:</td>\n";
        print "          <td><input type=\"text\" name=\"editorial\" size=\"$cfg[formClasicosTamEditorial]\" maxlength=\"$cfg[formClasicosMaxEditorial]\" value=\"$registro[editorial]\"></td>\n";
        print "        </tr>\n";
        print "        <tr>\n";
        print "          <td class=\"parrafo\">Language:</td>\n";
        print "          <td><input type=\"text\" name=\"idioma\" size=\"$cfg[formClasicosTamIdioma]\" maxlength=\"$cfg[formClasicosMaxIdioma]\" value=\"$registro[idioma]\"></td>\n";
        print "        </tr>\n";
        print "        <tr>\n";
        print "          <td class=\"parrafo\">Number of pages</td>\n";
        print "          <td><input type=\"integer\" name=\"paginas\" size=\"$cfg[formClasicosTamPaginas]\" maxlength=\"$cfg[formClasicosMaxPaginas]\"></td>\n";
        print "        </tr>\n";
        print "          <td class=\"parrafo\">Disponibility:</td>\n";
        print "          <td>\n";
        print "            <select name=\"disponibilidad\" class=\"botonform\">\n";
        print "              <option value=\"Sí\"" . ($registro['disponibilidad'] == 'Sí' ? ' selected' : '') . ">Sí</option>\n";
        print "              <option value=\"No\"" . ($registro['disponibilidad'] == 'No' ? ' selected' : '') . ">No</option>\n";
        print "            </select>\n";
        print "          </td>\n";
        print "        </tr>\n";
        print "      </table>\n";
        print "\n";
        print "      <p>\n";
        print "        <input type=\"hidden\" name=\"id\" value=\"$id\">\n";
        print "        <input type=\"submit\" value=\"Update\" class=\"botonform\">\n";
        print "        <input type=\"reset\" value=\"Restart form\" class=\"botonform\">\n";
        print "      </p>\n";
        print "    </form>\n";
    }
}

pie();
