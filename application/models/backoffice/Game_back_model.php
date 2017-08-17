<?php

class Game_back_model extends CI_Model
{
    private $_url = 'https://www.boardgamegeek.com/xmlapi/';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_from_bdd()
    {
        $this->db->select('*')
            ->from('sam_jeu');
        $query = $this->db->get();
        return $query->result();
    }

    public function searchGames($humanQuery)
    {
        //remplacement des espaces par leur code http
        $query = str_replace(' ', '%20', $humanQuery);

        //on créer le lien avec les différentes variables
        $lien = $this->_url . 'search?search=' . $query;

        //utilisation de DOM pour recuperer le contenu xml correspondant à l'url dans $lien
        $dom = new DOMDocument();
        $dom->load($lien);
        $myXMLData = $dom->saveXML();

        //on encode en json puis on decode pour obtenir un tableau php
        $xml = json_decode(
            json_encode(
                simplexml_load_string(
                    $myXMLData
                )
            ), true
        );

        //on obtient un tableau avec des caractères que twig ne peut pas lire : @
        //pour resoudre cela, on parcours le tableau une première fois en php pour récuperer l'id du jeu que l'on met dans un tableau $id
        if (isset($xml['boardgame']) && !(empty($xml['boardgame']))) {
            $length = count($xml['boardgame']);
            $id = array();
            if (isset($xml['boardgame'][0])) {
                for ($i = 0; $i < $length; $i++) {
                    $id[] = $xml['boardgame'][$i]['@attributes'];
                }
            } else {
                $id[] = $xml['boardgame']['@attributes'];
            }
            $value['id'] = $id;
        }
        $value['info'] = $xml;
        //on met les 2 tableaux obtenus dans une variable que l'on récupère dans le controller
        return $value;
    }

    public function get_one_from_api($id)
    {
        //on créer le lien avec les différentes variables
        $lien = $this->_url . 'boardgame/' . $id . '?stats=1';

        //utilisation de DOM pour recuperer le contenu xml correspondant à l'url dans $lien
        $dom = new DOMDocument();
        $dom->load($lien);
        $myXMLData = $dom->saveXML();

        //on encode en json puis on decode pour obtenir un tableau php
        $xml = json_decode(
            json_encode(
                simplexml_load_string(
                    $myXMLData
                )
            ), true
        );
        return $xml;
    }

    public function add_game($nom, $description, $date, $joueur_min, $joueur_max, $duree, $difficulte, $age, $ambiance, $coop, $type, $image)
    {
        //ajout d'un jeu dans la bdd

        $data = array(
            'jeu_nom' => $nom,
            'jeu_description' => $description,
            'jeu_date' => $date,
            'jeu_nb_joueur_min' => $joueur_min,
            'jeu_nb_joueur_max' => $joueur_max,
            'jeu_duree' => $duree,
            'jeu_difficulte' => $difficulte,
            'jeu_age' => $age,
            'ambiance_id_' => $ambiance,
            'jeu_type' => $type,
            'jeu_coop' => $coop,
            'jeu_image' => $image
        );
        $this->db->insert('sam_jeu', $data);
    }

    public function get_one_from_bdd($id)
    {
        //récupération d'une bière en fonction de son id dans la bdd
        $this->db->select('*')
            ->from('sam_jeu')
            ->where('jeu_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function mod_game($id, $nom, $description, $date, $joueur_min, $joueur_max, $duree, $difficulte, $age, $ambiance, $coop, $type, $image)
    {
        //ajout d'un jeu dans la bdd

        $data = array(
            'jeu_nom' => $nom,
            'jeu_description' => $description,
            'jeu_date' => $date,
            'jeu_nb_joueur_min' => $joueur_min,
            'jeu_nb_joueur_max' => $joueur_max,
            'jeu_duree' => $duree,
            'jeu_difficulte' => $difficulte,
            'jeu_age' => $age,
            'ambiance_id_' => $ambiance,
            'jeu_type' => $type,
            'jeu_coop' => $coop,
            'jeu_image' => $image
        );
        $this->db->set($data);
        $this->db->where('jeu_id', $id);
        return $this->db->update('sam_jeu');
    }

    public function game_del($id)
    {
        $this->db->where('jeu_id', $id)
            ->delete('sam_jeu');
    }

    public function get_ambiance()
    {
        $this->db->select('*')
            ->from('sam_jeu_ambiance');
        $query = $this->db->get();
        return $query->result();
    }

    public function generepdf($id){
        $this->db->select('*');
        $this->db->from('sam_jeu');
        $this->db->where('jeu_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_nom($id)
    {
        $this->db->select('jeu_image')
            ->from('sam_jeu')
            ->where('jeu_id', $id);
        $query = $this->db->get();
        return $query->result();
    }
}
