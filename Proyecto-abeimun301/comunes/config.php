<?php

// VARIABLES CONFIGURABLES POR EL ADMINISTRADOR DE LA APLICACIÓN

// Nombre de sesión

$cfg["sessionName"] = "proyecto-abeimun301";         // Nombre de sesión

// Base de datos utilizada por la aplicación

$cfg["dbMotor"] = MYSQL;                                   // Valores posibles: MYSQL o SQLITE

// Configuración para MySQL

$cfg["mysqlHost"]     = "localhost";                        // Nombre de host
$cfg["mysqlUser"]     = "abeimun301";           // Nombre de usuario
$cfg["mysqlPassword"] = "abeimun301";                                 // Contraseña de usuario
$cfg["mysqlDatabase"] = "bd_w3_abm";           // Nombre de la base de datos

// Tamaño de los campos en la tabla Usuarios

$cfg["tablaUsuariosTamUsuario"]  = 20;                      // Tamaño de la columna Usuarios > Nombre de usuario
$cfg["tablaUsuariosTamPassword"] = 64;                      // Tamaño de la columna Usuarios > Contraseña de usuario (cifrada)

// Tamaño de los controles en los formularios

$cfg["formUsuariosTamUsuario"]  = $cfg["tablaUsuariosTamUsuario"];  // Tamaño de la caja de texto Usuarios > Nombre de usuario
$cfg["formUsuariosTamPassword"] = 20;                               // Tamaño de la caja de texto Usuarios > Contraseña

// Tamaño máximo admitido por los controles en los formularios

$cfg["formUsuariosMaxUsuario"]  = $cfg["tablaUsuariosTamUsuario"];   // Tamaño máximo admitido por la caja de texto Usuarios > Nombre de usuario
$cfg["formUsuariosMaxPassword"] = $cfg["formUsuariosTamPassword"];   // Tamaño máximo admitido por la caja de texto Usuarios > Contraseña

// Tamaño de los campos en la tabla Clasicos

$cfg["tablaClasicosTamTitulo"]    = 60;                     // Tamaño de la columna Clasicos > Titulo
$cfg["tablaClasicosTamAutor"]     = 60;                     // Tamaño de la columna Clasicos > Autor
$cfg["tablaClasicosTamEditorial"] = 60;                     // Tamaño de la columna Clasicos > Editorial
$cfg["tablaClasicosTamIdioma"]  = 60;                     // Tamaño de la columna Clasicos > Idioma
$cfg["tablaClasicosTamDisponibilidad"] = 5;
$cfg["tablaClasicosTamPaginas"] = 6; // Tamaño máximo: suficiente para números de hasta 99,999 páginas.

// Tamaño de los controles en los formularios

$cfg["formClasicosTamTitulo"]    = $cfg["tablaClasicosTamTitulo"];     // Tamaño de la caja de texto Clasicos > Nombre
$cfg["formClasicosTamAutor"] = $cfg["tablaClasicosTamAutor"];  // Tamaño de la caja de texto Clasicos > Apellidos
$cfg["formClasicosTamEditorial"]  = $cfg["tablaClasicosTamEditorial"];   // Tamaño de la caja de texto Clasicos > Teléfono
$cfg["formClasicosTamIdioma"]    = $cfg["tablaClasicosTamIdioma"]; 
$cfg["formClasicosTamDisponibilidad"]    = $cfg["tablaClasicosTamDisponibilidad"];
$cfg["formClasicosTamPaginas"]    = $cfg["tablaClasicosTamPaginas"];  

// Tamaño máximo admitido por los controles en los formularios

$cfg["formClasicosMaxTitulo"]    = $cfg["tablaClasicosTamTitulo"];     // Tamaño máximo admitido por la caja de texto Clasicos > Nombre
$cfg["formClasicosMaxAutor"] = $cfg["tablaClasicosTamAutor"];  // Tamaño máximo admitido por la caja de texto Clasicos > Apellidos
$cfg["formClasicosMaxEditorial"]  = $cfg["tablaClasicosTamEditorial"];   // Tamaño máximo admitido por la caja de texto Clasicos > Teléfono
$cfg["formClasicosMaxIdioma"]    = $cfg["tablaClasicosTamIdioma"];     // Tamaño máximo admitido por la caja de texto Clasicos > Correo
$cfg["formClasicosMaxDisponibilidad"]    = $cfg["tablaClasicosTamDisponibilidad"];
$cfg["formClasicosMaxPaginas"]    = $cfg["tablaClasicosTamPaginas"];
// Número máximo de registros en las tablas

$cfg["tablaUsuariosMaxReg"] = 20;                           // Número máximo de registros en la tabla Usuarios
$cfg["tablaClasicosMaxReg"] = 20;                           // Número máximo de registros en la tabla Clasicos

// Usuario Administrador de la aplicación

$cfg["rootName"]      = "root";                             // Nombre del Usuario Administrador de la aplicación
$cfg["rootPassword"]  = "4813494d137e1631bba301d5acab6e7bb7aa74ce1185d456565ef51d737677b2";  // Contraseña encriptada del Usuario Administrador de la aplicación
$cfg["hashAlgorithm"] = "sha256";                           // Algoritmo hash para encriptar la contraseña de usuario
                                                            
$cfg["rootPasswordModificable"] = false;                    // Contraseña del usuario Administrador se puede cambiar o no

// Método de envío de formularios

$cfg["formMethod"] = "get";                                 // Valores posibles: get o post
