<?php

require_once "../../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Clasics - Modify 3", MENU_Clasicos, PROFUNDIDAD_2);

$titulo    = recoge("titulo");
$autor     = recoge("autor");
$editorial = recoge("editorial");
$idioma    = recoge("idioma");
$disponibilidad = recoge("disponibilidad");
$paginas = recoge ("paginas");
$id        = recoge("id");

// Comprobamos los datos recibidos procedentes de un formulario
$tituloOk = false;
$autorOk = false;
$editorialOk = false;
$idiomaOk = false;
$disponibilidadOk = false;
$idOk = false;

if (mb_strlen($titulo, "UTF-8") > $cfg["formClasicosMaxTitulo"]) {
    print "    <p class=\"aviso\">title cant have more than $cfg[formClasicosMaxTitulo] characters.</p>\n";
    print "\n";
} else {
    $tituloOk = true;
}

if (mb_strlen($autor, "UTF-8") > $cfg["formClasicosMaxAutor"]) {
    print "    <p class=\"aviso\">author cant have more than $cfg[formClasicosMaxAutor] characters.</p>\n";
    print "\n";
} else {
    $autorOk = true;
}

if (mb_strlen($editorial, "UTF-8") > $cfg["formClasicosMaxEditorial"]) {
    print "    <p class=\"aviso\">Editorial cant have more than $cfg[formClasicosMaxEditorial] characters.</p>\n";
    print "\n";
} else {
    $editorialOk = true;
}

if (mb_strlen($idioma, "UTF-8") > $cfg["formClasicosMaxIdioma"]) {
    print "    <p class=\"aviso\">Language cant have more than $cfg[formClasicosMaxIdioma] characters.</p>\n";
    print "\n";
} else {
    $idiomaOk = true;
}

if (mb_strlen($disponibilidad, "UTF-8") > $cfg["formClasicosMaxDisponibilidad"]) {
    print "    <p class=\"aviso\">disponibility cant have morhe than $cfg[formClasicosMaxDisponibilidad] characters.</p>\n";
    print "\n";
} else {
    $disponibilidadOk = true;
}

if ($id == "") {
    print "    <p class=\"aviso\">Any record selected</p>\n";
} else {
    $idOk = true;
}

// Comprobamos que no se intenta crear un registro vacío
$registroNoVacioOk = false;

if ($tituloOk && $autorOk && $editorialOk && $idiomaOk && $idOk) {
    if ($titulo == "" && $autor == "" && $editorial == "" && $idioma == "" && $disponibilidad == "") {
        print "    <p class=\"aviso\">fill all the fields. Record unsave.</p>\n";
        print "\n";
    } else {
        $registroNoVacioOk = true;
    }
}

// Comprobamos que el registro con el id recibido existe en la base de datos
$registroEncontradoOk = false;

if ($tituloOk && $autorOk && $editorialOk && $idiomaOk && $idOk && $registroNoVacioOk) {
    $consulta = "SELECT COUNT(*) FROM $cfg[tablaClasicos]
                 WHERE id = :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":id" => $id])) {
        print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif ($resultado->fetchColumn() == 0) {
        print "    <p class=\"aviso\">Cant find the record.</p>\n";
    } else {
        $registroEncontradoOk = true;
    }
}

// Comprobamos que no se intenta crear un registro idéntico a uno que ya existe
$registroDistintoOk = false;

if ($tituloOk && $autorOk && $editorialOk && $idiomaOk && $idOk && $registroNoVacioOk && $registroEncontradoOk) {
    $consulta = "SELECT COUNT(*) FROM $cfg[tablaClasicos]
                 WHERE titulo = :titulo
                 AND autor = :autor
                 AND editorial = :editorial
                 AND idioma = :idioma
                 AND disponibilidad = :disponibilidad
                 AND paginas = :paginas
                 AND id <> :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([
        ":titulo" => $titulo,
        ":autor" => $autor,
        ":editorial" => $editorial,
        ":idioma" => $idioma,
        ":disponibilidad" => $disponibilidad,
        ":paginas" => $paginas,
        ":id" => $id
    ])) {
        print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif ($resultado->fetchColumn() > 0) {
        print "    <p class=\"aviso\">already exist a record with that values.</p>\n";
    } else {
        $registroDistintoOk = true;
    }
}

// Si todas las comprobaciones han tenido éxito ...
if ($tituloOk && $autorOk && $editorialOk && $idiomaOk && $idOk && $registroNoVacioOk && $registroEncontradoOk && $registroDistintoOk) {
    $consulta = "UPDATE $cfg[tablaClasicos]
                 SET titulo = :titulo, autor = :autor, editorial = :editorial, idioma = :idioma, disponibilidad = :disponibilidad, paginas = :paginas
                 WHERE id = :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([
        ":titulo" => $titulo,
        ":autor" => $autor,
        ":editorial" => $editorial,
        ":idioma" => $idioma,
        ":disponibilidad" => $disponibilidad,
        ":paginas" => $paginas,
        ":id" => $id
    ])) {
        print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        print "    <p class=\"parrafo\">Record modified succesfully. </p>\n";
    }
}

pie();
