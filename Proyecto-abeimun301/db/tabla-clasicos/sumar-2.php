<?php

require_once "../../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Clasics - Amount 2", MENU_Clasicos, PROFUNDIDAD_2);

$id = recoge("id", []);

// Comprobamos el dato recibido
$idOk = false;

if ($id == []) {
    print "    <p class=\"aviso\">any records selected</p>\n";
} else {
    $idOk = true;
}

// Si hemos recibido una matriz de ids de registros
if ($idOk) {
    $sumaPaginas = 0;

    // Recorremos la matriz para procesar cada uno de los ids recibidos
    foreach ($id as $indice => $valor) {
        // Comprobamos que el registro con el id recibido existe en la base de datos
        $registroEncontradoOk = false;

        $consulta = "SELECT paginas FROM $cfg[tablaClasicos]
                     WHERE id = :indice";

        $resultado = $pdo->prepare($consulta);
        if (!$resultado) {
            print "    <p class=\"aviso\">query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
        } elseif (!$resultado->execute([":indice" => $indice])) {
            print "    <p class=\"aviso\">query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
        } else {
            $pagina = $resultado->fetchColumn();
            if ($pagina !== false) {
                $sumaPaginas += $pagina;
                $registroEncontradoOk = true;
            } else {
                print "    <p class=\"aviso\">Cant find the record with ID $indice.</p>\n";
            }
        }
    }

    if ($registroEncontradoOk) {
        print "    <p class=\"parrafo\">The total amount of pages is: <strong>$sumaPaginas</strong>.</p>\n";
    }
}

pie();
