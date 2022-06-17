<?php
class PlatsModele extends AccesBd 
{
    public function tout($params)
    {
        return $this->lire("SELECT cat_nom, plat.* FROM plat JOIN categorie 
        ON pla_cat_id_ce = cat_id");
        
    }
    public function un($id)
    {
        return $this->lireUn("SELECT cat_nom, plat.* FROM plat JOIN categorie 
        ON pla_cat_id_ce = cat_id  WHERE pla_id=:pla_id", ['pla_id'=>$id]);
       
    }
    public function ajouter($plat)
    {
        return $this->creer("INSERT INTO plat
        (pla_nom, pla_detail, pla_portion, pla_prix, pla_cat_id_ce) 
        VALUES (?,?,?,?,?)"
        , [$plat->pla_nom, $plat->pla_detail, $plat->pla_portion, $plat->pla_prix, $plat->pla_cat_id_ce]);
      
       /*----JSON dans le thunder client pour tester , la méthode choisi est POST, http://localhost/technique-avancee/api-web-rest/index.php/plats

       {
           "pla_nom": "Pâté chinois",
           "pla_detail": "Hachis parmenttier de canard effiloché",
           "pla_portion": 1,
           "pla_cat_id_ce": 2,
           "pla_prix": 27
       }*/
    }
} 