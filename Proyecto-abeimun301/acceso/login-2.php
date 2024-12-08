<?php

require_once "../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

$usuario  = recoge("usuario");
$password = recoge("password");

// Comprobamos los datos recibidos procedentes de un formulario
$usuarioOk  = false;
$passwordOk = false;

if (mb_strlen($usuario, "UTF-8") > $cfg["formUsuariosMaxUsuario"]) {
    header("Location:login-1.php?aviso=user name cant have more than $cfg[formUsuariosMaxUsuario] characters.");
} else {
    $usuarioOk = true;
}

if (mb_strlen($password, "UTF-8") > $cfg["formUsuariosMaxPassword"]) {
    header("Location:login-1.php?aviso=Password cant have more than $cfg[formUsuariosMaxPassword] characters.");
} else {
    $passwordOk = true;
}

// Comprobamos que el usuario recibido con la contraseña recibida existe en la base de datos
$passwordCorrectoOk = false;

if ($usuarioOk && $passwordOk) {
    $consulta = "SELECT COUNT(*) FROM $cfg[tablaUsuarios]
                 WHERE usuario = :usuario
                 AND password = :password";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        header("Location:login-1.php?aviso=Query Error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}");
    } elseif (!$resultado->execute([":usuario" => $usuario, ":password" => encripta($password)])) {
        header("Location:login-1.php?aviso=Query Error. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}");
    } elseif ($resultado->fetchColumn() == 0) {
        header("Location:login-1.php?aviso=Error: username or passswords incorrects.");
    } else {
        $passwordCorrectoOk = true;
    }
}

// Si todas las comprobaciones han tenido éxito ...
if ($usuarioOk && $passwordOk && $passwordCorrectoOk) {
    // Creamos la variable de sesión "conectado"
    $_SESSION["conectado"] = true;

    header("Location:../index.php");
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


?>
