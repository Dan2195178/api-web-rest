<?php
class AccesBd
{
    private $pdo = null; // Objet de Connexion (PDO)
    private $requetePdo = null; // Objet de requête paramétrée PDO (PDOStatement)

    /**
     * __construct initialiser objet PDO pour créer un lien avec la base de donnée
     */
    function __construct()
    {
        if (!$this->pdo) {

            $options = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_STRINGIFY_FETCHES => false, PDO::ATTR_EMULATE_PREPARES => false];
            $this->pdo = new PDO("mysql:host=localhost; dbname=leila; charset=utf8", 'root', '', $options);
        }
    }

    /**
     * Soumet une requête paramétrée PDO
     * @param string $sql Chaîne contenant une requête SQL paramétrée - 'SELECT * FROM <tablename> WHERE champ1 = ? and champ2 = ?...'
     * @param array $params Tableau associatif des paramètres de la requête - remplacer le ? avec les ces parametre en tableau  [champ1: 2, champ2: 3...]
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
    protected function lire($sql, $groupe, $params = [])
    {
        $this->soumettre($sql, $params);
        return $this->requetePdo->fetchAll($groupe); // si on veut retourner une colletion groupé , on lui passe en paramètre $groupe = PDO::FETCH_GROUP  , sinon  $groupe est null
    }

    /**
     * lire un enregistrement spécifié par son identifiant unique qui est passé en paramètre dans le tableau $params
     *
     * @param string $sql Chaîne contenant une requête SQL paramétrée
     * @param array $params Tableau associatif des paramètres de la requête
     * @return array Tableau associatif ayant seulement un entregistrement cherché
     */
    protected function lireUn($sql, $params = [])
    {
        $this->soumettre($sql, $params);
        return $this->requetePdo->fetch();
    }

    /**
     * Ajoute  un enregistrement
     * @param string $sql Chaîne contenant une requête SQL paramétrée
     * @param array $params Tableau associatif des paramètres de la requête
     * @return int Identifiant (auto increment) du dernier enregistrement inséré
     */

    protected function creer($sql, $params = [])
    {
        $this->soumettre($sql, $params);
        return $this->pdo->lastInsertId();
    }

    /**
     * remplacer tous les champs d'un enregistrement
     *
     * @param string $sql Chaîne contenant une requête SQL paramétrée
     * @param array $params Tableau associatif des paramètres de la requête
     * @return int Nombre d'enregistrement affecté
     * @return void
     */
    protected function modifier($sql, $params = [])
    {
        $this->soumettre($sql, $params);
        return $this->requetePdo->rowCount();
    }

    /**
     * Modifie un enregistrement
     * @param string $sql Chaîne contenant une requête SQL paramétrée
     * @param array $params Tableau associatif des paramètres de la requête
     * @return int Nombre d'enregistrement affecté
     */
    protected function modifierPartie($sql, $params = [])
    {
        $this->soumettre($sql, $params);
        return $this->requetePdo->rowCount();
    }

    /**
     * Supprime un enregistrement
     * @param string $sql Chaîne contenant une requête SQL paramétrée
     * @param array $params Tableau associatif des paramètres de la requête
     * @return int Nombre d'enregistrement affecté
     */
    protected function supprimer($sql, $params = [])
    {
        $this->soumettre($sql, $params);
        return $this->requetePdo->rowCount();
    }
}
