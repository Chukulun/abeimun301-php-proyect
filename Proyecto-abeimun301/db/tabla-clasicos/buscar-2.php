<?php

require_once "../../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Clasics - Search 2", MENU_Clasicos, PROFUNDIDAD_2);

$titulo    = recoge("titulo");
$autor = recoge("autor");
$editorial  = recoge("editorial");
$idioma    = recoge("idioma");
$disponibilidad = recoge("disponibilidad");
$ordena = recoge("ordena", "", $cfg["tablaClasicosColumnasOrden"][0]); 

// Comprobamos los datos recibidos procedentes de un formulario
$tituloOk    = false;
$autorOk = false;
$editorialOk  = false;
$idiomaOk    = false;

if (mb_strlen($titulo, "UTF-8") > $cfg["formClasicosMaxTitulo"]) {
    print "    <p class=\"aviso\">Title cant have more than $cfg[formClasicosMaxTitulo] characters.</p>\n";
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

// Comprobamos si existen registros con las condiciones de búsqueda recibidas
$registrosEncontradosOk = false;

if ($tituloOk && $autorOk && $editorialOk && $idiomaOk) {
    $consulta = "SELECT COUNT(*) FROM $cfg[tablaClasicos]
                 WHERE titulo LIKE :titulo
                 AND autor LIKE :autor
                 AND editorial LIKE :editorial
                 AND idioma LIKE :idioma";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":titulo" => "%$titulo%", ":autor" => "%$autor%", ":editorial" => "%$editorial%", ":idioma" => "%$idioma%"])) {
        print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif ($resultado->fetchColumn() == 0) {
        print "    <p class=\"aviso\">Cant find any record.</p>\n";
    } else {
        $registrosEncontradosOk = true;
    }
}

// Si todas las comprobaciones han tenido éxito ...
if ($tituloOk && $autorOk && $editorialOk && $idiomaOk && $registrosEncontradosOk) {
    // Seleccionamos todos los registros con las condiciones de búsqueda recibidas
    $consulta = "SELECT * FROM $cfg[tablaClasicos]
                 WHERE titulo LIKE :titulo
                 AND autor LIKE :autor
                 AND editorial LIKE :editorial
                 AND idioma LIKE :idioma
                 ORDER BY $ordena";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":titulo" => "%$titulo%", ":autor" => "%$autor%", ":editorial" => "%$editorial%", ":idioma" => "%$idioma%"])) {
        print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        print "    <form action=\"$_SERVER[PHP_SELF]\" method=\"$cfg[formMethod]\">\n";
        print "      <p>\n";
        print "        <input type=\"hidden\" name=\"titulo\" value=\"$titulo\">\n";
        print "        <input type=\"hidden\" name=\"autor\" value=\"$autor\">\n";
        print "        <input type=\"hidden\" name=\"editorial\" value=\"$editorial\">\n";
        print "        <input type=\"hidden\" name=\"idioma\" value=\"$idioma\">\n";
        print "      </p>\n";
        print "\n";
        print "      <p class=\"parrafo\">Records found:</p>\n";
        print "\n";
        print "      <table class=\"conborde franjas\">\n";
        print "        <thead>\n";
        print "          <tr>\n";
        print "            <th>\n";
        print "              <button name=\"ordena\" value=\"titulo ASC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/down.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"20\" height=\"22\">\n";
        print "              </button>\n";
        print "              title\n";
        print "              <button name=\"ordena\" value=\"titulo DESC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/up.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"20\" height=\"22\">\n";
        print "              </button>\n";
        print "            </th>\n";
        print "            <th>\n";
        print "              <button name=\"ordena\" value=\"autor ASC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/down.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"20\" height=\"22\">\n";
        print "              </button>\n";
        print "              author\n";
        print "              <button name=\"ordena\" value=\"autor DESC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/up.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"20\" height=\"22\">\n";
        print "              </button>\n";
        print "            </th>\n";
        print "            <th>\n";
        print "              <button name=\"ordena\" value=\"editorial ASC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/down.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"20\" height=\"22\">\n";
        print "              </button>\n";
        print "              Editorial\n";
        print "              <button name=\"ordena\" value=\"editorial DESC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/up.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"20\" height=\"22\">\n";
        print "              </button>\n";
        print "            </th>\n";
        print "            <th>\n";
        print "              <button name=\"ordena\" value=\"idioma ASC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/down.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"20\" height=\"22\">\n";
        print "              </button>\n";
        print "              Language\n";
        print "              <button name=\"ordena\" value=\"idioma DESC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/up.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"20\" height=\"22\">\n";
        print "              </button>\n";
        print "            </th>\n";
        print "            <th>\n";
        print "              <button name=\"ordena\" value=\"pagina ASC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/down.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"20\" height=\"22\">\n";
        print "              </button>\n";
        print "              Pages\n";
        print "              <button name=\"ordena\" value=\"pagina DESC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/up.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"20\" height=\"22\">\n";
        print "              </button>\n";
        print "            </th>\n";
        print "            <th>\n";
        print "              <button name=\"ordena\" value=\"disponibilidad ASC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/down.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"20\" height=\"22\">\n";
        print "              </button>\n";
        print "              Disponibility\n";
        print "              <button name=\"ordena\" value=\"disponibilidad DESC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/up.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"20\" height=\"22\">\n";
        print "              </button>\n";
        print "            </th>\n";
        print "          </tr>\n";
        print "        </thead>\n";
        foreach ($resultado as $registro) {
            print "        <tr>\n";
            print "          <td>$registro[titulo]</td>\n";
            print "          <td>$registro[autor]</td>\n";
            print "          <td>$registro[editorial]</td>\n";
            print "          <td>$registro[idioma]</td>\n";
            print "          <td>$registro[paginas]</td>\n";
            print "          <td>$registro[disponibilidad]</td>\n";
            print "        </tr>\n";
        }
        print "      </table>\n";
        print "    </form>\n";
    }
}

pie();
