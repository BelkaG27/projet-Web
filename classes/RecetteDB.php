<?php

class RecetteDB{
    
    private PDO $pdo;
    private $liste_recettes=[];
    private $tab_ingredients=[];
    private $tab_tags=[];

    public function __construct()
    {
        $db_name = "recette";
        $db_host = "127.0.0.1";
        $db_port = "3306";

        $db_user = "root" ; 
        $db_pwd = "" ;
        try{
            $dns = 'mysql:dbname=' . $db_name . ';host='. $db_host. ';port=' . $db_port;
            $this->pdo = new PDO($dns,$db_user,$db_pwd);
        }catch(\Exception $e){
            echo'<h1 style="color:red">EROR : '.$e->getMessage();
        }
    }

    public function getIdRecettesPageAcceuil():array
    {
        $statement = $this->pdo->prepare("SELECT * FROM recette");
        $statement->execute();

        $result = $statement->fetchAll();
        foreach($result as $key=>$recette){
            $liste_recettes[$key]=$recette['ID_Recette'];
        }
        return $liste_recettes;
    }

    public function getTagsPageAcceuil():array
    {
        $statement = $this->pdo->prepare("SELECT tag.Nom_TAG FROM tag");
        $statement->execute();

        $result = $statement->fetchAll();
        foreach($result as $key=>$recette){
            $liste_tags[$key]=$recette['Nom_TAG'];
        }
        return $liste_tags;
    }

    public function getNomRecette($id):string
    {
        $statement = $this->pdo->prepare("SELECT recette.Nom_Recette FROM recette where ID_Recette=:id");
        $statement->bindValue(":id",$id);
        $statement->execute();

        $result = $statement->fetchAll();
        return $result[0]["Nom_Recette"];
    }

    public function getImageRecette($id):string
    {
        $statement = $this->pdo->prepare("SELECT recette.Image_Recette FROM recette where ID_Recette=:id");
        $statement->bindValue(":id",$id);
        $statement->execute();

        $result = $statement->fetchAll();
        return $result[0]["Image_Recette"];
    }

    public function getDescriptionRecette($id):string
    {
        $statement = $this->pdo->prepare("SELECT recette.Description FROM recette where ID_Recette=:id");
        $statement->bindValue(":id",$id);
        $statement->execute();

        $result = $statement->fetchAll();
        return $result[0]["Description"];
    }


    public function getTagsRecette($id):array
    {
        $statement = $this->pdo->prepare("SELECT tag.Nom_TAG FROM tag JOIN recette_tag ON recette_tag.ID_TAG = tag.ID_TAG where recette_tag.ID_Recette=:id");
        $statement->bindValue(":id",$id);
        $statement->execute();

        $result = $statement->fetchAll();
        foreach($result as $key=>$tag){
            $liste_tags[$key]=$tag['Nom_TAG'];
        }
        return $liste_tags;
    }

    public function getIngredientsRecette($id):array
    {
        $statement = $this->pdo->prepare("SELECT ingredient.Nom_Ingredient,ingredient.Image_Ingredient FROM ingredient JOIN recette_ingredient ON recette_ingredient.ID_Ingredient = ingredient.ID_Ingredient where recette_ingredient.ID_Recette=:id");
        $statement->bindValue(":id",$id);
        $statement->execute();

        $result = $statement->fetchAll();
        foreach($result as $key=>$ingredient){
            $liste_nom_ingredient[$key]=$ingredient['Nom_Ingredient'];
            $liste_image_ingredient[$key]=$ingredient['Image_Ingredient'];
        }
        return $liste_ingredient=["nom"=>$liste_nom_ingredient,"image"=>$liste_image_ingredient];
    }

    public function getIngredientsRecette_pageRecipe($id):array{
        $statement = $this->pdo->prepare("SELECT ingredient.Nom_Ingredient,ingredient.Image_Ingredient FROM ingredient JOIN recette_ingredient ON recette_ingredient.ID_Ingredient = ingredient.ID_Ingredient where recette_ingredient.ID_Recette=:id");
        $statement->bindValue(":id",$id);
        $statement->execute();

        $result = $statement->fetchAll();
        $liste_ingredient = [];
        foreach($result as $key=>$ingredient){
            $liste_ingredient[$key]=["name"=>$ingredient['Nom_Ingredient'],"image"=>$ingredient['Image_Ingredient']];
        }
        return $liste_ingredient;
    }
}