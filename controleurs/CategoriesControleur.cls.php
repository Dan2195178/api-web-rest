<?php
class CategoriesControleur extends Controleur {

     /**
     *  Implémenter chacune des opérations des données catgories...
     *  
     *  - Après chaque opération , il va appleler automatiquement la function __destruct() de la class parent Controleur 
     *  - pour gérer (envoyer) la réponse (équivalent echo '') à browseur - ce sont header() et echo "" ensemble dans le framework Controleur.cls.php
     * 
     *  Référence:https://www.restapitutorial.com/lessons/httpmethods.html
     */

    // Afficher tous les catégories
    public function tout($params)
    {
        $this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
        $groupe = NULL;
        $this->reponse['corps'] = $this->modele->tout($groupe);
    }
     // Afficher une catégorie spécifiée
    public function un($id)
    {
        $this->reponse['entete_statut'] = 'HTTP/1.1 200 Created';
        $this->reponse['corps'] = $this->modele->un($id);

    }
     // Ajouter une nouvelle catégorie 
    public function ajouter($entite)//$entite => $categorie
    {
        $this->reponse['entete_statut'] = 'HTTP/1.1 200 Created';
        $this->reponse['corps'] = ['cat_id'=>$this->modele->ajouter(json_decode($entite))];
    }
    // Modifier tous les champs d'une catégorie spécifiée
    public function remplacer($id, $entite)
    {
        $this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
        $this->reponse['corps'] = ['row_count_affecte' => $this->modele->remplacer($id,json_decode($entite))];
  
    }
    // Modifier les champs spécifiés d'une catégorie 
    public function changer($id, $fragmentEntite)
    {
        $this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
        $this->reponse['corps'] = ['row_count_affecte' => $this->modele->changer($id,json_decode($fragmentEntite,true))];
  
    }
    // Supprimer une catégorie 
    public function retirer($id)
    {
        $this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
        $this->reponse['corps'] = ['row_count_affecte' => $this->modele->retirer($id)];
  
    }

}