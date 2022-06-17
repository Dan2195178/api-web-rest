<?php
    abstract class Controleur {
    protected $modele; // Référence au 'Modele' correspondant au 'controleur'
    // Tableau associatif contenant l'entête de statut (Status Header) et le corp (BODY) du message HTTP de réponse
    //ce tableau aura la forme suivante : 
    //['entete_statut'=> 'Valeur...', 'corps'=>'Valeur du corps du message']
    protected $reponse; 
    function __construct($nomModele)
    {
        if(class_exists($nomModele)) {
            $this->modele = new $nomModele();
        }
    }

    // Contrat pour les méthodes spécifiques de chaque contrôleur concret, tous ceux qui devrraient être implémenté dans la class enfant. ex: PlatsControleur.cls.php
    protected abstract function tout($params);
    protected abstract function un($id);
    protected abstract function ajouter($entite);
    protected abstract function remplacer($id, $entite);
    protected abstract function changer($id, $fragmentEntite);
    protected abstract function retirer($id);

    //
    private function produireReponse()
    {
        header($this->reponse['entete_statut']);
        if($this->reponse['corps']) {
            echo json_encode($this->reponse['corps']);
        } else {
            echo json_encode(['erreur'=>'Rien trouvé']);
        }
    }

    function __destruct()
    {
        $this->produireReponse();
    }
}