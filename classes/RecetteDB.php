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

    public function getRecettes():array
    {
        $statement = $this->pdo->prepare("SELECT recette.ID_Recette,recette.Nom_Recette FROM recette");
        $statement->execute();

        $result = $statement->fetchAll();
        foreach($result as $key=>$recette){
            $liste_recettes[$key]=['id'=>$recette['ID_Recette'],'title'=>$recette['Nom_Recette']];
        }
        return $liste_recettes;
    }

    public function getIngredients():array
    {
        $statement = $this->pdo->prepare("SELECT ingredient.Nom_Ingredient,ingredient.ID_Ingredient FROM ingredient");
        $statement->execute();

        $result = $statement->fetchAll();
        foreach($result as $key=>$ingredient){
            $liste_ingredient[$key]=["id"=>$ingredient['ID_Ingredient'],"name"=>$ingredient['Nom_Ingredient']];
        }
        return $liste_ingredient;
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

    public function getTags():array
    {
        $statement = $this->pdo->prepare("SELECT tag.ID_TAG,tag.Nom_TAG FROM tag");
        $statement->execute();

        $result = $statement->fetchAll();
        foreach($result as $key=>$tag){
            $liste_tags[$key]=["id"=>$tag['ID_TAG'],"name"=>$tag['Nom_TAG']];
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

    public function ajouterIngredient($nom,$img)
    {   
        $statement = $this->pdo->prepare("SELECT ingredient.ID_Ingredient FROM  ingredient ORDER BY ID_Ingredient DESC LIMIT 1");
        $statement->execute();
        $result = $statement->fetchAll();
        $new_id = $result[0]["ID_Ingredient"]+1;

        $statement = $this->pdo->prepare("INSERT INTO ingredient(ingredient.ID_Ingredient,ingredient.Nom_Ingredient,ingredient.Image_Ingredient) VALUES (:id,:nom,:img)");
        $statement->bindValue(":id",$new_id);
        $statement->bindValue(":nom",$nom);
        $statement->bindValue(":img",$img);
        $statement->execute();
    }

    public function modifierIngredient($nom,$old_nom,$img)
    {   
        $statement = $this->pdo->prepare("UPDATE ingredient SET ingredient.Nom_Ingredient=:new,ingredient.Image_ingredient=:img WHERE ingredient.Nom_Ingredient=:old");
        $statement->bindValue(":new",$nom);
        $statement->bindValue(":old",$old_nom);
        $statement->bindValue(":img",$img);
        $statement->execute();
        
    }

    public function ajouterTag($nom)
    {   
        $statement = $this->pdo->prepare("SELECT tag.ID_TAG FROM tag ORDER BY ID_TAG DESC LIMIT 1");
        $statement->execute();
        $result = $statement->fetchAll();
        $new_id = $result[0]["ID_TAG"]+1;

        $statement = $this->pdo->prepare("INSERT INTO tag(tag.ID_TAG,tag.Nom_TAG) VALUES (:id,:nom)");
        $statement->bindValue(":id",$new_id);
        $statement->bindValue(":nom",$nom);
        $statement->execute();
    }

    public function modifierTag($nom,$old_nom)
    {   
        $statement = $this->pdo->prepare("UPDATE tag SET tag.Nom_TAG=:new WHERE tag.Nom_TAG=:old");
        $statement->bindValue(":new",$nom);
        $statement->bindValue(":old",$old_nom);
        $statement->execute();
        
    }

    public function supprimerTag($nom)
    {   
        $statement = $this->pdo->prepare("DELETE FROM tag WHERE tag.Nom_TAG=:nom");
        $statement->bindValue(":nom",$nom);
        $statement->execute();
        
    }

    public function creerRecette($nom,$desc,$img,$liste_ingredients,$liste_tags){
        $id_ingredients = [];
        $id_tags = [];
        foreach($liste_ingredients as $ingredient){
            $statement = $this->pdo->prepare("SELECT ingredient.ID_Ingredient FROM ingredient WHERE ingredient.Nom_Ingredient=:nom");
            $statement->bindValue(":nom",$ingredient);
            $statement->execute();

            $result = $statement->fetchAll();
            $id_ingredients[]=$result[0]['ID_Ingredient'];
        }

        foreach($liste_tags as $tag){
            $statement = $this->pdo->prepare("SELECT tag.ID_TAG FROM tag WHERE tag.Nom_TAG=:nom");
            $statement->bindValue(":nom",$tag);
            $statement->execute();

            $result = $statement->fetchAll();
            $id_tags[]=$result[0]['ID_TAG'];
        }

        $statement = $this->pdo->prepare("SELECT recette.ID_Recette FROM  recette ORDER BY ID_Recette DESC LIMIT 1");
        $statement->execute();
        $result = $statement->fetchAll();
        $new_id = $result[0]["ID_Recette"]+1;


        $statement = $this->pdo->prepare("INSERT INTO recette(recette.ID_Recette,recette.Nom_Recette,recette.Description,recette.Image_Recette) VALUES (:id,:nom,:desc,:img)");
        $statement->bindValue(":id",$new_id);
        $statement->bindValue(":nom",$nom);
        $statement->bindValue(":desc",$desc);
        $statement->bindValue(":img",$img);
        $statement->execute();

        foreach($id_ingredients as $ingredient){
            $statement = $this->pdo->prepare("INSERT INTO recette_ingredient(recette_ingredient.ID_Recette,recette_ingredient.ID_Ingredient) VALUES (:id1,:id2)");
            $statement->bindValue(":id1",$new_id);
            $statement->bindValue(":id2",$ingredient);
            $statement->execute();
        }

        foreach($id_tags as $tag){
            $statement = $this->pdo->prepare("INSERT INTO recette_tag(recette_tag.ID_Recette,recette_tag.ID_TAG) VALUES (:id1,:id2)");
            $statement->bindValue(":id1",$new_id);
            $statement->bindValue(":id2",$tag);
            $statement->execute();
        }

    }

    public function modifierRecette($new_name,$old_name,$desc,$img,$liste_ingredients,$liste_tags){
        $id_ingredients = [];
        $id_tags = [];
        foreach($liste_ingredients as $ingredient){
            $statement = $this->pdo->prepare("SELECT ingredient.ID_Ingredient FROM ingredient WHERE ingredient.Nom_Ingredient=:nom");
            $statement->bindValue(":nom",$ingredient);
            $statement->execute();

            $result = $statement->fetchAll();
            $id_ingredients[]=$result[0]['ID_Ingredient'];
        }

        foreach($liste_tags as $tag){
            $statement = $this->pdo->prepare("SELECT tag.ID_TAG FROM tag WHERE tag.Nom_TAG=:nom");
            $statement->bindValue(":nom",$tag);
            $statement->execute();

            $result = $statement->fetchAll();
            $id_tags[]=$result[0]['ID_TAG'];
        }

        $statement = $this->pdo->prepare("SELECT recette.ID_Recette FROM  recette WHERE recette.Nom_Recette=:nom");
        $statement->bindValue(":nom",$old_name);
        $statement->execute();
        $result = $statement->fetchAll();
        var_dump($old_name);
        $id = $result[0]["ID_Recette"];


        $statement = $this->pdo->prepare("UPDATE recette SET recette.Nom_Recette=:nom,recette.Description=:desc,recette.Image_Recette=:img WHERE recette.ID_Recette=:id");
        $statement->bindValue(":id",$id);
        $statement->bindValue(":nom",$new_name);
        $statement->bindValue(":desc",$desc);
        $statement->bindValue(":img",$img);
        $statement->execute();


        $statement = $this->pdo->prepare("DELETE FROM recette_ingredient WHERE recette_ingredient.ID_Recette=:id");
        $statement->bindValue(":id",$id);
        $statement->execute();

        $statement = $this->pdo->prepare("DELETE FROM recette_tag WHERE recette_tag.ID_Recette=:id");
        $statement->bindValue(":id",$id);
        $statement->execute();

        foreach($id_ingredients as $ingredient){
            $statement = $this->pdo->prepare("INSERT INTO recette_ingredient(recette_ingredient.ID_Recette,recette_ingredient.ID_Ingredient) VALUES (:id1,:id2)");
            $statement->bindValue(":id1",$id);
            $statement->bindValue(":id2",$ingredient);
            $statement->execute();
        }

        foreach($id_tags as $tag){
            $statement = $this->pdo->prepare("INSERT INTO recette_tag(recette_tag.ID_Recette,recette_tag.ID_TAG) VALUES (:id1,:id2)");
            $statement->bindValue(":id1",$id);
            $statement->bindValue(":id2",$tag);
            $statement->execute();
        }
    }

    public function supprimerRecette($nom)
    {   
        $statement = $this->pdo->prepare("SELECT recette.ID_Recette FROM recette WHERE recette.Nom_Recette=:nom");
        $statement->bindValue(":nom",$nom);
        $statement->execute();
        $result = $statement->fetchAll();
        $id = $result[0]["ID_Recette"];

        $statement = $this->pdo->prepare("DELETE FROM recette WHERE recette.Nom_Recette=:nom");
        $statement->bindValue(":nom",$nom);
        $statement->execute();

        $statement = $this->pdo->prepare("DELETE FROM recette_ingredient WHERE recette_ingredient.ID_Recette=:id");
        $statement->bindValue(":id",$id);
        $statement->execute();

        $statement = $this->pdo->prepare("DELETE FROM recette_tag WHERE recette_tag.ID_Recette=:id");
        $statement->bindValue(":id",$id);
        $statement->execute();
    }

    
}