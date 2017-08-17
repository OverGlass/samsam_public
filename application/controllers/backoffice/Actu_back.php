<?php

class Actu_back extends CI_Controller
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
        $this->load->model('backoffice/Actu_back_model');
        $this->load->library('session');
        $this->twig->addGlobal('session', $_SESSION);
        $this->load->helper('form');
        $this->load->library('ftp');
    }

    public function actu_list()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true){

            $actu = $this->Actu_back_model->get_all_from_bdd();
            $data = array(
                'actus' => $actu
            );
            $this->twig->display('backoffice/actualite/actu_list', $data);
        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function actu_add()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true){

            $this->twig->display('backoffice/actualite/form_add_actu');
        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function add_actu_valid()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true){

            $name = $this->input->post('a_nom');
            $date = $this->input->post('a_date');
            $contenu = $this->input->post('a_contenu');
            $categorie = $this->input->post('a_categorie');
            $lieu = $this->input->post('a_lieu');
            $a_image = $this->input->post('a_image');
            $tableau = $_FILES['img_upload']['name'];
            if (empty($tableau)){
                    $image = $this->input->post('a_image');
            } else {
                $config['upload_path']          = './image_tempo/';
                $config['allowed_types']        = 'gif|jpg|png';
                //$config['max_size']             = 2048;
                $config['max_width']            = 0;
                $config['max_height']           = 0;

                $this->load->library('upload', $config);

                $this->upload->do_upload('img_upload');

                $this->ftp->connect($this->_config);

                $list_actus = $this->ftp->list_files('/www/prod/assets/img/events/');
                $deja_existante = 0;
                foreach ($list_actus as $actu){
                    if ($actu == $_FILES['img_upload']['name']){
                        $deja_existante = 1;
                    }
                }

                $nom = $_FILES['img_upload']['name'];

                if ($deja_existante == 0){
                    $this->ftp->move('/www/prod/image_tempo/'.$nom, '/www/prod/assets/img/events/'.$nom);
                }

                $this->ftp->close();
                $image = base_url().'assets/img/events/'.$nom;
            }

            $this->Actu_back_model->add_actu($name, $date, $contenu, $categorie, $lieu, $image);

            $data = array(
                'add' => true
            );
            $this->twig->display('backoffice/actualite/validation', $data);
        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function actu_mod($id)
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true){

            $actu = $this->Actu_back_model->get_one_from_bdd($id);
            $data = array(
                'actu' => $actu,
                'id' => $id
            );
            $this->twig->display('backoffice/actualite/form_mod_actu', $data);
        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function mod_actu_valid()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true){

            $name = $this->input->post('a_nom');
            $date = $this->input->post('a_date');
            $contenu = $this->input->post('a_contenu');
            $categorie = $this->input->post('a_categorie');
            $lieu = $this->input->post('a_lieu');
            $image = $this->input->post('a_image');
            $id = $this->input->post('a_id');

            $tableau = $_FILES['img_upload']['name'];
            if (empty($tableau)){
                $image = $this->input->post('a_image');
            } else {
                $config['upload_path']          = './image_tempo/';
                $config['allowed_types']        = 'gif|jpg|png';
                //$config['max_size']             = 2048;
                $config['max_width']            = 0;
                $config['max_height']           = 0;

                $this->load->library('upload', $config);

                $this->upload->do_upload('img_upload');

                $this->ftp->connect($this->_config);

                $old_nom_tab = $this->Actu_back_model->get_nom($id);
                foreach ($old_nom_tab as $nom){
                    $old_nom = $nom->actu_image;
                    $old_nom = str_replace('http://samsamcafe.fr/', '/www/', $old_nom);
                }

                $list_actus = $this->ftp->list_files('/www/prod/assets/img/events/');
                $exist = 0;
                $deja_existante = 0;
                foreach ($list_actus as $actu){
                    if ($old_nom == '/www/prod/assets/img/events/'.$actu){
                        $exist = 1;
                    }
                    if ($actu == $_FILES['img_upload']['name']){
                        $deja_existante = 1;
                    }
                }
                if ($exist == 1){
                    $this->ftp->delete_file($old_nom);
                }

                $nom = $_FILES['img_upload']['name'];

                if ($deja_existante == 0){
                    $this->ftp->move('/www/prod/image_tempo/'.$nom, '/www/prod/assets/img/events/'.$nom);
                }

                $this->ftp->close();
                $image = base_url().'assets/img/events/'.$nom;
            }

            $this->Actu_back_model->mod_actu($name, $date, $contenu, $categorie, $lieu, $image, $id);

            $data = array(
                'mod' => true
            );

            $this->twig->display('backoffice/actualite/validation', $data);
        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function actu_del($id)
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true){

            //envoie de l'id sur une page de validation
            $data = array(
                'id' => $id
            );
            $this->twig->display('backoffice/actualite/actu_del_validation', $data);
        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function actu_del_validation($id)
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true){

            $this->ftp->connect($this->_config);

            $old_nom_tab = $this->Actu_back_model->get_nom($id);
            foreach ($old_nom_tab as $nom){
                $old_nom = $nom->actu_image;
                $old_nom = str_replace('http://samsamcafe.fr/', '/www/', $old_nom);
            }
            $list_bieres = $this->ftp->list_files('/www/prod/assets/img/bieres/');
            $exist = 0;
            foreach ($list_bieres as $biere){
                if ($old_nom == '/www/prod/assets/img/bieres/'.$biere){
                    $exist = 1;
                }
            }
            $bieres = $this->Actu_back_model->get_all_from_bdd();
            $deja_existante = 0;
            foreach ($bieres as $une){
                if ($une->actu_nom == $old_nom){
                    $deja_existante = 1;
                }
            }
            if ($exist == 1 && $deja_existante == 0){
                $this->ftp->delete_file($old_nom);
            }

            $this->ftp->close();

            $this->Actu_back_model->actu_del($id);
            $data = array(
                'del' => TRUE
            );
            $this->twig->display('backoffice/actualite/validation', $data);
        } else {
            $this->twig->display('backoffice/echec');
        }
    }
}