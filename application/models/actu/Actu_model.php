<?php

class Actu_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function affiche_all()
    {
        $this->db->select('*')
            ->from('sam_actu');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_one($id)
    {
        $this->db->select('*')
            ->from('sam_actu')
            ->where('actu_id', $id);
        $query = $this->db->get();
        return $query->result();
    }
}
