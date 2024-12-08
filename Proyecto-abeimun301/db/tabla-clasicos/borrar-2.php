<?php
require_once "../../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Clasics - Delete 2", MENU_Clasicos, PROFUNDIDAD_2);

$id = recoge("id", []);

// Comprobamos el dato recibido
$idOk = false;

if ($id == []) {
    print "    <p class=\"aviso\">Any record selected.</p>\n";
} else {
    $idOk = true;
}

// Si hemos recibido una matriz de ids de registros
if ($idOk) {
    // Recorremos la matriz para procesar cada uno de los ids recibidos
    foreach ($id as $indice => $valor) {
        // Comprobamos que el registro con el id recibido existe en la base de datos
        $registroEncontradoOk = false;

        $consulta = "SELECT COUNT(*) FROM $cfg[tablaClasicos]
                     WHERE id = :indice";

        $resultado = $pdo->prepare($consulta);
        if (!$resultado) {
            print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
        } elseif (!$resultado->execute([":indice" => $indice])) {
            print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
        } elseif ($resultado->fetchColumn() == 0) {
            print "    <p class=\"aviso\">Cant find the record.</p>\n";
        } else {
            $registroEncontradoOk = true;
        }

        // Si todas las comprobaciones han tenido éxito ...
        if ($registroEncontradoOk) {
            // Borramos el registro con el id recibido
            $consulta = "DELETE FROM $cfg[tablaClasicos]
                         WHERE id = :indice";

            $resultado = $pdo->prepare($consulta);
            if (!$resultado) {
                print "    <p class=\"aviso\">Query Error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
            } elseif (!$resultado->execute([":indice" => $indice])) {
                print "    <p class=\"aviso\">Query Error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
            } else {
                print "    <p class=\"parrafo\">Record deleted succesfully</p>\n";
            }
        }
    }
}

pie();
