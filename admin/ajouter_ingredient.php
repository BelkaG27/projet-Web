<?php

require_once '../classes/RecetteDB.php';

$DB = new RecetteDB();
session_start();


if(!empty(($_POST["name"]))){
    $_SESSION['erreur']="";
    $name1 = $_POST["name"];

    $file = $_FILES['image'];
    if($file['error']==0){
        $tmp_name = $file['tmp_name'];
        $name = $file['name'];

        move_uploaded_file($tmp_name,"../img/".$name);
        $img = $name;
    }else{
        echo'<h1 style="color:red">ERROR : importation du fichier</h1>';
        $img=null;
    }
    $DB->ajouterIngredient($name1,$img);
    header("Location:index.php");
    exit();

}else{
    $_SESSION['erreur']="Le nom est vide !";
    header("Location:ingredient_add.php");
    exit();
}