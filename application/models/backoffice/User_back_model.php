<?php

/**
 * Created by PhpStorm.
 * User: tangu
 * Date: 13/01/2017
 * Time: 14:29
 */
class User_back_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function recupuser($mail)
    {
        $this->db->select('*')
            ->from('sam_user')
            ->where('user_mail', $mail);
        $query = $this->db->get();
        return $query->result();
    }

    public function test_co($mail, $mdp, $recup)
    {
        $co = 0;
        foreach ($recup as $unuser) {
            if ($unuser->user_mail == $mail && $unuser->user_mdp == $mdp) {
                $co = 1;
            }
        }
        return $co;
    }

    public function mdp_mod($mdp, $mail)
    {
        $this->db->set('user_mdp', $mdp)
            ->where('user_mail', $mail);
        return $this->db->update('sam_user');
    }

    public function mail_mod($mail, $oldmail)
    {
        $this->db->set('user_mail', $mail)
            ->where('user_mail', $oldmail);
        return $this->db->update('sam_user');
    }
}
