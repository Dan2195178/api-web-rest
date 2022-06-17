<?php
class VinsControleur extends Controleur {
    public function tout($params)
    {
        $this->reponse['entete_statut'] = 'HTTP/1.1 200 OK';
        $this->reponse['corps'] = $this->modele->tout($params);
    }
    public function un($id){
        $this->reponse['entete_statut'] = 'HTTP/1.1 200 Created';
        $this->reponse['corps'] = $this->modele->un($id);

    }
    public function ajouter($plat){
        $this->reponse['entete_statut'] = 'HTTP/1.1 200 Created';
        $this->reponse['corps'] = [''=>$this->modele->ajouter(json_decode($plat))];
    }
    public function remplacer($id, $entite){

    }
    public function changer($id, $fragmentEntite){

    }
    public function retirer($id){

    }

}