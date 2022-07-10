<?php
    abstract class Controleur {
    protected $modele;   // Référence au 'Modele' correspondant au 'controleur'
    protected $reponse;  // Tableau associatif contenant l'entête de statut (Status Header) et 
                         //le corp (BODY) du message HTTP de réponse
                         // ce tableau aura la forme suivante : 
                         // ['entete_statut'=> 'Valeur...', 'corps'=>'Valeur du corps du message']

    function __construct($nomModele)
    {
        if(class_exists($nomModele)) {
            $this->modele = new $nomModele();
        }
    }

    // Contrat pour les méthodes spécifiques de chaque contrôleur concret, tous ceux qui devrraient être implémenté dans la class enfant. ex: PlatsControleur.cls.php
    public abstract function tout($params);
    public abstract function un($id);
    public abstract function ajouter($entite);
    public abstract function remplacer($id, $entite);
    public abstract function changer($id, $fragmentEntite);
    public abstract function retirer($id);

    //Tester que l'on a eu une réponse qui se trouve dans le browseur: /inspecteur/Network, -onglet 'Headers' et 'Reponse'
    private function produireReponse()
    {
        header($this->reponse['entete_statut']); // header qui enregistre le Statut(HTTP Status Header), Ex: 200 ou 400 ou 201
        if($this->reponse['corps']) { //transformer en Json
            echo json_encode($this->reponse['corps']);
        } else {
            echo json_encode(['erreur'=>'Rien trouvé']);
        }
    }

    // renvoyer la reponse de la requête HTTP quand le controlleur instancié sera supprimé
    function __destruct()
    {
        $this->produireReponse();
    }
}