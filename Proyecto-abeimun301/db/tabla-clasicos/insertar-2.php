<?php

require_once "../../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Clasics - Add 2", MENU_Clasicos, PROFUNDIDAD_2);

$titulo    = recoge("titulo");
$autor     = recoge("autor");
$editorial = recoge("editorial");
$idioma    = recoge("idioma");
$disponibilidad = recoge("disponibilidad"); 
$num_paginas = recoge("paginas");


// Comprobamos los datos recibidos procedentes de un formulario
$tituloOk    = false;
$autorOk = false;
$editorialOk  = false;
$idiomaOk    = false;
$num_paginasOk = false;

if (mb_strlen($titulo, "UTF-8") > $cfg["formClasicosMaxTitulo"]) {
    print "    <p class=\"aviso\">the title cant have more than $cfg[formClasicosMaxtitulo] characters.</p>\n";
    print "\n";
} else {
    $tituloOk = true;
}

if (mb_strlen($autor, "UTF-8") > $cfg["formClasicosMaxAutor"]) {
    print "    <p class=\"aviso\">author cant have more than $cfg[formClasicosMaxautor] characters.</p>\n";
    print "\n";
} else {
    $autorOk = true;
}

if (mb_strlen($editorial, "UTF-8") > $cfg["formClasicosMaxEditorial"]) {
    print "    <p class=\"aviso\">editorial cant have more than $cfg[formClasicosMaxeditorial] characters.</p>\n";
    print "\n";
} else {
    $editorialOk = true;
}

if (mb_strlen($idioma, "UTF-8") > $cfg["formClasicosMaxIdioma"]) {
    print "    <p class=\"aviso\">Language cant have more than $cfg[formClasicosMaxidioma] characters.</p>\n";
    print "\n";
} else {
    $idiomaOk = true;
}
if (mb_strlen($num_paginas, "UTF-8") > $cfg["formClasicosMaxPaginas"]) {
    print "    <p class=\"aviso\">pages cant have more than $cfg[formClasicosMaxPaginas] characters.</p>\n";
    print "\n";
} else {
    $num_paginasOk = true;
}

// Comprobamos que no se intenta crear un registro vacío
$registroNoVacioOk = false;

if ($tituloOk && $autorOk && $editorialOk && $idiomaOk && $num_paginas) {
    if ($titulo == "" && $autor == "" && $editorial == "" && $idioma == "" && $num_paginas == "") {
        print "    <p class=\"aviso\">fill the fields. The record is unsave.</p>\n";
        print "\n";
    } else {
        $registroNoVacioOk = true;
    }
}

// Comprobamos que no se intenta crear un registro idéntico a uno que ya existe
$registroDistintoOk = false;

if ($tituloOk && $autorOk && $editorialOk && $idiomaOk && $registroNoVacioOk) {
    $consulta = "SELECT COUNT(*) FROM $cfg[tablaClasicos]
                 WHERE titulo = :titulo
                 AND autor = :autor
                 AND editorial = :editorial
                 AND idioma = :idioma
                 AND paginas = paginas";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Equery error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":titulo" => $titulo, ":autor" => $autor, ":editorial" => $editorial, ":idioma" => $idioma])) {
        print "    <p class=\"aviso\">query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif ($resultado->fetchColumn() > 0) {
        print "    <p class=\"aviso\">The record already exist.</p>\n";
    } else {
        $registroDistintoOk = true;
    }
}

// Comprobamos si se ha alcanzado el número máximo de registros en la tabla
$limiteRegistrosOk = false;

if ($tituloOk && $autorOk && $editorialOk && $idiomaOk && $registroNoVacioOk && $registroDistintoOk) {
    $consulta = "SELECT COUNT(*) FROM $cfg[tablaClasicos]";

    $resultado = $pdo->query($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error en la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif ($resultado->fetchColumn() >= $cfg["tablaClasicosMaxReg"]) {
        print "    <p class=\"aviso\">number of maximum records reached.</p>\n";
        print "\n";
        print "    <p class=\"aviso\">Please, delete at least one record to create a new one.</p>\n";
    } else {
        $limiteRegistrosOk = true;
    }
}

// Si todas las comprobaciones han tenido éxito ...
if ($tituloOk && $autorOk && $editorialOk && $idiomaOk && $registroNoVacioOk && $registroDistintoOk && $limiteRegistrosOk) {
    // Insertamos el registro en la tabla
    $consulta = "INSERT INTO $cfg[tablaClasicos]
                 (titulo, autor, editorial, idioma, disponibilidad, paginas)
                 VALUES (:titulo, :autor, :editorial, :idioma, :disponibilidad, :paginas)";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Query error.SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([
        ":titulo" => $titulo,
        ":autor" => $autor,
        ":editorial" => $editorial,
        ":idioma" => $idioma,
        ":disponibilidad" => $disponibilidad, // Añadido aquí
        ":paginas" => $num_paginas
    ])) {
        print "    <p class=\"aviso\">query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        print "    <p class=\"parrafo\">Record created succesfully.</p>\n";
    }
}


pie();
