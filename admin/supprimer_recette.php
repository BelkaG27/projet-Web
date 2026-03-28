<?php

require_once '../classes/RecetteDB.php';

$DB = new RecetteDB();
session_start();


if(!empty(($_POST["name"]))){
    $_SESSION['erreur']="";
    $nom = $_POST["name"];

    $DB->supprimerRecette($nom);
    header("Location:index.php");
    exit();

}else{
    $_SESSION['erreur']="Le nom est vide !";
    header("Location:tag_delete.php");
    exit();
}