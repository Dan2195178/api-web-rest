<?php
class PlatsControleur extends Controleur {
    /**
     *  Implémenter chacune des opérations des données plats...
     *  
     *  - Après chaque opération , il va appleler automatiquement la function __destruct() de la class parent Controleur 
     *  - pour gérer (envoyer) la réponse (équivalent echo '') à browseur - ce sont header() et echo "" ensemble dans le framework Controleur.cls.php
     * 
     *  Référence:https://www.restapitutorial.com/lessons/httpmethods.html
     */

    // Méthode 'GET' - 	Entire Collection - Read
    public function tout($params)
    {
        $this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
        // $groupe = PDO::FETCH_GROUP;
        $groupe = NULL;  
        $this->reponse['corps'] = $this->modele->tout($groupe);
    }

    // Méthode 'GET' - Specific Item - Read
    public function un($id)
    {
        $this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
        $this->reponse['corps'] = $this->modele->un($id);
    }

    // Méthode 'POST' - Specific Item - Create
    public function ajouter($entite) //$entite => $plat
    {
        $this->reponse['entete_statut'] = 'HTTP/1.1 201 Created';
        $this->reponse['corps'] = ['pla_id' => $this->modele->ajouter(json_decode($entite))];
    }

    // Méthode 'PUT' - Specific Item - Update/Replace - tous le champs
    public function remplacer($id, $entite)
    {
        $this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
        $this->reponse['corps'] = ['row_count_affecte' => $this->modele->remplacer($id,json_decode($entite))];
    }

    // Méthode 'PATCH'- Specific Item - Update/Modify - des champs spécifiés
    public function changer($id, $fragmentEntite)
    {
        $this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
        $this->reponse['corps'] = ['row_count_affecte' => $this->modele->changer($id,json_decode($fragmentEntite,true))];
  
    }

    // Méthode 'DELETE'- Specific Item - Delete 
    public function retirer($id)
    {
        $this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
        $this->reponse['corps'] = ['row_count_affecte' => $this->modele->retirer($id)];
    }
}
