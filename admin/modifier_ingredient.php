<?php

require_once '../classes/RecetteDB.php';

$DB = new RecetteDB();
session_start();


if(!empty(($_POST["name"]))){
    $_SESSION['erreur']="";
    $name1 = $_POST["name"];
    $name2 = $_POST["old_name"];

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
    $DB->modifierIngredient($name1,$name2,$img);
    header("Location:ingredient_.php");
    exit();

}else{
    $_SESSION['erreur']="Le nom est vide !";
    header("Location:ingredient_edit_form.php");
    exit();
}