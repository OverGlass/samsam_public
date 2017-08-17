<?php
/**
 * Created by PhpStorm.
 * User: tangu
 * Date: 13/01/2017
 * Time: 18:17
 */
class Actu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Twig');
        $this->load->model('actu/Actu_model');
    }

    public function actu_list()
    {
        $actu = $this->Actu_model->affiche_all();

        // On récupère les brasseries de chaque bière
        $allCategorie = [];

        for($i = 0; $i < count($actu); $i++) {
            array_push($allCategorie,$actu[$i]->actu_categorie);
        }

        // On enleve les doublons
        $categorie = array_unique($allCategorie);

        $data = array(
            'actus' => $actu,
            'categories' => $categorie,

        );

        $this->twig->display('actu/actu_view', $data);
    }

    public function produit($id)
    {
        $actu = $this->Actu_model->get_one($id);
        $data = array(
            'actu' => $actu
        );
        $this->twig->display('actu/actu_product', $data);
    }
}
