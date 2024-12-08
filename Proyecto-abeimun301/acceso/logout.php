<?php

require_once "../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

session_destroy();

header("Location:../index.php");
