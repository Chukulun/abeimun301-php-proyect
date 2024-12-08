<?php


require_once "../../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Users - Search 2", MENU_USUARIOS, PROFUNDIDAD_2);

$usuario  = recoge("usuario");
$ordena = recoge("ordena", "", $cfg["tablaUsuariosColumnasOrden"][0]);  // Si solo deseas el primer valor


$usuarioOk  = false;

if (mb_strlen($usuario, "UTF-8") > $cfg["formUsuariosMaxUsuario"]) {
    print "    <p class=\"aviso\">The number of user cant have more than $cfg[formUsuariosMaxUsuario] characters.</p>\n";
    print "\n";
} else {
    $usuarioOk = true;
}

// Comprobamos si existen registros con las condiciones de búsqueda recibidas
$registrosEncontradosOk = false;

if ($usuarioOk) {
    $consulta = "SELECT COUNT(*) FROM $cfg[tablaUsuarios]
                 WHERE usuario LIKE :usuario";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":usuario" => "%$usuario%"])) {
        print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif ($resultado->fetchColumn() == 0) {
        print "    <p class=\"aviso\">Any records found.</p>\n";
    } else {
        $registrosEncontradosOk = true;
    }
}

// Si todas las comprobaciones han tenido éxito ...
if ($usuarioOk && $registrosEncontradosOk) {
    // Seleccionamos todos los registros con las condiciones de búsqueda recibidas
    $consulta = "SELECT * FROM $cfg[tablaUsuarios]
                 WHERE usuario LIKE :usuario
                 ORDER BY $ordena";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":usuario" => "%$usuario%"])) {
        print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        print "    <form action=\"$_SERVER[PHP_SELF]\" method=\"$cfg[formMethod]\">\n";
        print "      <p>\n";
        print "        <input type=\"hidden\" name=\"usuario\" value=\"$usuario\">\n";
        print "      </p>\n";
        print "\n";
        print "      <p class=\"parrafo\">Records founded:</p>\n";
        print "\n";
        print "      <table class=\"conborde franjas\">\n";
        print "        <thead>\n";
        print "          <tr>\n";
        print "            <th>\n";
        print "              <button name=\"ordena\" value=\"usuario ASC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/down.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"15\" height=\"12\">\n";
        print "              </button>\n";
        print "              User\n";
        print "              <button name=\"ordena\" value=\"usuario DESC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/up.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"15\" height=\"12\">\n";
        print "              </button>\n";
        print "            </th>\n";
        print "            <th>\n";
        print "              <button name=\"ordena\" value=\"password ASC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/down.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"15\" height=\"12\">\n";
        print "              </button>\n";
        print "              Password\n";
        print "              <button name=\"ordena\" value=\"password DESC\" class=\"boton-invisible\">\n";
        print "                <img src=\"../../img/up.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"15\" height=\"12\">\n";
        print "              </button>\n";
        print "            </th>\n";
        print "          </tr>\n";
        print "        </thead>\n";
        foreach ($resultado as $registro) {
            print "        <tr>\n";
            print "          <td>$registro[usuario]</td>\n";
            print "          <td>$registro[password]</td>\n";
            print "        </tr>\n";
        }
        print "      </table>\n";
        print "    </form>\n";
    }
}

pie();
