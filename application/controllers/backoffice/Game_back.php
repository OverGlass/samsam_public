<?php

class Game_back extends CI_Controller
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
        $this->load->model('backoffice/Game_back_model');
        $this->load->library('session');
        $this->twig->addGlobal('session', $_SESSION);
        $this->load->helper('form');
        $this->load->library('ftp');
    }

    public function game_list()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

            //affichage de la liste des bières de la bdd
            $games = $this->Game_back_model->get_all_from_bdd();
            $data = array(
                'games' => $games
            );
            $this->twig->display('backoffice/jeu/game_list', $data);
        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function search()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

            //affichage de la page de formulaire de recherche
            $this->twig->display('backoffice/jeu/search');
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
            $games = $this->Game_back_model->searchGames($humanQuery);

            //affichage des résultats
            //$games contient 2 tableaux : id et info
            $data = array(
                'games' => $games
            );
            $this->twig->display('backoffice/jeu/game_from_api_view', $data);
        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function form_add_game($id)
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

            $game = $this->Game_back_model->get_one_from_api($id);
            $ambiance = $this->Game_back_model->get_ambiance();
            $nom = $game['boardgame']['name'];
            if (is_array($nom)) {
                $test = 1;
            } else {
                $test = 0;
            }
            $data = array(
                'game' => $game,
                'test' => $test,
                'ambiance' => $ambiance
            );
            $this->twig->display('backoffice/jeu/form_add_game', $data);
        } else {
            $this->twig->display('backoffice/echec');
        }

    }

    public function form_add_game_empty()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            $data = array(
                'vide' => 1,
                'game' => ''
            );
            $this->twig->display('backoffice/jeu/form_add_game', $data);

        } else {
            $this->twig->display('backoffice/echec');
        }

    }

    public function game_add()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            $name = $this->input->post('g_nom');
            $description = $this->input->post('g_description');
            $date = $this->input->post('g_date');
            $joueur_min = $this->input->post('g_joueur_min');
            $joueur_max = $this->input->post('g_joueur_max');
            $duree = $this->input->post('g_duree');
            $difficulte = $this->input->post('g_difficulte');
            $age = $this->input->post('g_age');
            $ambiance = $this->input->post('g_ambiance');
            $coop = $this->input->post('g_coop');
            $type = $this->input->post('g_type');
            $g_image = $this->input->post('g_image');
            $tableau = $_FILES['img_upload']['name'];

            $jeux = $this->Game_back_model->get_all_from_bdd();
            $test = 0;
            foreach ($jeux as $all) {
                if ($all->jeu_nom == $name) {
                    $test = 1;
                }
            }
            if ($test == 0) {

                if (empty($tableau)) {
                    $image = $this->input->post('g_image');
                } else {
                    $config['upload_path'] = './image_tempo/';
                    $config['allowed_types'] = 'gif|jpg|png';
                    //$config['max_size']             = 2048;
                    $config['max_width'] = 0;
                    $config['max_height'] = 0;

                    $this->load->library('upload', $config);

                    $this->upload->do_upload('img_upload');

                    $this->ftp->connect($this->_config);

                    $list_games = $this->ftp->list_files('/www/prod/assets/img/games/');
                    $deja_existante = 0;
                    foreach ($list_games as $game){
                        if ($game == $_FILES['img_upload']['name']){
                            $deja_existante = 1;
                        }
                    }

                    $nom = $_FILES['img_upload']['name'];

                    if ($deja_existante == 0){
                        $this->ftp->move('/www/prod/image_tempo/' . $nom, '/www/prod/assets/img/games/' . $nom);
                    }

                    $this->ftp->close();
                    $image = base_url() . 'assets/img/games/' . $nom;
                }

                $this->Game_back_model->add_game($name, $description, $date, $joueur_min, $joueur_max, $duree, $difficulte, $age, $ambiance, $coop, $type, $image);

                $data = array(
                    'add' => true
                );

                $this->twig->display('backoffice/jeu/validation', $data);
            } else {
                $data = array(
                    'check' => false
                );
                $this->twig->display('backoffice/jeu/validation', $data);
            }

        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function game_mod($id)
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            //recuperation des différentes valeurs de la bière
            $game = $this->Game_back_model->get_one_from_bdd($id);
            $ambiance = $this->Game_back_model->get_ambiance();
            $data = array(
                'game' => $game,
                'id' => $id,
                'ambiance' => $ambiance
            );
            $this->twig->display('backoffice/jeu/form_mod_game', $data);

        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function game_mod_result()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

            $name = $this->input->post('g_nom');
            $description = $this->input->post('g_description');
            $date = $this->input->post('g_date');
            $joueur_min = $this->input->post('g_joueur_min');
            $joueur_max = $this->input->post('g_joueur_max');
            $duree = $this->input->post('g_duree');
            $difficulte = $this->input->post('g_difficulte');
            $age = $this->input->post('g_age');
            $ambiance = $this->input->post('g_ambiance');
            $id = $this->input->post('g_id');
            $coop = $this->input->post('g_coop');
            $type = $this->input->post('g_type');
            $g_image = $this->input->post('g_image');

            $tableau = $_FILES['img_upload']['name'];
            if (empty($tableau)) {
                $image = $this->input->post('g_image');
            } else {
                $config['upload_path'] = './image_tempo/';
                $config['allowed_types'] = 'gif|jpg|png';
                //$config['max_size']             = 2048;
                $config['max_width'] = 0;
                $config['max_height'] = 0;

                $this->load->library('upload', $config);

                $this->upload->do_upload('img_upload');

                $this->ftp->connect($this->_config);

                $old_nom_tab = $this->Game_back_model->get_nom($id);
                foreach ($old_nom_tab as $nom) {
                    $old_nom = $nom->jeu_image;
                    $old_nom = str_replace('http://samsamcafe.fr/', '/www/', $old_nom);
                }
                $list_games = $this->ftp->list_files('/www/prod/assets/img/games/');
                $exist = 0;
                $deja_existante = 0;
                foreach ($list_games as $game){
                    if ($old_nom == '/www/prod/assets/img/games/'.$game){
                        $exist = 1;
                    }
                    if ($game == $_FILES['img_upload']['name']){
                        $deja_existante = 1;
                    }
                }
                if ($exist == 1){
                    $this->ftp->delete_file($old_nom);
                }

                $nom = $_FILES['img_upload']['name'];

                if ($deja_existante == 0){
                    $this->ftp->move('/www/prod/image_tempo/' . $nom, '/www/prod/assets/img/games/' . $nom);
                }

                $this->ftp->close();

                $image = base_url() . 'assets/img/games/' . $nom;
            }

            $this->Game_back_model->mod_game($id, $name, $description, $date, $joueur_min, $joueur_max, $duree, $difficulte, $age, $ambiance, $coop, $type, $image);

            $data = array(
                'mod' => true
            );

            $this->twig->display('backoffice/jeu/validation', $data);
        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function game_del($id)
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            //envoie de l'id sur une page de validation
            $data = array(
                'id' => $id
            );
            $this->twig->display('backoffice/jeu/game_del_validation', $data);

        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function game_del_validation($id)
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

            $this->ftp->connect($this->_config);

            $old_nom_tab = $this->Game_back_model->get_nom($id);
            foreach ($old_nom_tab as $nom) {
                $old_nom = $nom->jeu_image;
                $old_nom = str_replace('http://samsamcafe.fr/', '/www/', $old_nom);
            }
            $list_games = $this->ftp->list_files('/www/prod/assets/img/games/');
            $exist = 0;
            foreach ($list_games as $game){
                if ($old_nom == '/www/prod/assets/img/games/'.$game){
                    $exist = 1;
                }
            }
            $bieres = $this->Game_back_model->get_all_from_bdd();
            $deja_existante = 0;
            foreach ($bieres as $une){
                if ($une->jeu_nom == $old_nom){
                    $deja_existante = 1;
                }
            }
            if ($exist == 1 && $deja_existante == 0){
                $this->ftp->delete_file($old_nom);
            }

            $this->ftp->close();

            $this->Game_back_model->game_del($id);
            $data = array(
                'del' => TRUE
            );
            $this->twig->display('backoffice/jeu/validation', $data);

        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function pdf($id)
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            $data['unseul'] = $this->Game_back_model->generepdf($id);
            $this->load->view('backoffice/jeu/pdf_view', $data);

        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function pdf_all()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            $data['tous'] = $this->Game_back_model->get_all_from_bdd();
            $this->load->view('backoffice/jeu/pdf_all_view', $data);
        } else {
            $this->twig->display('backoffice/echec');
        }

    }
}