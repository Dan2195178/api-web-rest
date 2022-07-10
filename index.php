<?php
// Front Controller（Contrôlleur pilote ou routeur ）: point d'entrer
// indiquer dans l'entête de HTTP de réponse
header("Content-Type: application/json; charset=UTF-8");
//  pour servir à un appel extérieur disons que des autres sites extérieur ayant un URL différent qui pourraient accéder à des APIs de ce site
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE");

// Format de la requête HTTP :http://localhost/technique-avancee/api-web-rest/plats/2 ou http://127.0.0.1/technique-avancee/api-web-rest/index.php/plats
$urlRequete = $_SERVER['REQUEST_URI'];
$routeur = new Routeur(
    parse_url($urlRequete, PHP_URL_PATH),  // il fait la route, $this->route, il sortir le chemin avant le point intérogation ? du URL dans navigateur, le URL comme  plats?id=1&pla_detail=""
    parse_url($urlRequete, PHP_URL_QUERY), // querystring:$this->params, (après ?) Ex: id=2&categorie=3
    $_SERVER['REQUEST_METHOD'] // $this->methode, Ex: GET, POST, PUT, PATCH, DELETE

    //--- routeur(controleur,querystring,action)  , le controleur est le chemin(path) après l'extrait)
);

$routeur->invoquerRoute();

class Routeur
{
    private $route = '';
    private $params = '';
    private $methode = ''; // la methode qui décide quel action qu'on voudrais l'executer. on retrouve les actions dans la class controleur. Ex: GET - tout() ou un(), POST - ajouter(), PUT - remplacer(), PATCH - changer(), DELETE - retirer($id)
    function __construct($r, $p, $m)
    {
        $this->route = $r;
        $this->params = $p;
        $this->methode = $m;

        // Autochargement des fichiers de classe lorsqu'une fois instancier une classe comme 'new PlatsControleur'
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
        // Pour le tester , il faut dans le navigateur(browser) entrer comme '{nom du dossier : /technique-avance/api-web-rest}/index.php/plats'  ou '/index.php/plats/17?para1=value1&para2=value2'
        $colletion = "plats";
        $idEntite = ""; // GET, PUT, PATCH, DELETE peuvent avoir un id sauf POST
        $partiesRoute = explode('/', $this->route);  // Décomposer la route en tableau -- Ex: "/technique-avancee/api-web-rest/plats/17"  => [ '', 'technique-avance', 'api-web-rest', 'plats', '17']

        /* -- débogage --
         print_r($partiesRoute);
         echo '<hr>';
         echo 'Paramètres (querystring) : ' . $this->params;
         echo '<hr>';
         echo 'Méthod HTTP : ' . $this->methode; 
         */

        // Enlever l'espace de idEntite, Ex: /plats/   10    =>  /plats/10
        array_walk($partiesRoute, function (&$elt) {
            $elt = trim(urldecode($elt));
        });
        // URL - http://localhost/technique-avancee/api-web-rest/plats/2  - $partiesRoute => '/technique-avancee/api-web-rest/plats/2'
        if (count($partiesRoute) > 3 && $partiesRoute[3] != '') {
            $colletion = $partiesRoute[3]; // 'plats'
            if (count($partiesRoute) > 4 && $partiesRoute[4] != '') {
                $idEntite = $partiesRoute[4]; // '2'
            }
        }

        $nomControleur = ucfirst($colletion) . 'Controleur';
        $nomModele = ucfirst($colletion) . 'Modele';

        if (class_exists($nomControleur)) {
            $controleur = new $nomControleur($nomModele); // instancier la classe 'controleur' et le modèle approprié à la fois 
            switch ($this->methode) {
                case 'GET':
                    if (is_numeric($idEntite)) {
                        $controleur->un($idEntite);
                    } else {
                        $controleur->tout($this->params);
                    }
                    break;
                case 'POST':  //en utilisant extension 'thunder client'  ou 'POSTMAN'
                    $controleur->ajouter(file_get_contents('php://input')); // en utilisant 'file_get_contents()' pour capturer le corp du message HTTP (request body)en JSON
                    break;
                case 'PUT':
                    if (is_numeric($idEntite)) {
                        $controleur->remplacer($idEntite, file_get_contents('php://input'));
                    } else {
                        //Erreur à completer
                        throw new Exception("type incorrect!");
                        // echo json_encode(['erreur'=>'type incorrect!']);
                    }
                    break;
                case 'PATCH':
                    if (is_numeric($idEntite)) {
                        $controleur->changer($idEntite, file_get_contents('php://input'));
                    } else {

                        throw new Exception("type incorrect!");
                    }
                    break;
                case 'DELETE':
                    if (is_numeric($idEntite)) {
                        $controleur->retirer($idEntite);
                    } else {
                        throw new Exception("type incorrect!");
                    }
                    break;
            }
        } else {
            exit("Mauvaise requête (à compléter)");
        }
    }
}
