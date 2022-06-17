<?php
class AccesBd
{
    private $pdo = null; // Objet de Connexion (PDO)
    private $requetePdo = null; // Objet de requête paramétrée PDO (PDOStatement)

    function __construct()
    {
        if (!$this->pdo) {
            $options = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ];
            $this->pdo = new PDO("mysql:host=localhost; dbname=leila; charset=utf8", 'root', '', $options);
        }
    }
    /**
     * Soumet une requête paramétrée PDO
     * @param string $sql Chaîne contenant une requête SQL paramétrée
     * @param array $params Tableau associatif des paramètres de la requête
     * @return void
     */
    private function soumettre($sql, $params)
    {
        $this->requetePdo = $this->pdo->prepare($sql);
        $this->requetePdo->execute($params);
    }
    /**
     * Obtient un jeu d'enregistrement groupé(par première colonne sélectionnée, Ex: pla_categorie)
     * @param string $sql Chaîne contenant une requête SQL paramétrée
     * @param array $params Tableau associatif des paramètres de la requête
     * @return array Tableau associatif (colonne de groupage) contenant des tableau des données groupées
     */
    protected function lire($sql, $param = [])
    {
        $this->soumettre($sql, $param);
        return $this->requetePdo->fetchAll(PDO::FETCH_GROUP);
    }
    
    /**
     * lireUn
     *
     * @param  mixed $sql
     * @param  mixed $param
     * @return void
     */
    protected function lireUn($sql, $param = [])
    {
        $this->soumettre($sql, $param);
        return $this->requetePdo->fetch();
    }

    /**
     * Ajoute  un enregistrement
     * @param string $sql Chaîne contenant une requête SQL paramétrée
     * @param array $params Tableau associatif des paramètres de la requête
     * @return int Identifiant (auto increment) du dernier enregistrement inséré
     */

    protected function creer($sql, $param = [])
    {
        $this->soumettre($sql, $param);
        return $this->pdo->lastInsertId();
    }


    /**
     * Modifie un enregistrement
     * @param string $sql Chaîne contenant une requête SQL paramétrée
     * @param array $params Tableau associatif des paramètres de la requête
     * @return int Nombre d'enregistrement affecté
     */
    protected function modifier($sql, $param = [])
    {
        $this->soumettre($sql, $param);
        return $this->requetePdo->rowCount();
    }

    /**
     * Supprime un enregistrement
     * @param string $sql Chaîne contenant une requête SQL paramétrée
     * @param array $params Tableau associatif des paramètres de la requête
     * @return int Nombre d'enregistrement affecté
     */
    protected function supprimer($sql, $param = [])
    {
        return $this->modifier($sql, $param = []);
    }

}
