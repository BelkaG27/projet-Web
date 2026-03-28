<?php

require_once '../classes/RecetteDB.php';

$DB = new RecetteDB();
session_start();


if(!empty(($_POST["name"]))){
    $_SESSION['erreur']="";
    $name1 = $_POST["name"];
    $name2 = $_POST["old_name"];

    $DB->modifierTag($name1,$name2);
    header("Location:index.php");
    exit();

}else{
    $_SESSION['erreur']="Le nom est vide !";
    header("Location:tag_edit_form.php");
    exit();
}