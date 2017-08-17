<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//route pour l'api
$route["api/v1/product"]["get"]           = "api/v1/product";
$route["api/v1/product/(:any)"]["get"]    = "api/v1/product/view/$1";
$route["api/v1/product"]["post"]          = "api/v1/product/create";
$route["api/v1/product/(:num)"]["put"]    = "api/v1/product/update/$1";
$route["api/v1/product/(:num)"]["delete"] = "api/v1/product/delete/$1";


//listes de produits du backoffice / accueil back
$route['backoffice/biere'] = 'backoffice/Beer_back/beer_list';
$route['backoffice/jeux'] = 'backoffice/Game_back/game_list';
$route['backoffice/actualite'] = 'backoffice/Actu_back/actu_list';

//recherche
$route['backoffice/biere/recherche'] = 'backoffice/Beer_back/search';
$route['backoffice/jeux/recherche'] = 'backoffice/Game_back/search';

//resultat recherche
$route['backoffice/biere/recherche/resultat'] = 'backoffice/Beer_back/search_result';
$route['backoffice/jeux/recherche/resultat'] = 'backoffice/Game_back/search_result';

//ajout
$route['backoffice/biere/ajout/(:any)'] = 'backoffice/Beer_back/form_add_beer/$1';
$route['backoffice/biere/ajoutNouvelle'] = 'backoffice/Beer_back/form_add_beer_empty';

$route['backoffice/jeux/ajout/(:any)'] = 'backoffice/Game_back/form_add_game/$1';
$route['backoffice/jeux/ajoutNouveaux'] = 'backoffice/Game_back/form_add_game_empty';

$route['backoffice/actualite/ajout'] = 'backoffice/Actu_back/actu_add';

//modification
$route['backoffice/biere/modifier/(:any)'] = 'backoffice/Beer_back/beer_mod/$1';

$route['backoffice/jeux/modifier/(:any)'] = 'backoffice/Game_back/Game_mod/$1';

$route['backoffice/actualite/modifier/(:any)'] = 'backoffice/Actu_back/actu_mod/$1';

//suppression
$route['backoffice/biere/supprimer/(:any)'] = 'backoffice/Beer_back/beer_del/$1';

$route['backoffice/jeux/supprimer/(:any)'] = 'backoffice/Game_back/game_del/$1';

$route['backoffice/actualite/supprimer/(:any)'] = 'backoffice/Actu_back/actu_del/$1';

//validation
$route['backoffice/biere/validation/ajout'] = 'backoffice/Beer_back/add_beer';
$route['backoffice/biere/validation/modifier'] = 'backoffice/Beer_back/beer_mod_result';
$route['backoffice/biere/validation/supprimer/(:any)'] = 'backoffice/Beer_back/beer_del_validation/$1';

$route['backoffice/jeux/validation/ajout'] = 'backoffice/Game_back/game_add';
$route['backoffice/jeux/validation/modifier'] = 'backoffice/Game_back/Game_mod_result';
$route['backoffice/jeux/validation/supprimer/(:any)'] = 'backoffice/Game_back/game_del_validation/$1';

$route['backoffice/actualite/validation/ajout'] = 'backoffice/Actu_back/add_actu_valid';
$route['backoffice/actualite/validation/modifier'] = 'backoffice/Actu_back/mod_actu_valid';
$route['backoffice/actualite/validation/supprimer/(:any)'] = 'backoffice/Actu_back/actu_del_validation/$1';

//pdf
$route['backoffice/jeux/pdf/(:num)'] = 'backoffice/Game_back/pdf/$1';
$route['backoffice/jeux/pdfAll'] = 'backoffice/Game_back/pdf_all';

//connexion
$route['backoffice'] = 'backoffice/User_back';
$route['backoffice/connexion'] = 'backoffice/User_back/connexion';

$route['backoffice/user/modifier/mdp'] = 'backoffice/User_back/mdp_mod';
$route['backoffice/user/validation/modifier/mdp'] = 'backoffice/User_back/mdp_mod_valid';

$route['backoffice/user/modifier/mail'] = 'backoffice/User_back/mail_mod';
$route['backoffice/user/validation/modifier/mail'] = 'backoffice/User_back/mail_mod_valid';


//client
    //biere
$route['biere'] = 'biere/Beer/beer_list';
$route['biere/(:any)'] = 'biere/Beer/produit/$1';

    //jeux
$route['jeux'] = 'jeu/Game/game_list';
$route['jeux/(:any)'] = 'jeu/Game/produit/$1';

    //actualite
$route['actualite'] = 'actu/Actu/actu_list';
$route['actualite/(:any)'] = 'actu/Actu/produit/$1';