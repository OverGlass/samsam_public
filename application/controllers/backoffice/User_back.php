<?php

class User_back extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Twig');
        $this->load->library('session');
        $this->load->model('backoffice/User_back_model');
        $this->twig->addGlobal('session', $_SESSION);
    }

    public function index()
    {
        //formulaire de connexion
        $this->twig->display('backoffice/connexion');
    }

    public function connexion()
    {
        //on recupere les infos entrées dans le formulaire
        $mail = $this->input->post('u_mail');
        $pwd = $this->input->post('u_mdp');

        //encodage du mot de passe
        $pwd = md5($pwd);

        //on recupere l'utilisateur dans la bdd qui correspond au mail entré
        $recup = $this->User_back_model->recupuser($mail);

        //si on a recuperé quelque chose dans recup
        if (isset($recup)) {
            //on verifie si le mail et le mdp entrés par l'user corresponde à ce qui est dans la bdd
            $co = $this->User_back_model->test_co($mail, $pwd, $recup);

            //si le test est correct
            if ($co == 1) {
                //on met en session le mail et une variable pour verifier que l'utilisateur est connecté
                $data = array(
                    'mail' => $mail,
                    'logged_in' => true
                );
                $this->session->set_userdata($data);
                //redirection vers la liste des bieres
                redirect(base_url() . 'backoffice/biere');
            } else {
                $newdata['echec'] = true;
                $this->twig->display('backoffice/echec', $newdata);
            }
        } else {
            $newdata['echec'] = true;
            $this->twig->display('backoffice/echec', $newdata);
        }
    }

    public function deconnexion()
    {
        //on detruit les variables SESSION pour déconnecter l'utilisateur
        $this->session->unset_userdata(array('mail', 'logged_in'));
        header('location: ' . base_url() . 'backoffice/');
    }

    public function mdp_mod()
    {
        //modification du mot de passe
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            $this->twig->display('backoffice/form_mod_user');
        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function mdp_mod_valid()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

            //on recupere l'ancien mot de passe
            $old = $this->input->post('u_old_mdp', TRUE);
            //encodage
            $old = md5($old);

            //recuperation du nouveau mot de passe
            $new1 = $this->input->post('u_new_mdp', TRUE);
            $new2 = $this->input->post('u_new_mdp_2', TRUE);
            //verification que l'utilisateur a entré deux fois le meme mot de passe
            if ($new1 !== $new2) {
                //si il sont différents, on retourne sur le formulaire
                $data = array(
                    'echec' => 'Les mots de passe ne sont pas identiques.'
                );
                $this->twig->display('backoffice/form_mod_user', $data);
            } else {

                //encodage
                $new1 = md5($new1);

                //recuperation des users de la bdd
                $recup = $this->User_back_model->recupuser($_SESSION['mail']);

                foreach ($recup as $user) {
                    //si les infos de la bdd correspondent aux infos entrées par l'user
                    if ($user->user_mdp == $old) {
                        //modification du mot de passe
                        $this->User_back_model->mdp_mod($new1, $user->user_mail);
                        //page de validation
                        $data = array(
                            'mdp' => true
                        );
                        $this->twig->display('backoffice/validation', $data);
                    } else {
                        $data = array(
                            'echec' => 'L\'ancien mot de passe est incorrect.'
                        );
                        $this->twig->display('backoffice/form_mod_user', $data);
                    }
                }
            }
        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function mail_mod()
    {
        //modification du mot de passe
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            $this->twig->display('backoffice/form_mod_mail');
        } else {
            $this->twig->display('backoffice/echec');
        }
    }

    public function mail_mod_valid()
    {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            $mail = $this->input->post('u_mail', TRUE);
            $mail2 = $this->input->post('u_mail_2', TRUE);
            $oldmail = $_SESSION['mail'];
            if ($mail == $mail2){
                $this->User_back_model->mail_mod($mail, $oldmail);
                $data = array(
                    'mail' => true
                );
                $this->session->unset_userdata('mail');
                $this->session->set_userdata('mail', $mail);
                $this->twig->display('backoffice/validation', $data);
            } else {
                $data = array(
                    'echec' => true
                );
                $this->twig->display('backoffice/form_mod_mail', $data);
            }

        } else {
            $this->twig->display('backoffice/echec');
        }

    }
}