<?php

require_once "../../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Users - Modifify 2", MENU_USUARIOS, PROFUNDIDAD_2);

$id = recoge("id");

// Comprobamos el dato recibido
$idOk = false;

if ($id == "") {
    print "    <p class=\"aviso\">No records selected..</p>\n";
} else {
    $idOk = true;
}

// Comprobamos que el registro con el id recibido existe en la base de datos
$registroEncontradoOk = false;

if ($idOk) {
    $consulta = "SELECT COUNT(*) FROM $cfg[tablaUsuarios]
                 WHERE id = :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":id" => $id])) {
        print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif ($resultado->fetchColumn() == 0) {
        print "    <p class=\"aviso\">No records founded.</p>\n";
    } else {
        $registroEncontradoOk = true;
    }
}

// Comprobamos que el registro con el id recibido no es el registro del usuario root
$registroNoRootOk = false;

if ($idOk && $registroEncontradoOk) {
    $consulta = "SELECT * FROM $cfg[tablaUsuarios]
                 WHERE id = :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":id" => $id])) {
        print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        $registro = $resultado->fetch();
        if ($registro["usuario"] == $cfg["rootName"] && !$cfg["rootPasswordModificable"]) {
            print "    <p class=\"aviso\">This user cant be modified.</p>\n";
        } else {
            $registroNoRootOk = true;
        }
    }
}

// Si todas las comprobaciones han tenido éxito ...
if ($idOk && $registroEncontradoOk && $registroNoRootOk) {
    // Recuperamos el registro con el id recibido para incluir sus valores en el formulario
    $consulta = "SELECT * FROM $cfg[tablaUsuarios]
                 WHERE id = :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":id" => $id])) {
        print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        $registro = $resultado->fetch();
        print "    <form action=\"modificar-3.php\" method=\"$cfg[formMethod]\">\n";
        print "      <p class=\"parrafo\">Modify the fields wanted:</p>\n";
        print "\n";
        print "      <table>\n";
        print "        <tr>\n";
        print "          <td class=\"parrafo\">User:</td>\n";
        print "          <td><input type=\"text\" name=\"usuario\" size=\"$cfg[formUsuariosTamUsuario]\" maxlength=\"$cfg[formUsuariosMaxUsuario]\" value=\"$registro[usuario]\" autofocus></td>\n";
        print "        </tr>\n";
        print "        <tr>\n";
        print "          <td class=\"parrafo\">Password:</td>\n";
        print "          <td><input type=\"text\" name=\"password\" size=\"$cfg[formUsuariosTamPassword]\" maxlength=\"$cfg[formUsuariosMaxPassword]\"></td>\n";
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
