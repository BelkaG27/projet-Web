<?php

require_once '../classes/RecetteDB.php';

$DB = new RecetteDB();
session_start();


if(!empty(($_POST["title"])) && !empty(($_POST["description"]))){
    $_SESSION['erreur']="";
    $name1 = $_POST["title"];
    $desc = $_POST["description"];

    $file = $_FILES['photo'];
    if($file['error']==0){
        $tmp_name = $file['tmp_name'];
        $name = $file['name'];

        move_uploaded_file($tmp_name,"../img/".$name);
        $img = $name;
    }else{
        echo'<h1 style="color:red">ERROR : importation du fichier</h1>';
        $img=null;
    }

    $ingredients = $_POST["ingredients"];
    $tags = $_POST['tags'];


    $DB->creerRecette($name1,$desc,$img,$ingredients,$tags);
    header("Location:index.php");
    exit();

}else if(empty($_POST["title"])){
    $_SESSION['erreur']="Le nom est vide !";
    header("Location:ingredient_add.php");
    exit();
}else if(empty($_POST["description"])){
    $_SESSION['erreur']="La description est vide !";
    header("Location:ingredient_add.php");
    exit();
}