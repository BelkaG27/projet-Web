<?php

require_once '../classes/RecetteDB.php';

$DB = new RecetteDB();
session_start();


if(!empty(($_POST["name"]))){
    $_SESSION['erreur']="";
    $name1 = $_POST["name"];

    $DB->ajouterTag($name1);
    header("Location:index.php");
    exit();

}else{
    $_SESSION['erreur']="Le nom est vide !";
    header("Location:tag_add.php");
    exit();
}