<?php
class CategoriesModele extends AccesBd {

    /**
     *  Implémenter toutes les opérations pour la table 'categorie'
     */
    public function tout($groupe)
    {
        return $this->lire("SELECT * FROM categorie", $groupe);
        
    }
    public function un($id)
    {
        return $this->lireUn("SELECT * FROM categorie 
        WHERE cat_id=:cat_id", ['cat_id' => $id]);
    }
    public function ajouter($cat)
    {
        return $this->creer(
            "INSERT INTO categorie
        (cat_nom, cat_type) 
        VALUES (?,?)",
            [$cat->cat_nom, $cat->cat_type]
        );

        /*----payload du JSON dans le thunder client pour tester , la méthode choisi est POST, http://localhost/technique-avancee/api-web-rest/categories

       {
           "cat_nom": "Boite du riz",
           "cat_type": "plat"
       }*/
    }
    public function remplacer($id, $entite)
    {
        return $this->modifier("UPDATE categorie SET cat_nom=?, cat_type=?
        WHERE cat_id=?", [
            $entite->cat_nom,
            $entite->cat_type,
            $id
        ]);
          /*----payload du JSON dans le thunder client pour tester , la méthode choisi est POST, http://localhost/technique-avancee/api-web-rest/categories/5

       {
           "cat_nom": "Boite du riz",
           "cat_type": "plat"
       }*/

    }
    public function changer($id, $fragmentEntite)
    {
        $fragmentSql = "";
        $fragmentParams = [];
        foreach($fragmentEntite as $key => $value) {
            $fragmentSql .= "$key=?,";
            $fragmentParams[] = $value;
        }
        $fragmentSql = rtrim($fragmentSql, ',');
        $fragmentParams[] = $id;

        return $this->modifierPartie("UPDATE categorie SET {$fragmentSql} WHERE cat_id=?", $fragmentParams);
        /*----payload du JSON dans le thunder client pour tester , la méthode choisi est POST, http://localhost/technique-avancee/api-web-rest/categories/5

        {
            "cat_type": "vin"
        }*/
    }

    public function retirer($id)
    {
        return $this->supprimer("DELETE FROM categorie WHERE cat_id=:cat_id", ['cat_id' => $id]);
    }
}