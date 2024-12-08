<?php

function conectaDb()
{
    global $cfg;

    try {
        $tmp = new PDO("mysql:host=$cfg[mysqlHost];dbname=$cfg[mysqlDatabase];charset=utf8mb4", $cfg["mysqlUser"], $cfg["mysqlPassword"]);
    } catch (PDOException $e) {
        $tmp = new PDO("mysql:host=$cfg[mysqlHost];charset=utf8mb4", $cfg["mysqlUser"], $cfg["mysqlPassword"]);
    } catch (PDOException $e) {
        print "    <p class=\"aviso\">Error: Can't connect to database. {$e->getMessage()}</p>\n";
        exit;
    } finally {
        $tmp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        $tmp->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        return $tmp;
    }
}

// MYSQL: Borrado y creación de base de datos y tablas

function borraTodo()
{
    global $pdo, $cfg;

    print "    <p class=\"parrafo\">Database system: MySQL.</p>\n";
    print "\n";

    $consulta = "DROP DATABASE IF EXISTS $cfg[mysqlDatabase]";

    if (!$pdo->query($consulta)) {
        print "    <p class=\"aviso\">Error at delete database. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        print "    <p class=\"parrafo\">Database deleted succesfully (it was existed).</p>\n";
    }
    print "\n";

    $consulta = "CREATE DATABASE $cfg[mysqlDatabase]
                 CHARACTER SET utf8mb4
                 COLLATE utf8mb4_unicode_ci";

    if (!$pdo->query($consulta)) {
        print "    <p class=\"aviso\">Error at create Database. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        print "    <p class=\"parrafo\">Database created succesfully.</p>\n";
        print "\n";

        $consulta = "USE $cfg[mysqlDatabase]";

        if (!$pdo->query($consulta)) {
            print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
        } else {
            print "    <p class=\"parrafo\">Database selected succesfully</p>\n";
            print "\n";

            $consulta = "CREATE TABLE $cfg[tablaUsuarios] (
                         id INTEGER UNSIGNED AUTO_INCREMENT,
                         usuario VARCHAR($cfg[tablaUsuariosTamUsuario]),
                         password VARCHAR($cfg[tablaUsuariosTamPassword]),
                         PRIMARY KEY(id)
                         )";

            if (!$pdo->query($consulta)) {
                print "    <p class=\"aviso\">Error at create table $cfg[tablaUsuarios]. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
            } else {
                print "    <p class=\"parrafo\">Table Users created succesfully.</p>\n";
                print "\n";

                $consulta = "INSERT INTO $cfg[tablaUsuarios]
                             (id, usuario, password)
                             VALUES (1, '$cfg[rootName]', '$cfg[rootPassword]')";

                if (!$pdo->query($consulta)) {
                    print "    <p class=\"aviso\">Error al insertar el registro de usuario. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
                } else {
                    print "    <p class=\"parrafo\">Record of user created succesfully.</p>\n";
                }
            }
            print "\n";

            $consulta = "CREATE TABLE $cfg[tablaClasicos] (
                         id INTEGER UNSIGNED AUTO_INCREMENT,
                         titulo VARCHAR($cfg[tablaClasicosTamTitulo]),
                         autor VARCHAR($cfg[tablaClasicosTamAutor]),
                         editorial VARCHAR($cfg[tablaClasicosTamEditorial]),
                         idioma VARCHAR($cfg[tablaClasicosTamIdioma]),
                         paginas INTEGER UNSIGNED NOT NULL,
                         disponibilidad ENUM('Yes', 'No') NOT NULL DEFAULT 'Yes',
                         PRIMARY KEY(id)
                         )";

            if (!$pdo->query($consulta)) {
                print "    <p class=\"aviso\">Error at create the table $cfg[tablaClasicos]. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
            } else {
                print "    <p class=\"parrafo\">Table Clasics created succesfully.</p>\n";
            }
        }
    }
}

// MYSQL: Comprobación de existencia de las tablas

function existenTablas()
{
    global $pdo, $cfg;

    $existe = true;

    $consulta = "SELECT COUNT(*) FROM information_schema.schemata WHERE schema_name = '$cfg[mysqlDatabase]'";

    $resultado = $pdo->query($consulta);
    if (!$resultado) {
        $existe = false;
        print "    <p class=\"aviso\">Query Error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
        print "\n";
    } else {
        if ($resultado->fetchColumn() == 0) {
            $existe = false;
        } else {
            foreach ($cfg["dbTablas"] as $tabla) {
                $consulta = "SELECT COUNT(*) FROM information_schema.tables
                             WHERE table_schema = '$cfg[mysqlDatabase]'
                             AND table_name = '$tabla'";

                $resultado = $pdo->query($consulta);
                if (!$resultado) {
                    $existe = false;
                    print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
                    print "\n";
                } else {
                    if ($resultado->fetchColumn() == 0) {
                        $existe = false;
                    }
                }
            }
        }
    }
    return $existe;
}
