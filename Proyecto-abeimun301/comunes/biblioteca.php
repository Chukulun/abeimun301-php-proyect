<?php

// Constantes configurables por el programador de la aplicación

define("SQLITE", 1);                        // Base de datos SQLITE
define("MYSQL", 2);                         // Base de datos MySQL

define("MENU_PRINCIPAL", 1);                // Menú principal sin conectar
define("MENU_VOLVER", 2);                   // Menú Volver a inicio
define("MENU_ADMINISTRADOR", 3);            // Menú Administrador
define("MENU_USUARIOS", 4);                 // Menú Usuarios
define("MENU_Clasicos", 5);                 // Menú Clasicos

define("PROFUNDIDAD_0", "");                // Profundidad de nivel de la página: directorio raíz
define("PROFUNDIDAD_1", "../");             // Profundidad de nivel de la página: subdirectorio
define("PROFUNDIDAD_2", "../../");          // Profundidad de nivel de la página: sub-subdirectorio

// Variables configurables por el administrador de la aplicación

require_once "config.php";

// Variables configurables por el programador de la aplicación

// Nombres de las tablas

$cfg["tablaClasicos"] = "Clasicos";         // Nombre de la tabla Clasicos
$cfg["tablaUsuarios"] = "usuarios";         // Nombre de la tabla Usuarios

$cfg["dbTablas"] = [
    $cfg["tablaClasicos"],
    $cfg["tablaUsuarios"],
];

// Valores de ordenación de las tablas

$cfg["tablaClasicosColumnasOrden"] = [
    "titulo ASC", "titulo DESC",
    "autor ASC", "autor DESC",
    "editorial ASC", "editorial DESC",
    "idioma ASC", "idioma DESC",
];

$cfg["tablaUsuariosColumnasOrden"] = [
    "usuario ASC", "usuario DESC",
    "password ASC", "password DESC",
];

// Carga Biblioteca específica de la base de datos utilizada

if ($cfg["dbMotor"] == SQLITE) {
    require_once "biblioteca-sqlite.php";
} elseif ($cfg["dbMotor"] == MYSQL) {
    require_once "biblioteca-mysql.php";
}

// Funciones comunes

function recoge($key, $type = "", $default = null, $allowed = null)
{
    if (!is_string($key) && !is_int($key) || $key == "") {
        trigger_error("Function recoge(): Argument #1 (\$key) must be a non-empty string or an integer", E_USER_ERROR);
    } elseif ($type !== "" && $type !== []) {
        trigger_error("Function recoge(): Argument #2 (\$type) is optional, but if provided, it must be an empty array or an empty string", E_USER_ERROR);
    } elseif (isset($default) && !is_string($default)) {
        trigger_error("Function recoge(): Argument #3 (\$default) is optional, but if provided, it must be a string", E_USER_ERROR);
    } elseif (isset($allowed) && !is_array($allowed)) {
        trigger_error("Function recoge(): Argument #4 (\$allowed) is optional, but if provided, it must be an array of strings", E_USER_ERROR);
    } elseif (is_array($allowed) && array_filter($allowed, function ($value) { return !is_string($value); })) {
        trigger_error("Function recoge(): Argument #4 (\$allowed) is optional, but if provided, it must be an array of strings", E_USER_ERROR);
    } elseif (!isset($default) && isset($allowed) && !in_array("", $allowed)) {
        trigger_error("Function recoge(): If argument #3 (\$default) is not set and argument #4 (\$allowed) is set, the empty string must be included in the \$allowed array", E_USER_ERROR);
    } elseif (isset($default, $allowed) && !in_array($default, $allowed)) {
        trigger_error("Function recoge(): If arguments #3 (\$default) and #4 (\$allowed) are set, the \$default string must be included in the \$allowed array", E_USER_ERROR);
    }

    if ($type == "") {
        if (!isset($_REQUEST[$key]) || (is_array($_REQUEST[$key]) != is_array($type))) {
            $tmp = "";
        } else {
            $tmp = trim(htmlspecialchars($_REQUEST[$key]));
        }
        if ($tmp == "" && !isset($allowed) || isset($allowed) && !in_array($tmp, $allowed)) {
            $tmp = $default ?? "";
        }
    } else {
        if (!isset($_REQUEST[$key]) || (is_array($_REQUEST[$key]) != is_array($type))) {
            $tmp = [];
        } else {
            $tmp = $_REQUEST[$key];
            array_walk_recursive($tmp, function (&$value) use ($default, $allowed) {
                $value = trim(htmlspecialchars($value));
                if ($value == "" && !isset($allowed) || isset($allowed) && !in_array($value, $allowed)) {
                    $value = $default ?? "";
                }
            });
        }
    }
    return $tmp;
}

