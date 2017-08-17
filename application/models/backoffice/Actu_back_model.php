<?php

class Actu_back_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_from_bdd()
    {
        $this->db->select('*')
            ->from('sam_actu');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_one_from_bdd($id)
    {
        $this->db->select('*')
            ->from('sam_actu')
            ->where('actu_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function add_actu($nom, $date, $contenu, $categorie, $lieu, $image)
    {
        $data = array(
            'actu_nom' => $nom,
            'actu_date' => $date,
            'actu_contenu' => $contenu,
            'actu_categorie' => $categorie,
            'actu_lieu' => $lieu,
            'actu_image' => $image
        );
        $this->db->insert('sam_actu', $data);
    }

    public function mod_actu($nom, $date, $contenu, $categorie, $lieu, $image, $id)
    {
        $data = array(
            'actu_nom' => $nom,
            'actu_date' => $date,
            'actu_contenu' => $contenu,
            'actu_categorie' => $categorie,
            'actu_lieu' => $lieu,
            'actu_image' => $image
        );
        $this->db->set($data)
            ->where('actu_id', $id);
        return $this->db->update('sam_actu');
    }

    public function actu_del($id)
    {
        return $this->db->where('actu_id', $id)
            ->delete('sam_actu');
    }

    public function get_nom($id)
    {
        $this->db->select('actu_image')
            ->from('sam_actu')
            ->where('actu_id', $id);
        $query = $this->db->get();
        return $query->result();
    }
}
