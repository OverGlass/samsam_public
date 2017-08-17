<?php

class Beer_back extends CI_Controller
{
    private $_config = array(
        'hostname' => 'ftp.samsamcafe.fr',
        'username' => 'samsamca',
        'password' => 'gXkWGhnR',
        'debug' => TRUE
    );


    public function __construct()
    {
        parent::__construct();
        $this->load->library('Twig');
        $this->load->model('backoffice/Beer_back_model');
        $this->load->library('session');
        $this->twig->addGlobal('session', $_SESSION);
        $this->load->helper('form');
        $this->load->library('ftp');
    }

    public function search()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

            //affichage de la page de formulaire de recherche
            $this->twig->display('backoffice/biere/search');
        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function search_result()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

            //récupération du formulaire de recherche
            $recherche = $this->input->post('recherche');
            $humanQuery = $recherche;
            //envoie des données dans l'api
            $beers = $this->Beer_back_model->searchBeers($humanQuery);
            //affichage des résultats
            $data = array(
                'beers' => $beers
            );
            $this->twig->display('backoffice/biere/beer_from_api_view', $data);
        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function form_add_beer($beer_id)
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

            //récupération des données d'une bière en fonction de son id dans l'api
            $beer = $this->Beer_back_model->affiche_une($beer_id);
            //différents tests pour connaitre la couleur de la bière en fonction de son srm
            $couleur = $this->Beer_back_model->couleur($beer);
            $data = array(
                'beer' => $beer,
                'couleur' => $couleur
            );
            //Affichage d'un formulaire prè-rempli avec les données de l'api
            $this->twig->display('backoffice/biere/form_add_beer', $data);
        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function form_add_beer_empty()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

            //différents tests pour connaitre la couleur de la bière en fonction de son srm
            $data = array(
                'beer' => ''
            );
            //Affichage d'un formulaire prè-rempli avec les données de l'api
            $this->twig->display('backoffice/biere/form_add_beer', $data);
        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function add_beer()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            //récupération des valeurs entrées dans le formulaire en post

            $name = $this->input->post('b_name');
            $description = $this->input->post('b_description');
            $provenance = $this->input->post('b_provenance');
            $type = $this->input->post('b_type');
            $pourcentage = $this->input->post('b_pourcentage');
            $amertume = $this->input->post('b_amertume');
            $brasserie = $this->input->post('b_brasserie');
            $reference = $this->input->post('b_reference');
            $test = $this->input->post('b_image');
            $tableau = $_FILES['img_upload']['name'];
            if (empty($tableau)){
                if (empty($test)){
                    $image = base_url().'assets/backoffice/img/biere_exemple.png';
                } else {
                    $image = $this->input->post('b_image');
                }
            } else {
                $config['upload_path']          = './image_tempo/';
                $config['allowed_types']        = 'gif|jpg|png';
                //$config['max_size']             = 2048;
                $config['max_width']            = 0;
                $config['max_height']           = 0;

                $this->load->library('upload', $config);

                $this->upload->do_upload('img_upload');

                $this->ftp->connect($this->_config);

                $list_bieres = $this->ftp->list_files('/www/prod/assets/img/bieres/');

                $deja_existante = 0;
                foreach ($list_bieres as $biere){
                    if ($biere == $_FILES['img_upload']['name']){
                        $deja_existante = 1;
                    }
                }

                $nom = $_FILES['img_upload']['name'];
                if ($deja_existante == 0){
                    $this->ftp->move('/www/prod/image_tempo/'.$nom, '/www/prod/assets/img/bieres/'.$nom);
                }

                $this->ftp->close();
                $image = base_url().'assets/img/bieres/'.$nom;
            }

            //ajout de la biere dans la bdd
            $this->Beer_back_model->add_beer($name, $description, $provenance, $type, $pourcentage, $amertume, $brasserie, $reference, $image);
            $data = array(
                'add' => TRUE,
            );
            $this->twig->display('backoffice/biere/validation', $data);

        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function beer_list()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

            //affichage de la liste des bières de la bdd
            $beers = $this->Beer_back_model->get_all_from_bdd();
            $data = array(
                'beers' => $beers
            );
            $this->twig->display('backoffice/biere/beer_list', $data);
        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function beer_mod($id)
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

