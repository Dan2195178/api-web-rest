<?php
class VinsModele extends AccesBd
{
    /**
     *  Implémenter toutes les opérations pour la table 'vin'
     */
    public function tout($groupe)
    {
        return $this->lire("SELECT cat_nom, vin.* FROM vin JOIN categorie 
        ON vin_cat_id_ce = cat_id", $groupe);
    }
    public function un($id)
    {
        return $this->lireUn("SELECT cat_nom, vin.* FROM vin JOIN categorie 
        ON vin_cat_id_ce = cat_id  WHERE vin_id=:vin_id", ['vin_id' => $id]);
    }
    public function ajouter($vin)
    {
        return $this->creer(
            "INSERT INTO vin
        (vin_nom, vin_detail, vin_provenance, vin_annee, vin_prix, vin_cat_id_ce) 
        VALUES (?,?,?,?,?,?)",
            [$vin->vin_nom, $vin->vin_detail, $vin->vin_provenance, $vin->vin_annee, $vin->vin_prix, $vin->vin_cat_id_ce]
        );

        /*----payload du JSON dans le thunder client pour tester , la méthode choisi est POST, http://localhost/technique-avancee/api-web-rest/vins

       {
           "vin_nom": "cava Brut 2000",
           "vin_detail": "Vincent Gonzalvez 2000",
           "vin_provenance": "France",
           "vin_annee": 2000,
           "vin_prix": 270,
           "vin_cat_id_ce": 6
           
         }   
       */
    }
    public function remplacer($id, $entite)
    {
        return $this->modifier("UPDATE vin SET vin_nom=?, vin_detail=?, vin_provenance=?, vin_annee=?, vin_prix=?, vin_cat_id_ce=? 
        WHERE vin_id=?", [
            $entite->vin_nom,
            $entite->vin_detail,
            $entite->vin_provenance,
            $entite->vin_annee,
            $entite->vin_prix,
            $entite->vin_cat_id_ce,
            $id
        ]);
        /*----payload du JSON dans le thunder client pour tester , la méthode choisi est POST, http://localhost/technique-avancee/api-web-rest/vins/2

       {
           "vin_nom": "cava Brut 2000",
           "vin_detail": "Vincent Gonzalvez 2000",
           "vin_provenance": "France",
           "vin_annee": 2000,
           "vin_prix": 270,
           "vin_cat_id_ce": 7
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

        return $this->modifierPartie("UPDATE vin SET {$fragmentSql} WHERE vin_id=?", $fragmentParams);
        /*----payload du JSON dans le thunder client pour tester , la méthode choisi est POST, http://localhost/technique-avancee/api-web-rest/vins/3
       
        {
            "vin_provenance": "Italie",
            "vin_prix": 90,
            "vin_cat_id_ce": 6
        }*/
    }

    public function retirer($id)
    {
        return $this->supprimer("DELETE FROM vin WHERE vin_id=:vin_id", ['vin_id' => $id]);
    }
}
