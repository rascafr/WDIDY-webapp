<?php

  // Only modify this file to give access for all PHP files to SQL database !

  // bdd access
  $myServer = "localhost";
  $myUser = "cl50-wdidy";
  $myPass = "hadres11";
  $myDB = "cl50-wdidy";

  try {
    $bdd = new PDO('mysql:host='.$myServer.';dbname='.$myDB.';charset=utf8', $myUser, $myPass);
    $bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
  }