function cabecera($texto, $menu, $profundidadDirectorio)
{
    print "<!DOCTYPE html>\n";
    print "<html lang=\"es\">\n";
    print "<head>\n";
    print "  <meta charset=\"utf-8\">\n";
    print "  <title>\n";
    print "    $texto. Library Abeimun.\n";
    print "  </title>\n";
    print "  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
    print "  <link rel=\"stylesheet\" href=\"{$profundidadDirectorio}comunes/style.css\" title=\"Color\">\n";
    print "</head>\n";
    print "\n";
    print "<body class=\"fondo\">\n";
    print "  <header>\n";
    print "    <h1>Library Abeimun301- $texto</h1>\n";
    print "\n";
    print "    <nav>\n";
    print "      <ul>\n";
    if (!isset($_SESSION["conectado"])) {
        if ($menu == MENU_PRINCIPAL) {
            print "        <li><a href=\"acceso/login-1.php\" class=\"cta\"><span>Connect</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";

        } elseif ($menu == MENU_VOLVER) {
            print "        <li><a href=\"../index.php\" class=\"cta\"><span>Back</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";

        } else {
            print "        <li>Error en menu selecction (not connected)</li>\n";
        }
    } else {
        if ($menu == MENU_PRINCIPAL) {
            print "        <li><a href=\"db/tabla-clasicos/index.php\" class=\"cta\"><span>Clasics</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";
            print "        <li><a href=\"db/tabla-usuarios/index.php\" class=\"cta\"><span>Users</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";
            print "        <li><a href=\"administrador/index.php\" class=\"cta\"><span>Administrator</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";
            print "        <li><a href=\"acceso/logout.php\" class=\"cta\"><span>Disconnect</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";
        } elseif ($menu == MENU_VOLVER) {
            print "        <li><a href=\"../../index.php\" class=\"cta\"><span>Back</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";
        } elseif ($menu == MENU_ADMINISTRADOR) {
            print "        <li><a href=\"../index.php\" class=\"cta\"><span>Back</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";
            print "        <li><a href=\"borrar-todo-1.php\" class=\"cta\"><span>Delete All</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";
        } elseif ($menu == MENU_USUARIOS) {
            print "        <li><a href=\"../../index.php\" class=\"cta\"><span>Back</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";
            print "        <li><a href=\"insertar-1.php\" class=\"cta\"><span>Add Record</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";
            print "        <li><a href=\"listar.php\" class=\"cta\"><span>List</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";
            print "        <li><a href=\"borrar-1.php\" class=\"cta\"><span>Delete</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";
            print "        <li><a href=\"buscar-1.php\" class=\"cta\"><span>Search</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";
            print "        <li><a href=\"modificar-1.php\" class=\"cta\"><span>Modify</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";
        } elseif ($menu == MENU_Clasicos) {
            print "        <li><a href=\"../../index.php\" class=\"cta\"><span>Back</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";
            print "        <li><a href=\"insertar-1.php\" class=\"cta\"><span>Add Record</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";
            print "        <li><a href=\"listar.php\" class=\"cta\"><span>List</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";
            print "        <li><a href=\"borrar-1.php\" class=\"cta\"><span>Delete</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";
            print "        <li><a href=\"buscar-1.php\" class=\"cta\"><span>Search</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";
            print "        <li><a href=\"modificar-1.php\" class=\"cta\"><span>Modify</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";
            print "        <li><a href=\"sumar-1.php\" class=\"cta\"><span>Amount</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";
            print "        <li><a href=\"total-1.php\" class=\"cta\"><span>Total</span><svg width=\"15px\" height=\"10px\" viewBox=\"0 0 13 10\"><path d=\"M1,5 L11,5\"></path><polyline points=\"8 1 12 5 8 9\"></polyline></svg></a></li>\n";
        } else {
            print "        <li>Error in menu selection (connected)</li>\n";
        }
    }
    print "      </ul>\n";
    print "    </nav>\n";
    print "  </header>\n";
    print "\n";
    print "  <main >\n";
}

function pie()
{
    print "  </main>\n";
    print "\n";

    print "    <footer>\n";

    print "    </footer>\n";

    print "</body>\n";
    print "</html>\n";
}

function encripta($cadena)
{
    global $cfg;

    return hash($cfg["hashAlgorithm"], $cadena);
}

?>
