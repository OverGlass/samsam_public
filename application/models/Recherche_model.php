<?php

/**
 * Created by PhpStorm.
 * User: tangu
 * Date: 12/01/2017
 * Time: 17:10
 */
class Recherche_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_from_beer($nom)
    {
        $this->db->select('*')
            ->from('sam_biere')
            ->like('biere_nom', $nom)
            ->or_like('biere_type', $nom)
            ->or_like('biere_brasserie', $nom);
        return $this->db->get();
    }

    public function get_all_from_game($nom)
    {
        $this->db->select('*')
            ->from('sam_jeu')
            ->like('jeu_nom', $nom)
            ->or_like('jeu_type', $nom);
        return $this->db->get();
    }

    public function get_all_from_actu($nom)
    {
        $this->db->select('*')
            ->from('sam_actu')
            ->like('actu_nom', $nom);
        return $this->db->get();
    }

    public function get_all_from_ambiance()
    {
        $this->db->select('*')
            ->from('sam_jeu_ambiance');
        return $this->db->get();
    }
}