            //recuperation des différentes valeurs de la bière
            $beer = $this->Beer_back_model->get_one_from_bdd($id);
            $data = array(
                'beer' => $beer,
                'id' => $id
            );
            $this->twig->display('backoffice/biere/form_beer_mod', $data);
        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function beer_mod_result()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

            $name = $this->input->post('b_name');
            $description = $this->input->post('b_description');
            $provenance = $this->input->post('b_provenance');
            $type = $this->input->post('b_type');
            $pourcentage = $this->input->post('b_pourcentage');
            $amertume = $this->input->post('b_amertume');
            $brasserie = $this->input->post('b_brasserie');
            $reference = $this->input->post('b_reference');
            $test = $this->input->post('b_image');

            $id = $this->input->post('b_id');
            $tableau = $_FILES['img_upload']['name'];
            if (empty($tableau)){
                if (empty($test)){
                    $image = base_url().'assets/backoffice/img/biere_exemple.png';
                } else {
                    $image = $this->input->post('b_image');
                }
            } else {
                $config['upload_path']          = './image_tempo/';
                $config['allowed_types']        = 'gif|jpg|png';
                //$config['max_size']             = 2048;
                $config['max_width']            = 0;
                $config['max_height']           = 0;

                $this->load->library('upload', $config);

                $this->upload->do_upload('img_upload');

                $this->ftp->connect($this->_config);

                $old_nom_tab = $this->Beer_back_model->get_nom($id);
                foreach ($old_nom_tab as $nom){
                    $old_nom = $nom->biere_photo;
                    $old_nom = str_replace('http://samsamcafe.fr/', '/www/', $old_nom);
                }

                $list_bieres = $this->ftp->list_files('/www/prod/assets/img/bieres/');
                $exist = 0;
                $deja_existante = 0;
                foreach ($list_bieres as $biere){
                    if ($old_nom == '/www/prod/assets/img/bieres/'.$biere){
                        $exist = 1;
                    }
                    if ($biere == $_FILES['img_upload']['name']){
                        $deja_existante = 1;
                    }
                }
                if ($exist == 1){
                    $this->ftp->delete_file($old_nom);
                }

                $nom = $_FILES['img_upload']['name'];
                if ($deja_existante == 0){
                    $this->ftp->move('/www/prod/image_tempo/'.$nom, '/www/prod/assets/img/bieres/'.$nom);
                }

                $this->ftp->close();

                $image = base_url().'assets/img/bieres/'.$nom;
            }

            //envoie des données dans la bdd
            $this->Beer_back_model->beer_mod($id, $name, $description, $provenance, $type, $pourcentage, $amertume, $brasserie, $reference, $image);
            $data = array(
                'mod' => TRUE,
            );
            $this->twig->display('backoffice/biere/validation', $data);
        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function beer_del($id)
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

            //envoie de l'id sur une page de validation
            $data = array(
                'id' => $id
            );
            $this->twig->display('backoffice/biere/beer_del_validation', $data);
        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function beer_del_validation($id)
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            $this->ftp->connect($this->_config);

            $old_nom_tab = $this->Beer_back_model->get_nom($id);
            foreach ($old_nom_tab as $nom){
                $old_nom = $nom->biere_photo;
                $old_nom = str_replace('http://samsamcafe.fr/', '/www/', $old_nom);
            }
            $list_bieres = $this->ftp->list_files('/www/prod/assets/img/bieres/');
            $exist = 0;
            foreach ($list_bieres as $biere){
                if ($old_nom == '/www/prod/assets/img/bieres/'.$biere){
                    $exist = 1;
                }
            }
            $bieres = $this->Beer_back_model->get_all_from_bdd();
            $deja_existante = 0;
            foreach ($bieres as $une){
                if ($une->biere_nom == $old_nom){
                    $deja_existante = 1;
                }
            }
            if ($exist == 1 && $deja_existante == 0){
                $this->ftp->delete_file($old_nom);
            }
            $this->ftp->close();

            $this->Beer_back_model->beer_del($id);
            $data = array(
                'del' => TRUE
            );
            $this->twig->display('backoffice/biere/validation', $data);

        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function add_in_bar()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            $check = $this->input->post('check[]');
            $empty = $this->input->post('empty[]');

            $this->Beer_back_model->dispo($check, $empty);

            redirect(base_url() . 'backoffice/Beer_back/beer_list');

        } else {
            $this->twig->display('backoffice/echec');
        }
    }


}