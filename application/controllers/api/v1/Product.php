<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("Recherche_model");
    }

    public function view($nom)
    {
        $nom = urldecode($nom);
        $biere = $this->Recherche_model->get_all_from_beer($nom);
        $jeu = $this->Recherche_model->get_all_from_game($nom);
        $actu = $this->Recherche_model->get_all_from_actu($nom);
        $ambiance = $this->Recherche_model->get_all_from_ambiance();

        if ($biere->num_rows() > 0 || $jeu->num_rows() > 0 || $actu->num_rows() > 0) {
            foreach ($biere->result() as $row) {
                $beer[] = array(
                    "biere_id" => intval($row->biere_id),
                    "biere_nom" => $row->biere_nom,
                    "biere_description" => $row->biere_description,
                    "biere_provenance" => $row->biere_provenance,
                    "biere_type" => $row->biere_type,
                    "biere_pourcentage" => $row->biere_pourcentage,
                    "biere_amertume" => $row->biere_amertume,
                    "biere_reference" => $row->biere_reference,
                    "biere_photo" => $row->biere_photo,
                    "biere_dispo" => $row->biere_dispo,
                    "biere_brasserie" => $row->biere_brasserie,
                );
            }

            foreach ($jeu->result() as $row) {
                $jeux[] = array(
                    'jeu_id' => intval($row->jeu_id),
                    'jeu_nom' => $row->jeu_nom,
                    'jeu_description' => $row->jeu_description,
                    'jeu_date' => $row->jeu_date,
                    'jeu_nb_joueur_min' => $row->jeu_nb_joueur_min,
                    'jeu_nb_joueur_max' => $row->jeu_nb_joueur_max,
                    'jeu_difficulte' => $row->jeu_difficulte,
                    'jeu_duree' => $row->jeu_duree,
                    'jeu_age' => $row->jeu_age,
                    'jeu_type' => $row->jeu_type,
                    'jeu_image' => $row->jeu_image,
                    'ambiance_id_' => $row->ambiance_id_,
                    'jeu_coop' => $row->jeu_coop,
                );
            }

            foreach ($actu->result() as $row) {
                $actus[] = array(
                    'actu_id' => intval($row->actu_id),
                    'actu_nom' => $row->actu_nom,
                    'actu_date' => $row->actu_date,
                    'actu_categorie' => $row->actu_categorie,
                    'actu_lieu' => $row->actu_lieu,
                    'actu_image' => $row->actu_image,
                    'actu_contenu' => $row->actu_contenu,
                );
            }

            foreach ($ambiance->result() as $row) {
                $ambiances[] = array(
                    'ambiance_id' => intval($row->ambiance_id),
                    'ambiance_nom' => $row->ambiance_nom,
                );
            }

            if (empty($beer)) {
                $beer = array(
                    'vide' => true
                );
            }
            if (empty($jeux)) {
                $jeux = array(
                    'vide' => true
                );
            }
            if (empty($actus)) {
                $actus = array(
                    'vide' => true
                );
            }
            $result = array(
                'biere' => $beer,
                'jeux' => $jeux,
                'actus' => $actus,
                'ambiance' => $ambiances
            );
            echo json_encode($result);
        } else {
            $result = array(
                'erreur' => 'Le produit n\'a pas été trouvé'
            );
            echo json_encode($result);
        }
    }
}

/* End of file Product.php */
/* Location: ./application/controllers/Product.php */
