<?php

require_once "../../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Usuarios - Borrar 2", MENU_USUARIOS, PROFUNDIDAD_2);

$id = recoge("id", []);

// Comprobamos el dato recibido
$idOk = false;

if ($id == []) {
    print "    <p class=\"aviso\">No record selected.</p>\n";
} else {
    $idOk = true;
}

// Si hemos recibido una matriz de ids de registros
if ($idOk) {
    // Recorremos la matriz para procesar cada uno de los ids recibidos
    foreach ($id as $indice => $valor) {
        // Comprobamos que el registro con el id recibido existe en la base de datos
        $registroEncontradoOk = false;

        $consulta = "SELECT COUNT(*) FROM $cfg[tablaUsuarios]
                     WHERE id = :indice";

        $resultado = $pdo->prepare($consulta);
        if (!$resultado) {
            print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
        } elseif (!$resultado->execute([":indice" => $indice])) {
            print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
        } elseif ($resultado->fetchColumn() == 0) {
            print "    <p class=\"aviso\">Cant find the record.</p>\n";
        } else {
            $registroEncontradoOk = true;
        }

        // Comprobamos que el usuario con el id recibido no es el usuario Administrador inicial
        $registroNoRootOk = false;

        if ($registroEncontradoOk) {
            $consulta = "SELECT COUNT(*) FROM $cfg[tablaUsuarios]
                         WHERE id = :indice
                         AND usuario = '$cfg[rootName]'";

            $resultado = $pdo->prepare($consulta);
            if (!$resultado) {
                print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
            } elseif (!$resultado->execute([":indice" => $indice])) {
                print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
            } elseif ($resultado->fetchColumn() > 0) {
                print "    <p class=\"aviso\">Cannot delete this user.</p>\n";
            } else {
                $registroNoRootOk = true;
            }
        }

        // Si todas las comprobaciones han tenido éxito ...
        if ($registroEncontradoOk && $registroNoRootOk) {
            // Borramos el registro con el id recibido
            $consulta = "DELETE FROM $cfg[tablaUsuarios]
                         WHERE id = :indice";

            $resultado = $pdo->prepare($consulta);
            if (!$resultado) {
                print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
            } elseif (!$resultado->execute([":indice" => $indice])) {
                print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
            } else {
                print "    <p class=\"parrafo\">Records deleted succesfully</p>\n";
            }
        }
    }
}

pie();
