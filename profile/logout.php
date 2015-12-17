<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 17/12/2015
 * Time: 22:57
 */

session_start();
$_SESSION['IDuser'] = '';
unset($_SESSION['IDuser']);
$_SESSION['nameUser'] = '';
unset($_SESSION['nameUser']);
session_destroy(); // Triple security

header('Location: ../index.php');