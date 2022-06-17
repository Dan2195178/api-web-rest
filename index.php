<?php
// Front Controller（Contrôlleur pilot ou routeur ）: point d'entrer
// indiquer dans l'entête de HTTP de réponse
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE");

$urlRequete = $_SERVER['REQUEST_URI'];
$routeur = new Routeur(
    parse_url($urlRequete, PHP_URL_PATH),  // il fait la route, $this->route, il sortir le chemin avant le point intérogation ? du URL dans navigateur, le URL comme  plats?id=1&pla_detail=""
    parse_url($urlRequete, PHP_URL_QUERY), // querystring:$this->params, (après ?)
    $_SERVER['REQUEST_METHOD'] // $this->methode, Ex: GET, POST, PUT, PATCH, DELETE

    //routeur(controleur,querystring,action)  , le controleur est le chemin(path) après l'extrait)
);

$routeur->invoquerRoute();

class Routeur
{
    private $route = '';
    private $params = '';
    private $methode = ''; // la methode qui décide quel action qu'on voudrais l'executer. Ex: GET - tout() ou un(), POST - ajouter()
    function __construct($r, $p, $m)
    {
        $this->route = $r;
        $this->params = $p;
        $this->methode = $m;

        // Autochargement des fichiers de classe lorsque une fois instancier une classe comme 'new PlatsControleur'
        spl_autoload_register(function ($nomClasse) {
            $nomFichier = "$nomClasse.cls.php";
            if (file_exists("controleurs/$nomFichier")) {
                include("controleurs/$nomFichier");
            } else if (file_exists("modeles/$nomFichier")) {
                include("modeles/$nomFichier");
            }
        });
    }

    public function invoquerRoute()
    {
        // Exemples d'URLs
        // /index.php/plats ou /plats(ce forme doit être après le configurer), /plats/17, /vins, /vins/5
        // pour le tester , il faut dans le navigateur(browser) entrer comme '{nom du dossier : /technique-avance/api-web-rest}/index.php/plats'  ou '/index.php/plats/17?para1=value1&para2=value2'
        $colletion = "plats";
        $idEntite = ""; // GET, PUT, PATCH, DELETE peuvent avoir un id sauf POST

        $partiesRoute = explode('/', $this->route);
        /* print_r($partiesRoute);
         echo '<hr>';
         echo 'Paramètres (querystring) : ' . $this->params;
         echo '<hr>';
         echo 'Méthod HTTP : ' . $this->methode; 
         */
        array_walk($partiesRoute, function(&$elt){
            $elt = trim(urldecode($elt));
        }); // enlever l'espace de idEntite, Ex: /plats/   10    =>  /plats/10

        if (count($partiesRoute) > 4 && $partiesRoute[4] != '') {
            $colletion = $partiesRoute[4];
            if (count($partiesRoute) > 5 && $partiesRoute[5] != '') {
                $idEntite = $partiesRoute[5];
            }
        }

        $nomControleur = ucfirst($colletion) . 'Controleur';
        $nomModele = ucfirst($colletion) . 'Modele';

        if (class_exists($nomControleur)) {
            $controleur = new $nomControleur($nomModele);
            switch ($this->methode) {
                case 'GET':
                    if (is_numeric($idEntite)) {
                        $controleur->un($idEntite);
                    } else {
                        $controleur->tout($this->params);
                    }
                    break;
                case 'POST':
                    $controleur->ajouter(file_get_contents('php://input'));// utiliser l'extension 'thunder client' en utilisant 'file_get_contents()' récupérer le corp du message HTTP
                    break;
                case 'PUT':
                    if (is_numeric($idEntite)) {
                        $controleur->remplacer($idEntite, file_get_contents('php://input'));
                    } else {
                        //Erreur à completer
                    }
                    break;
                case 'PATCH':
                    if (is_numeric($idEntite)) {
                        $controleur->changer($idEntite, file_get_contents('php://input'));
                    } else {
                        //Erreur à completer
                    }
                    break;
                case 'DELETE':
                    if (is_numeric($idEntite)) {
                        $controleur->retirer($idEntite);
                    } else {
                        //Erreur à completer
                    }
                    break;

            }
        } else {
                 exit("Mauvaise requête (à compléter)");
        }
    }
}
