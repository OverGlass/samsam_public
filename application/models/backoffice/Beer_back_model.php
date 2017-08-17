<?php

class Beer_back_model extends CI_Model
{
    //Résout un problème de SSL

    public $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ));
    private $_url = "https://api.brewerydb.com/v2/";
    private $_key = "?key=4ed7a74b2e9392a03a29ad5f23c83586&";

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    //recuperation des bieres dans l'api

    public function searchBeers($humanQuery)
    {
        //remplacement des espaces par leur code http
        $query = str_replace(' ', '%20', $humanQuery);
        //récupération du fichier json envoyé par l'api et traduction
        return json_decode(
            file_get_contents(
                $this->_url
                . 'search'
                . $this->_key
                . 'q="' . $query
                . '"&withBreweries=y&type=beer&format=json',
                false,
                stream_context_create($this->arrContextOptions)
            )
            , true
        );
    }

    public function affiche_une($beer_id)
    {
        //récupération des données de l'api pour une bière
        return json_decode(
            file_get_contents(
                $this->_url
                . 'beer/'
                . $beer_id
                . $this->_key
                . 'withBreweries=y&format=json',
                false,
                stream_context_create($this->arrContextOptions)
            )
            , true
        );
    }

    public function couleur($beer)
    {
        //cette fonction récupère le srm des bière et determine la couleur de celle ci

        //on verifie si les valeurs srmMax et min existe dans l'api
        if (isset($beer['data']['style']['srmMin']) && isset($beer['data']['style']['srmMax'])) {
            $srmMin = $beer['data']['style']['srmMin'];
            $srmMax = $beer['data']['style']['srmMax'];
            //on fait une moyenne des 2 pour se rapprocher de la réalité
            $moyenne = ($srmMax + $srmMin) / 2;

            //on retourne la couleur
            if ($moyenne < 4) {
                return 'blanche';
            } elseif ($moyenne < 6) {
                return 'blonde';
            } elseif ($moyenne < 15) {
                return 'ambrée';
            } elseif ($moyenne < 25) {
                return 'brune';
            } elseif ($moyenne >= 25) {
                return 'noire';
            }
        } else {
            //si srm n'existe pas dans l'api c'est que la bière est rouge
            return 'rouge';
        }
    }

    public function add_beer($name, $description, $provenance, $type, $pourcentage, $amertume, $brasserie, $reference, $image)
    {
        //ajout d'une bière dans l'api
        $data = array(
            'biere_nom' => $name,
            'biere_description' => $description,
            'biere_provenance' => $provenance,
            'biere_type' => $type,
            'biere_pourcentage' => $pourcentage,
            'biere_amertume' => $amertume,
            'biere_brasserie' => $brasserie,
            'biere_reference' => $reference,
            'biere_photo' => $image,
            'biere_dispo' => 1
        );

        $this->db->insert('sam_biere', $data);
    }

    public function get_all_from_bdd()
    {
        //récupération de toute les bières de la bdd
        $this->db->select('*')
            ->from('sam_biere');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_one_from_bdd($id)
    {
        //récupération d'une bière en fonction de son id dans la bdd
        $this->db->select('*')
            ->from('sam_biere')
            ->where('biere_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function beer_mod($id, $nom, $description, $provenance, $type, $pourcentage, $amertume, $brasserie, $reference, $image)
    {
        //modif d'une bière dans la bdd
        $data = array(
            'biere_nom' => $nom,
            'biere_description' => $description,
            'biere_provenance' => $provenance,
            'biere_type' => $type,
            'biere_pourcentage' => $pourcentage,
            'biere_amertume' => $amertume,
            'biere_brasserie' => $brasserie,
            'biere_reference' => $reference,
            'biere_photo' => $image,
        );
        $this->db->set($data);
        $this->db->where('biere_id', $id);
        return $this->db->update('sam_biere');
    }

    public function beer_del($id)
    {
        //suppression d'une bière dans la bdd
        return $this->db->where('biere_id', $id)
            ->delete('sam_biere');
    }

    public function dispo($check, $empty)
    {
        //cette fonction permet de gérer la disponibilité des bières dans le bar

        //on vérifie si les tableaux envoyés par le controller
        if (!(empty($check))) {
            foreach ($check as $value) {
                $this->db->set('biere_dispo', 1)
                    ->where('biere_id', $value);
                $this->db->update('sam_biere');
            }
        }
        if (!(empty($empty))) {
            foreach ($empty as $value) {
                $this->db->set('biere_dispo', 0)
                    ->where('biere_id', $value);
                $this->db->update('sam_biere');
            }
        }
    }

    public function get_nom($id)
    {
        $this->db->select('biere_photo')
            ->from('sam_biere')
            ->where('biere_id', $id);
        $query = $this->db->get();
        return $query->result();
    }


}
