<?php


require_once "../../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Users - Modify 3", MENU_USUARIOS, PROFUNDIDAD_2);

$usuario  = recoge("usuario");
$password = recoge("password");
$id       = recoge("id");

// Comprobamos los datos recibidos procedentes de un formulario
$usuarioOk  = false;
$passwordOk = false;
$idOk       = false;

if ($usuario == "") {
    print "    <p class=\"aviso\">Write a username</p>\n";
    print "\n";
} elseif (mb_strlen($usuario, "UTF-8") > $cfg["formUsuariosMaxUsuario"]) {
    print "    <p class=\"aviso\">Username cant have more than $cfg[formUsuariosMaxUsuario] characters.</p>\n";
    print "\n";
} else {
    $usuarioOk = true;
}

if (mb_strlen($password, "UTF-8") > $cfg["formUsuariosMaxPassword"]) {
    print "    <p class=\"aviso\">Password cant have more than $cfg[formUsuariosMaxPassword] characters.</p>\n";
    print "\n";
} else {
    $passwordOk = true;
}

if ($id == "") {
    print "    <p class=\"aviso\">No recrods selected.</p>\n";
} else {
    $idOk = true;
}

// Comprobamos que el registro con el id recibido existe en la base de datos
$registroEncontradoOk = false;

if ($usuarioOk && $passwordOk && $idOk) {
    $consulta = "SELECT COUNT(*) FROM $cfg[tablaUsuarios]
                 WHERE id = :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":id" => $id])) {
        print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif ($resultado->fetchColumn() == 0) {
        print "    <p class=\"aviso\">Record not founded.</p>\n";
    } else {
        $registroEncontradoOk = true;
    }
}

// Comprobamos que no se intenta crear un registro idéntico a uno que ya existe
$registroDistintoOk = false;

if ($usuarioOk && $passwordOk && $idOk && $registroEncontradoOk) {
    // La consulta cuenta los registros con un id diferente porque MySQL no distingue
    // mayúsculas de minúsculas y si en un registro sólo se cambian mayúsculas por
    // minúsculas MySQL diría que ya hay un registro como el que se quiere guardar.
    $consulta = "SELECT COUNT(*) FROM $cfg[tablaUsuarios]
                 WHERE usuario = :usuario
                 AND id <> :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":usuario" => $usuario, ":id" => $id])) {
        print "    <p class=\"aviso\">Query error SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif ($resultado->fetchColumn() > 0) {
        print "    <p class=\"aviso\">Exist a record with tame values. Doesn't update de record.</p>\n";
    } else {
        $registroDistintoOk = true;
    }
}

// Comprobamos que el usuario con el id recibido no es el usuario Administrador inicial
$registroNoRootOk = false;

if ($usuarioOk && $passwordOk && $idOk && $registroEncontradoOk && $registroDistintoOk) {
    $consulta = "SELECT * FROM $cfg[tablaUsuarios]
                 WHERE id = :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":id" => $id])) {
        print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        $registro = $resultado->fetch();
        if ($registro["usuario"] == $cfg["rootName"] && (!$cfg["rootPasswordModificable"] || $registro["usuario"] != $usuario)) {
            print "    <p class=\"aviso\">root user only is possible change the password</p>\n";
        } else {
            $registroNoRootOk = true;
        }
    }
}

if ($usuarioOk && $passwordOk && $idOk && $registroEncontradoOk && $registroDistintoOk && $registroNoRootOk) {
    $consulta = "UPDATE $cfg[tablaUsuarios]
                 SET usuario = :usuario, password = :password
                 WHERE id = :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":usuario" => $usuario, ":password" => encripta($password), ":id" => $id])) {
        print "    <p class=\"aviso\">Query error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        print "    <p class=\"parrafo\">Record updated succesfully.</p>\n";
    }
}

pie();
