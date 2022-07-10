<?php
class PlatsModele extends AccesBd
{
    /**
     *  Implémenter toutes les opérations pour la table 'plat'
     */
    public function tout($groupe)
    {

        return $this->lire("SELECT cat_nom, plat.* FROM plat JOIN categorie 
        ON pla_cat_id_ce = cat_id", $groupe);
    }
    public function un($id)
    {
        return $this->lireUn("SELECT cat_nom, plat.* FROM plat JOIN categorie 
        ON pla_cat_id_ce = cat_id  WHERE pla_id=:pla_id", ['pla_id' => $id]);
    }
    public function ajouter($plat)
    {
        return $this->creer(
            "INSERT INTO plat
        (pla_nom, pla_detail, pla_portion, pla_prix, pla_cat_id_ce) 
        VALUES (?,?,?,?,?)",
            [$plat->pla_nom, $plat->pla_detail, $plat->pla_portion, $plat->pla_prix, $plat->pla_cat_id_ce]
        );

        /*----payload du JSON dans le thunder client pour tester , la méthode choisi est POST, http://localhost/technique-avancee/api-web-rest/plats

       {
           "pla_nom": "Pâté chinois",
           "pla_detail": "Hachis parmenttier de canard effiloché",
           "pla_portion": 1,
           "pla_cat_id_ce": 2,
           "pla_prix": 27
       }*/
    }
    public function remplacer($id, $entite)
    {
        return $this->modifier("UPDATE plat SET pla_nom=?, pla_detail=?, pla_portion=?, pla_prix=?, pla_cat_id_ce=? 
        WHERE pla_id=?", [
            $entite->pla_nom,
            $entite->pla_detail,
            $entite->pla_portion,
            $entite->pla_prix,
            $entite->pla_cat_id_ce,
            $id
        ]);
        /*----payload du JSON dans le thunder client pour tester , la méthode choisi est POST, http://localhost/technique-avancee/api-web-rest/plats/2

       {
           "pla_nom": "Pâté chinois",
           "pla_detail": "Hachis parmenttier de canard effiloché",
           "pla_portion": 1,
           "pla_cat_id_ce": 2,
           "pla_prix": 27
       }*/
    }
    public function changer($id, $fragmentEntite)
    {
        $fragmentSql = "";
        $fragmentParams = [];
        foreach ($fragmentEntite as $key => $value) {
            $fragmentSql .= "$key=?,";
            $fragmentParams[] = $value;
        }
        $fragmentSql = rtrim($fragmentSql, ',');
        $fragmentParams[] = $id;

        return $this->modifierPartie("UPDATE plat SET {$fragmentSql} WHERE pla_id=?", $fragmentParams);
        /*----payload du JSON dans le thunder client pour tester , la méthode choisi est POST, http://localhost/technique-avancee/api-web-rest/plats/3
       
        {
        
           "pla_detail": "Hachis parmenttier de canard effiloché",
           "pla_prix": 100
       }*/
    }

    public function retirer($id)
    {
        return $this->supprimer("DELETE FROM plat WHERE pla_id=:pla_id", ['pla_id' => $id]);
    }
}
