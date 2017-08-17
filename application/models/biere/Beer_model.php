<?php

class Beer_model extends CI_Model
{
    //load du fichier config/database.php

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    //query qui recupere la liste des bieres dans la bdd

    public function affiche_all()
    {
        $this->db->select('*')
            ->from('sam_biere')
            ->where('biere_dispo', 1);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_one($id)
    {
        $this->db->select('*')
            ->from('sam_biere')
            ->where('biere_id', $id);
        $query = $this->db->get();
        return $query->result();
    }
}