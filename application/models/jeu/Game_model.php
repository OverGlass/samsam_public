<?php

/**
 * Created by PhpStorm.
 * User: tangu
 * Date: 10/01/2017
 * Time: 11:18
 */
class Game_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function affiche_all()
    {
        $this->db->select('*')
            ->from('sam_jeu');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_one($id)
    {
        $this->db->select('*')
            ->from('sam_jeu')
            ->where('jeu_id', $id);
        $query = $this->db->get();
        return $query->result();
    }
}