<?php

/**
 * Created by PhpStorm.
 * User: tangu
 * Date: 10/01/2017
 * Time: 11:14
 */
class Game extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Twig');
        $this->load->model('jeu/Game_model');
    }

    public function game_list()
    {
        $jeux = $this->Game_model->affiche_all();
        // On récupère les types de chaque jeu
        $allTypes = [];
        $allTime = [];

        for($i = 0; $i < count($jeux); $i++) {
            array_push($allTypes,$jeux[$i]->jeu_type);
            array_push($allTime,$jeux[$i]->jeu_duree);
        }

        // On enleve les doublons
        $types = array_unique($allTypes);
        $minTime = min($allTime);
        $maxTime= max($allTime);

        $data = array(
            'jeux' => $jeux,
            'types' => $types,
            'minTime' => $minTime,
            'maxTime' => $maxTime

        );
        $this->twig->display('jeu/game_view', $data);
    }

    public function produit($id)
    {
        $jeu = $this->Game_model->get_one($id);
        $data = array(
            'jeu' => $jeu
        );
        $this->twig->display('jeu/game_product', $data);
    }
}
