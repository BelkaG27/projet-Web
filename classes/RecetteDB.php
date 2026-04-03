<?php

class RecetteDB{
    
    private PDO $pdo;
    private $liste_recettes=[];
    private $tab_ingredients=[];
    private $tab_tags=[];

    public function __construct() // contstructeur pour initialiser la base de donnee
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

    public function getIdRecettesPageAcceuil():array // retourne toutes les recettes de la base de donnee
    {
        $statement = $this->pdo->prepare("SELECT * FROM recette"); // requete pour selectionner toutes les recettes
        $statement->execute();

        // la requete nous renvoie un tableau des resultats de la requete
        $result = $statement->fetchAll();
        foreach($result as $key=>$recette){ // pour chaque resultat(ligne du tableau recette)
            // on remplit le tableau liste_recettes avec les ID de chaque ligne du tab recette
            $liste_recettes[$key]=$recette['ID_Recette'];
        }
        return $liste_recettes;// on renvoie le tab recette
    }

    public function getRecettes():array // retourne toutes les recettes de la base de donnee (id et nom)
{
    $statement = $this->pdo->prepare("SELECT recette.ID_Recette,recette.Nom_Recette FROM recette"); // requete pour selectionner l'id et le nom de toutes les recettes
    $statement->execute();

    // la requete nous renvoie un tableau des resultats de la requete
    $result = $statement->fetchAll();
    foreach($result as $key=>$recette){ // pour chaque resultat(ligne du tableau recette)
        // on remplit le tableau liste_recettes avec l'id et le titre de chaque recette
        $liste_recettes[$key]=['id'=>$recette['ID_Recette'],'title'=>$recette['Nom_Recette']];
    }
    return $liste_recettes; // on renvoie le tableau de recettes
}

public function getIngredients():array // retourne tous les ingredients de la base de donnee (id et nom)
{
    $statement = $this->pdo->prepare("SELECT ingredient.Nom_Ingredient,ingredient.ID_Ingredient FROM ingredient"); // requete pour selectionner le nom et l'id de tous les ingredients
    $statement->execute();

    // la requete nous renvoie un tableau des resultats de la requete
    $result = $statement->fetchAll();
    foreach($result as $key=>$ingredient){ // pour chaque resultat(ligne du tableau ingredient)
        // on remplit le tableau liste_ingredient avec l'id et le nom de chaque ingredient
        $liste_ingredient[$key]=["id"=>$ingredient['ID_Ingredient'],"name"=>$ingredient['Nom_Ingredient']];
    }
    return $liste_ingredient; // on renvoie le tableau d'ingredients
}

public function getTagsPageAcceuil():array // retourne tous les noms de tags de la base de donnee (pour la page d'accueil)
{
    $statement = $this->pdo->prepare("SELECT tag.Nom_TAG FROM tag"); // requete pour selectionner le nom de tous les tags
    $statement->execute();

    // la requete nous renvoie un tableau des resultats de la requete
    $result = $statement->fetchAll();
    foreach($result as $key=>$recette){ // pour chaque resultat(ligne du tableau tag)
        // on remplit le tableau liste_tags avec le nom de chaque tag
        $liste_tags[$key]=$recette['Nom_TAG'];
    }
    return $liste_tags; // on renvoie le tableau de tags
}

public function getTags():array // retourne tous les tags de la base de donnee (id et nom)
{
    $statement = $this->pdo->prepare("SELECT tag.ID_TAG,tag.Nom_TAG FROM tag"); // requete pour selectionner l'id et le nom de tous les tags
    $statement->execute();

    // la requete nous renvoie un tableau des resultats de la requete
    $result = $statement->fetchAll();
    foreach($result as $key=>$tag){ // pour chaque resultat(ligne du tableau tag)
        // on remplit le tableau liste_tags avec l'id et le nom de chaque tag
        $liste_tags[$key]=["id"=>$tag['ID_TAG'],"name"=>$tag['Nom_TAG']];
    }
    return $liste_tags; // on renvoie le tableau de tags
}

public function getNomRecette($id):string // retourne le nom d'une recette en fonction de son id
{
    $statement = $this->pdo->prepare("SELECT recette.Nom_Recette FROM recette where ID_Recette=:id"); // requete pour selectionner le nom de la recette correspondant a l'id
    $statement->bindValue(":id",$id); // on lie la valeur de l'id a la requete
    $statement->execute();

    // la requete nous renvoie un tableau des resultats de la requete
    $result = $statement->fetchAll();
    return $result[0]["Nom_Recette"]; // on renvoie le nom de la recette (premier et unique resultat)
}

public function getImageRecette($id):string // retourne le chemin de l'image d'une recette en fonction de son id
{
    $statement = $this->pdo->prepare("SELECT recette.Image_Recette FROM recette where ID_Recette=:id"); // requete pour selectionner l'image de la recette correspondant a l'id
    $statement->bindValue(":id",$id); // on lie la valeur de l'id a la requete
    $statement->execute();

    // la requete nous renvoie un tableau des resultats de la requete
    $result = $statement->fetchAll();
    return $result[0]["Image_Recette"]; // on renvoie le chemin de l'image (premier et unique resultat)
}

public function getDescriptionRecette($id):string // retourne la description d'une recette en fonction de son id
{
    $statement = $this->pdo->prepare("SELECT recette.Description FROM recette where ID_Recette=:id"); // requete pour selectionner la description de la recette correspondant a l'id
    $statement->bindValue(":id",$id); // on lie la valeur de l'id a la requete
    $statement->execute();

    // la requete nous renvoie un tableau des resultats de la requete
    $result = $statement->fetchAll();
    return $result[0]["Description"]; // on renvoie la description (premier et unique resultat)
}

public function getTagsRecette($id):array // retourne tous les tags associes a une recette en fonction de son id
{
    $statement = $this->pdo->prepare("SELECT tag.Nom_TAG FROM tag JOIN recette_tag ON recette_tag.ID_TAG = tag.ID_TAG where recette_tag.ID_Recette=:id"); // requete pour selectionner les tags de la recette via la table de liaison recette_tag
    $statement->bindValue(":id",$id); // on lie la valeur de l'id a la requete
    $statement->execute();

    // la requete nous renvoie un tableau des resultats de la requete
    $result = $statement->fetchAll();
    foreach($result as $key=>$tag){ // pour chaque resultat(ligne du tableau tag)
        // on remplit le tableau liste_tags avec le nom de chaque tag associe a la recette
        $liste_tags[$key]=$tag['Nom_TAG'];
    }
    return $liste_tags; // on renvoie le tableau de tags de la recette
}

public function getIngredientsRecette($id):array // retourne les noms et images des ingredients d'une recette en fonction de son id
{
    $statement = $this->pdo->prepare("SELECT ingredient.Nom_Ingredient,ingredient.Image_Ingredient FROM ingredient JOIN recette_ingredient ON recette_ingredient.ID_Ingredient = ingredient.ID_Ingredient where recette_ingredient.ID_Recette=:id"); // requete pour selectionner les ingredients de la recette via la table de liaison recette_ingredient
    $statement->bindValue(":id",$id); // on lie la valeur de l'id a la requete
    $statement->execute();

    // la requete nous renvoie un tableau des resultats de la requete
    $result = $statement->fetchAll();
    foreach($result as $key=>$ingredient){ // pour chaque resultat(ligne du tableau ingredient)
        // on remplit deux tableaux separes : un pour les noms et un pour les images
        $liste_nom_ingredient[$key]=$ingredient['Nom_Ingredient'];
        $liste_image_ingredient[$key]=$ingredient['Image_Ingredient'];
    }
    return $liste_ingredient=["nom"=>$liste_nom_ingredient,"image"=>$liste_image_ingredient]; // on renvoie un tableau associatif contenant les noms et les images
}

public function getIngredientsRecette_pageRecipe($id):array // retourne les ingredients d'une recette sous forme de tableau associatif (nom + image) pour la page recette
{
    $statement = $this->pdo->prepare("SELECT ingredient.Nom_Ingredient,ingredient.Image_Ingredient FROM ingredient JOIN recette_ingredient ON recette_ingredient.ID_Ingredient = ingredient.ID_Ingredient where recette_ingredient.ID_Recette=:id"); // requete pour selectionner les ingredients de la recette via la table de liaison recette_ingredient
    $statement->bindValue(":id",$id); // on lie la valeur de l'id a la requete
    $statement->execute();

    // la requete nous renvoie un tableau des resultats de la requete
    $result = $statement->fetchAll();
    $liste_ingredient = []; // on initialise le tableau vide
    foreach($result as $key=>$ingredient){ // pour chaque resultat(ligne du tableau ingredient)
        // on remplit le tableau avec le nom et l'image de chaque ingredient
        $liste_ingredient[$key]=["name"=>$ingredient['Nom_Ingredient'],"image"=>$ingredient['Image_Ingredient']];
    }
    return $liste_ingredient; // on renvoie le tableau d'ingredients
}

public function ajouterIngredient($nom,$img) // ajoute un nouvel ingredient dans la base de donnee
{   
    $statement = $this->pdo->prepare("SELECT ingredient.ID_Ingredient FROM  ingredient ORDER BY ID_Ingredient DESC LIMIT 1"); // requete pour recuperer le dernier id d'ingredient insere
    $statement->execute();
    $result = $statement->fetchAll();
    $new_id = $result[0]["ID_Ingredient"]+1; // on incremente l'id pour obtenir le nouvel id unique

    $statement = $this->pdo->prepare("INSERT INTO ingredient(ingredient.ID_Ingredient,ingredient.Nom_Ingredient,ingredient.Image_Ingredient) VALUES (:id,:nom,:img)"); // requete pour inserer le nouvel ingredient
    $statement->bindValue(":id",$new_id); // on lie le nouvel id
    $statement->bindValue(":nom",$nom); // on lie le nom de l'ingredient
    $statement->bindValue(":img",$img); // on lie le chemin de l'image
    $statement->execute();
}

public function modifierIngredient($nom,$old_nom,$img) // modifie un ingredient existant dans la base de donnee
{   
    $statement = $this->pdo->prepare("UPDATE ingredient SET ingredient.Nom_Ingredient=:new,ingredient.Image_ingredient=:img WHERE ingredient.Nom_Ingredient=:old"); // requete pour mettre a jour le nom et l'image de l'ingredient
    $statement->bindValue(":new",$nom); // on lie le nouveau nom
    $statement->bindValue(":old",$old_nom); // on lie l'ancien nom pour identifier l'ingredient a modifier
    $statement->bindValue(":img",$img); // on lie le nouveau chemin de l'image
    $statement->execute();
}

public function ajouterTag($nom) // ajoute un nouveau tag dans la base de donnee
{   
    $statement = $this->pdo->prepare("SELECT tag.ID_TAG FROM tag ORDER BY ID_TAG DESC LIMIT 1"); // requete pour recuperer le dernier id de tag insere
    $statement->execute();
    $result = $statement->fetchAll();
    $new_id = $result[0]["ID_TAG"]+1; // on incremente l'id pour obtenir le nouvel id unique

    $statement = $this->pdo->prepare("INSERT INTO tag(tag.ID_TAG,tag.Nom_TAG) VALUES (:id,:nom)"); // requete pour inserer le nouveau tag
    $statement->bindValue(":id",$new_id); // on lie le nouvel id
    $statement->bindValue(":nom",$nom); // on lie le nom du tag
    $statement->execute();
}

public function modifierTag($nom,$old_nom) // modifie un tag existant dans la base de donnee
{   
    $statement = $this->pdo->prepare("UPDATE tag SET tag.Nom_TAG=:new WHERE tag.Nom_TAG=:old"); // requete pour mettre a jour le nom du tag
    $statement->bindValue(":new",$nom); // on lie le nouveau nom du tag
    $statement->bindValue(":old",$old_nom); // on lie l'ancien nom pour identifier le tag a modifier
    $statement->execute();
}

public function supprimerTag($nom) // supprime un tag de la base de donnee en fonction de son nom
{   
    $statement = $this->pdo->prepare("DELETE FROM tag WHERE tag.Nom_TAG=:nom"); // requete pour supprimer le tag correspondant au nom
    $statement->bindValue(":nom",$nom); // on lie le nom du tag a supprimer
    $statement->execute();
}

public function creerRecette($nom,$desc,$img,$liste_ingredients,$liste_tags) // cree une nouvelle recette dans la base de donnee avec ses ingredients et ses tags
{
    $id_ingredients = []; // tableau pour stocker les ids des ingredients
    $id_tags = []; // tableau pour stocker les ids des tags
    foreach($liste_ingredients as $ingredient){ // pour chaque ingredient passe en parametre
        $statement = $this->pdo->prepare("SELECT ingredient.ID_Ingredient FROM ingredient WHERE ingredient.Nom_Ingredient=:nom"); // requete pour recuperer l'id de l'ingredient via son nom
        $statement->bindValue(":nom",$ingredient);
        $statement->execute();

        $result = $statement->fetchAll();
        $id_ingredients[]=$result[0]['ID_Ingredient']; // on ajoute l'id recupere dans le tableau
    }

    foreach($liste_tags as $tag){ // pour chaque tag passe en parametre
        $statement = $this->pdo->prepare("SELECT tag.ID_TAG FROM tag WHERE tag.Nom_TAG=:nom"); // requete pour recuperer l'id du tag via son nom
        $statement->bindValue(":nom",$tag);
        $statement->execute();

        $result = $statement->fetchAll();
        $id_tags[]=$result[0]['ID_TAG']; // on ajoute l'id recupere dans le tableau
    }

    $statement = $this->pdo->prepare("SELECT recette.ID_Recette FROM  recette ORDER BY ID_Recette DESC LIMIT 1"); // requete pour recuperer le dernier id de recette insere
    $statement->execute();
    $result = $statement->fetchAll();
    $new_id = $result[0]["ID_Recette"]+1; // on incremente l'id pour obtenir le nouvel id unique


    $statement = $this->pdo->prepare("INSERT INTO recette(recette.ID_Recette,recette.Nom_Recette,recette.Description,recette.Image_Recette) VALUES (:id,:nom,:desc,:img)"); // requete pour inserer la nouvelle recette
    $statement->bindValue(":id",$new_id);
    $statement->bindValue(":nom",$nom);
    $statement->bindValue(":desc",$desc);
    $statement->bindValue(":img",$img);
    $statement->execute();

    foreach($id_ingredients as $ingredient){ // pour chaque id d'ingredient recupere
        $statement = $this->pdo->prepare("INSERT INTO recette_ingredient(recette_ingredient.ID_Recette,recette_ingredient.ID_Ingredient) VALUES (:id1,:id2)"); // requete pour lier l'ingredient a la recette dans la table de liaison
        $statement->bindValue(":id1",$new_id);
        $statement->bindValue(":id2",$ingredient);
        $statement->execute();
    }

    foreach($id_tags as $tag){ // pour chaque id de tag recupere
        $statement = $this->pdo->prepare("INSERT INTO recette_tag(recette_tag.ID_Recette,recette_tag.ID_TAG) VALUES (:id1,:id2)"); // requete pour lier le tag a la recette dans la table de liaison
        $statement->bindValue(":id1",$new_id);
        $statement->bindValue(":id2",$tag);
        $statement->execute();
    }
}

public function modifierRecette($new_name,$old_name,$desc,$img,$liste_ingredients,$liste_tags) // modifie une recette existante et met a jour ses ingredients et ses tags
{
    $id_ingredients = []; // tableau pour stocker les ids des nouveaux ingredients
    $id_tags = []; // tableau pour stocker les ids des nouveaux tags
    foreach($liste_ingredients as $ingredient){ // pour chaque ingredient passe en parametre
        $statement = $this->pdo->prepare("SELECT ingredient.ID_Ingredient FROM ingredient WHERE ingredient.Nom_Ingredient=:nom"); // requete pour recuperer l'id de l'ingredient via son nom
        $statement->bindValue(":nom",$ingredient);
        $statement->execute();

        $result = $statement->fetchAll();
        $id_ingredients[]=$result[0]['ID_Ingredient']; // on ajoute l'id recupere dans le tableau
    }

    foreach($liste_tags as $tag){ // pour chaque tag passe en parametre
        $statement = $this->pdo->prepare("SELECT tag.ID_TAG FROM tag WHERE tag.Nom_TAG=:nom"); // requete pour recuperer l'id du tag via son nom
        $statement->bindValue(":nom",$tag);
        $statement->execute();

        $result = $statement->fetchAll();
        $id_tags[]=$result[0]['ID_TAG']; // on ajoute l'id recupere dans le tableau
    }

    $statement = $this->pdo->prepare("SELECT recette.ID_Recette FROM  recette WHERE recette.Nom_Recette=:nom"); // requete pour recuperer l'id de la recette a modifier via son ancien nom
    $statement->bindValue(":nom",$old_name);
    $statement->execute();
    $result = $statement->fetchAll();
    var_dump($old_name);
    $id = $result[0]["ID_Recette"]; // on stocke l'id de la recette a modifier


    $statement = $this->pdo->prepare("UPDATE recette SET recette.Nom_Recette=:nom,recette.Description=:desc,recette.Image_Recette=:img WHERE recette.ID_Recette=:id"); // requete pour mettre a jour les informations de la recette
    $statement->bindValue(":id",$id);
    $statement->bindValue(":nom",$new_name);
    $statement->bindValue(":desc",$desc);
    $statement->bindValue(":img",$img);
    $statement->execute();


    $statement = $this->pdo->prepare("DELETE FROM recette_ingredient WHERE recette_ingredient.ID_Recette=:id"); // on supprime tous les anciens liens ingredients de la recette pour les remplacer
    $statement->bindValue(":id",$id);
    $statement->execute();

    $statement = $this->pdo->prepare("DELETE FROM recette_tag WHERE recette_tag.ID_Recette=:id"); // on supprime tous les anciens liens tags de la recette pour les remplacer
    $statement->bindValue(":id",$id);
    $statement->execute();

    foreach($id_ingredients as $ingredient){ // pour chaque id d'ingredient recupere
        $statement = $this->pdo->prepare("INSERT INTO recette_ingredient(recette_ingredient.ID_Recette,recette_ingredient.ID_Ingredient) VALUES (:id1,:id2)"); // requete pour inserer les nouveaux liens ingredients dans la table de liaison
        $statement->bindValue(":id1",$id);
        $statement->bindValue(":id2",$ingredient);
        $statement->execute();
    }

    foreach($id_tags as $tag){ // pour chaque id de tag recupere
        $statement = $this->pdo->prepare("INSERT INTO recette_tag(recette_tag.ID_Recette,recette_tag.ID_TAG) VALUES (:id1,:id2)"); // requete pour inserer les nouveaux liens tags dans la table de liaison
        $statement->bindValue(":id1",$id);
        $statement->bindValue(":id2",$tag);
        $statement->execute();
    }
}

public function supprimerRecette($nom) // supprime une recette de la base de donnee ainsi que ses liens dans les tables de liaison
{   
    $statement = $this->pdo->prepare("SELECT recette.ID_Recette FROM recette WHERE recette.Nom_Recette=:nom"); // requete pour recuperer l'id de la recette a supprimer via son nom
    $statement->bindValue(":nom",$nom);
    $statement->execute();
    $result = $statement->fetchAll();
    $id = $result[0]["ID_Recette"]; // on stocke l'id de la recette a supprimer

    $statement = $this->pdo->prepare("DELETE FROM recette WHERE recette.Nom_Recette=:nom"); // requete pour supprimer la recette de la table recette
    $statement->bindValue(":nom",$nom);
    $statement->execute();

    $statement = $this->pdo->prepare("DELETE FROM recette_ingredient WHERE recette_ingredient.ID_Recette=:id"); // requete pour supprimer les liens ingredients associes a la recette
    $statement->bindValue(":id",$id);
    $statement->execute();

    $statement = $this->pdo->prepare("DELETE FROM recette_tag WHERE recette_tag.ID_Recette=:id"); // requete pour supprimer les liens tags associes a la recette
    $statement->bindValue(":id",$id);
    $statement->execute();
}
}