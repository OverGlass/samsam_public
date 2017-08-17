<?php

/**
 * Created by PhpStorm.
 * User: tangu
 * Date: 03/01/2017
 * Time: 14:00
 */
class Beer extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Twig');
        $this->load->model('biere/Beer_model');
    }

    public function beer_list()
    {
        $beer = $this->Beer_model->affiche_all();

        // On récupère les brasseries de chaque bière
        $allBreweries = [];
        $allAmertumes = [];

        for($i = 0; $i < count($beer); $i++) {
            array_push($allBreweries,$beer[$i]->biere_brasserie);
            array_push($allAmertumes,$beer[$i]->biere_amertume);
        }

        // On enleve les doublons
        $breweries = array_unique($allBreweries);
        $amertumes = array_unique($allAmertumes);

        $data = array(
            'beer' => $beer,
            'breweries' => $breweries,
            'amertumes' => $amertumes
        );

        $this->twig->display('biere/beer_view', $data);
    }

    public function produit($id)
    {
        $beer = $this->Beer_model->get_one($id);
        $data = array(
            'beer' => $beer
        );
        $this->twig->display('biere/beer_product', $data);
    }
}
